<?php
    /**
     * TransactionController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: TransactionController.php, v1.00 5/4/2023 8:38 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use Wojo\Core\Controller;
    use Wojo\Core\Membership;
    use Wojo\Core\Router;
    use Wojo\Core\User;
    use Wojo\Database\Paginator;
    use Wojo\Date\Date;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Language\Language;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class TransactionController extends Controller
    {
        /**
         * @param $request
         * @param $response
         * @param $services
         */
        public function __construct($request, $response, $services)
        {
            parent::__construct($request, $response, $services);
        }
        
        /**
         * index
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function index(): void
        {
            
            $enddate = (Validator::get('enddate_submit') && $_GET['enddate_submit'] <> '') ? Validator::sanitize($this->db->toDate($_GET['enddate_submit'], false)) : date('Y-m-d');
            $fromdate = Validator::get('fromdate_submit') ? Validator::sanitize($this->db->toDate($_GET['fromdate_submit'], false)) : null;
            
            if (Validator::get('fromdate_submit') && $_GET['fromdate_submit'] <> '') {
                $counter = $this->db->count(Membership::pTable, "WHERE `created` BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59' AND status = 1")->run();
                $where = "WHERE p.created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59' AND p.status = 1";
                
            } else {
                $counter = $this->db->count(Membership::pTable)->run();
                $where = null;
            }
            
            $pager = Paginator::instance();
            $pager->items_total = $counter;
            $pager->default_ipp = $this->core->perpage;
            $pager->path = Url::url(Router::$path, '?');
            $pager->paginate();
            
            $lg = Language::$lang;
            
            $sql = "
            SELECT p.*, m.title$lg as title, CONCAT(u.fname,' ',u.lname) AS name
              FROM `" . Membership::pTable . '` AS p
              LEFT JOIN ' . User::mTable . ' AS u ON p.user_id = u.id
              LEFT JOIN ' . Membership::mTable . " AS m ON p.membership_id = m.id
              $where
              ORDER BY created
              DESC " . $pager->limit;
            
            $row = $this->db->rawQuery($sql)->run();
            
            $this->view->crumbs = ['admin', Language::$word->TRX_PAY];
            $this->view->caption = Language::$word->TRX_PAY;
            $this->view->title = Language::$word->TRX_PAY;
            $this->view->subtitle = Language::$word->M_SUB3;
            
            $this->view->data = $row ?? null;
            $this->view->pager = $pager;
            
            $this->view->render('transaction', 'view/admin/');
        }
        
        /**
         * action
         *
         * @return void
         */
        public function action(): void
        {
            $getAction = Validator::get('action');
            if ($getAction) {
                switch ($getAction) {
                    // render chart
                    case 'sales':
                        $range = (isset($_GET['timerange'])) ? Validator::sanitize($_GET['timerange'], 'string', 6) : 'all';
                        
                        $data = array();
                        $reg_data = array();
                        $data['label'] = array();
                        $data['color'] = array();
                        $data['legend'] = array();
                        $data['preUnits'] = Utility::currencySymbol();
                        
                        $color = array(
                            '#03a9f4',
                            '#33BFC1',
                            '#ff9800',
                            '#e91e63',
                        );
                        
                        $labels = array(
                            Language::$word->TRX_SALES,
                            Language::$word->TRX_AMOUNT,
                            Language::$word->TRX_TAX,
                            Language::$word->TRX_COUPON,
                        );
                        
                        switch ($range) {
                            case 'day':
                                for ($i = 0; $i < 24; $i++) {
                                    $data['data'][$i]['m'] = $i;
                                    $reg_data[$i] = array(
                                        'hour' => $i,
                                        'sales' => 0,
                                        'amount' => 0,
                                        'tax' => 0,
                                        'coupon' => 0,
                                    );
                                }
                                
                                $sql = '
                                SELECT COUNT(id) AS sales, SUM(rate_amount) AS amount, SUM(tax) AS tax, SUM(coupon) AS coupon, HOUR(created) as hour
                                  FROM `' . Membership::pTable . '`
                                  WHERE DATE(created) = DATE(NOW())
                                  AND status = ?
                                  GROUP BY HOUR(created)
                                  ORDER BY hour;
                                ';
                                $query = $this->db->rawQuery($sql, array(1));
                                
                                foreach ($query->run() as $result) {
                                    $reg_data[$result->hour] = array(
                                        'hour' => $result->hour,
                                        'sales' => $result->sales,
                                        'amount' => $result->amount,
                                        'tax' => $result->tax,
                                        'coupon' => $result->coupon
                                    );
                                }
                                break;
                            
                            case 'week':
                                $date[] = array();
                                $date_start = strtotime('-' . date('w') . ' days');
                                for ($i = 0; $i < 7; $i++) {
                                    $date = date('Y-m-d', $date_start + ($i * 86400));
                                    $data['data'][$i]['m'] = Date::doDate('EE', date('D', strtotime($date)));
                                    $reg_data[date('w', strtotime($date))] = array(
                                        'day' => date('D', strtotime($date)),
                                        'sales' => 0,
                                        'amount' => 0,
                                        'tax' => 0,
                                        'coupon' => 0,
                                    );
                                }
                                
                                $sql = '
                                SELECT COUNT(id) AS sales, SUM(rate_amount) AS amount, SUM(tax) AS tax, SUM(coupon) AS coupon, DAYNAME(created) as created
                                  FROM `' . Membership::pTable . "`
                                  WHERE DATE(created) >= DATE('" . Validator::sanitize(date('Y-m-d', $date_start), 'string', 10) . "')
                                  AND YEAR(created) = YEAR(CURDATE())
                                  AND status = ?
                                  GROUP BY DAYNAME(created);
                                ";
                                $query = $this->db->rawQuery($sql, array(1));
                                
                                foreach ($query->run() as $result) {
                                    $reg_data[date('w', strtotime($date))] = array(
                                        'day' => Date::doDate('EE', date('D', strtotime($result->created))),
                                        'sales' => $result->sales,
                                        'amount' => $result->amount,
                                        'tax' => $result->tax,
                                        'coupon' => $result->coupon
                                    );
                                }
                                break;
                            
                            case 'month':
                                for ($i = 1; $i <= date('t'); $i++) {
                                    $date = date('Y') . '-' . date('m') . '-' . $i;
                                    $data['data'][$i]['m'] = date('d', strtotime($date));
                                    $reg_data[date('j', strtotime($date))] = array(
                                        'day' => date('d', strtotime($date)),
                                        'sales' => 0,
                                        'amount' => 0,
                                        'tax' => 0,
                                        'coupon' => 0,
                                    );
                                }
                                
                                $sql = '
                                SELECT COUNT(id) AS sales, SUM(rate_amount) AS amount, SUM(tax) AS tax, SUM(coupon) AS coupon, DAY(created) as created
                                  FROM `' . Membership::pTable . '`
                                  WHERE MONTH(created) = MONTH(CURDATE())
                                  AND YEAR(created) = YEAR(CURDATE())
                                  AND status = ?
                                  GROUP BY DAY(created);
                                ';
                                $query = $this->db->rawQuery($sql, array(1));
                                
                                foreach ($query->run() as $result) {
                                    $reg_data[$result->created] = array(
                                        'month' => $result->created,
                                        'sales' => $result->sales,
                                        'amount' => $result->amount,
                                        'tax' => $result->tax,
                                        'coupon' => $result->coupon
                                    );
                                }
                                break;
                            
                            case 'year':
                                for ($i = 1; $i <= 12; $i++) {
                                    $data['data'][$i]['m'] = Date::doDate('MMM', date('F', mktime(0, 0, 0, $i, 10)));
                                    $reg_data[$i] = array(
                                        'month' => date('M', mktime(0, 0, 0, $i)),
                                        'sales' => 0,
                                        'amount' => 0,
                                        'tax' => 0,
                                        'coupon' => 0,
                                    );
                                }
                                
                                $sql = '
                                SELECT COUNT(id) AS sales, SUM(rate_amount) AS amount, SUM(tax) AS tax, SUM(coupon) AS coupon, MONTH(created) as created
                                  FROM `' . Membership::pTable . '`
                                  WHERE YEAR(created) = YEAR(NOW())
                                  AND status = ?
                                  GROUP BY MONTH(created);
                                ';
                                $query = $this->db->rawQuery($sql, array(1));
                                
                                foreach ($query->run() as $result) {
                                    $reg_data[$result->created] = array(
                                        'month' => Date::doDate('MMM', date('F', mktime(0, 0, 0, $result->created, 10))),
                                        'sales' => $result->sales,
                                        'amount' => $result->amount,
                                        'tax' => $result->tax,
                                        'coupon' => $result->coupon
                                    );
                                }
                                break;
                            
                            case 'all':
                                for ($i = 1; $i <= 12; $i++) {
                                    $data['data'][$i]['m'] = Date::doDate('MMM', date('F', mktime(0, 0, 0, $i, 10)));
                                    $reg_data[$i] = array(
                                        'month' => date('M', mktime(0, 0, 0, $i)),
                                        'sales' => 0,
                                        'amount' => 0,
                                        'tax' => 0,
                                        'coupon' => 0,
                                    );
                                }
                                
                                $sql = '
                                SELECT COUNT(id) AS sales, SUM(rate_amount) AS amount, SUM(tax) AS tax, SUM(coupon) AS coupon, MONTH(created) as created
                                  FROM `' . Membership::pTable . '`
                                  WHERE status = ?
                                  GROUP BY MONTH(created);
                                ';
                                $query = $this->db->rawQuery($sql, array(1));
                                
                                foreach ($query->run() as $result) {
                                    $reg_data[$result->created] = array(
                                        'month' => Date::doDate('MMM', date('F', mktime(0, 0, 0, $result->created, 10))),
                                        'sales' => $result->sales,
                                        'amount' => $result->amount,
                                        'tax' => $result->tax,
                                        'coupon' => $result->coupon
                                    );
                                }
                                break;
                            
                        }
                        
                        foreach ($reg_data as $key => $value) {
                            $data['data'][$key][Language::$word->TRX_SALES] = $value['sales'];
                            $data['data'][$key][Language::$word->TRX_AMOUNT] = $value['amount'];
                            $data['data'][$key][Language::$word->TRX_TAX] = $value['tax'];
                            $data['data'][$key][Language::$word->TRX_COUPON] = $value['coupon'];
                        }
                        
                        foreach ($labels as $k => $label) {
                            $data['label'][] = $label;
                            $data['color'][] = $color[$k];
                            $data['legend'][] = '<div class="item"><span class="wojo right ring label spaced" style="background:' . $color[$k] . '"> </span> ' . $label . '</div>';
                        }
                        $data['data'] = array_values($data['data']);
                        print json_encode($data);
                        break;
                    
                    // export transactions
                    case 'export':
                        $lg = Language::$lang;
                        $from = isset($_GET['fromdate_submit']) ? Validator::sanitize($_GET['fromdate_submit'], 'string', 10) : null;
                        $end = isset($_GET['enddate_submit']) ? Validator::sanitize($_GET['enddate_submit'], 'string', 10) : null;
                        
                        $enddate = (isset($end) && $end) <> '' ? Validator::sanitize($this->db->toDate($end, false)) : date('Y-m-d');
                        $fromdate = isset($from) ? Validator::sanitize($this->db->toDate($from, false)) : null;
                        
                        if (isset($fromdate) && $enddate <> '') {
                            $where = "WHERE p.created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'";
                        } else {
                            $where = null;
                        }
                        
                        $sql = "
                        SELECT p.txn_id, m.title$lg as title, CONCAT(u.fname,' ',u.lname) as name, p.rate_amount, p.tax, p.coupon, p.total, p.currency, p.pp, p.created
                          FROM `" . Membership::pTable . '` AS p
                          LEFT JOIN `' . User::mTable . '` AS u ON u.id = p.user_id
                          LEFT JOIN `' . Membership::mTable . "` AS m ON m.id = p.membership_id
                          $where
                          ORDER BY p.created DESC;
                        ";
                        
                        $rows = $this->db->rawQuery($sql)->run('array');
                        
                        header('Pragma: no-cache');
                        header('Content-Type: text/csv; charset=utf-8');
                        header('Content-Disposition: attachment; filename=AllPayments.csv');
                        
                        $data = fopen('php://output', 'w');
                        fputcsv($data, [
                            'TXN ID',
                            'Item',
                            'User',
                            'Amount',
                            'TAX/VAT',
                            'Coupon',
                            'Total Amount',
                            'Currency',
                            'Processor',
                            'Created',
                        ]);
                        
                        if ($rows) {
                            foreach ($rows as $row) {
                                fputcsv($data, $row);
                            }
                        }
                        break;
                    
                    default:
                        Url::invalidMethod();
                        break;
                    
                }
            } else {
                Url::invalidMethod();
            }
        }
    }