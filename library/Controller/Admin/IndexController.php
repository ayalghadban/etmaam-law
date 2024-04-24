<?php
    /**
     * IndexController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: IndexController.php, v1.00 4/25/2023 1:45 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use DateInterval;
    use DatePeriod;
    use DateTime;
    use Exception;
    use Wojo\Core\Controller;
    use Wojo\Core\Membership;
    use Wojo\Core\User;
    use Wojo\Date\Date;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Language\Language;
    use Wojo\Stats\Stats;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class IndexController extends Controller
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
         * @throws Exception
         */
        public function index(): void
        {
            if (IS_AJAX) {
                $this->action();
            } else {
                $sql = '
                SELECT COUNT(*) AS total,
                  COUNT(CASE WHEN type = ? THEN 1  END) AS users,
                  COUNT(CASE WHEN type = ? AND active = ? THEN 1  END) AS active,
                  COUNT(CASE WHEN type = ? AND active = ? THEN 1  END) AS pending,
                  COUNT(CASE WHEN type = ? AND membership_id >= 1 THEN 1  END) AS memberships
                  FROM `' . User::mTable . '`
                ';
                
                $this->view->data = $this->db->rawQuery($sql, array('member', 'member', 'y', 'member', 't', 'member'))->first()->run();
                $this->view->memberships = Stats::MembershipsExpireMonth();
                
                $this->view->crumbs = [''];
                $this->view->caption = Language::$word->META_T1;
                $this->view->title = Language::$word->META_T1;
                $this->view->subtitle = null;
                $this->view->render('index', 'view/admin/');
            }
        }
        
        /**
         * action
         *
         * @return void
         * @throws Exception
         */
        public function action(): void
        {
            $getAction = Validator::get('action');
            if ($getAction) {
                switch ($getAction) {
                    // index stats
                    case 'index':
                        $data = array();
                        $data['label'] = array();
                        $data['color'] = array();
                        $data['legend'] = array();
                        
                        $color = array(
                            '#03a9f4',
                            '#33BFC1'
                        );
                        
                        $labels = array(
                            Language::$word->TRX_SALES,
                            Language::$word->TRX_AMOUNT
                        );
                        
                        for ($i = 1; $i <= 12; $i++) {
                            $data['data'][$i]['m'] = Date::doDate('MMM', date('F', mktime(0, 0, 0, $i, 10)));
                            $reg_data[$i] = array(
                                'month' => date('M', mktime(0, 0, 0, $i)),
                                'sales' => 0,
                                'amount' => 0,
                            );
                        }
                        
                        $sql = '
                        SELECT COUNT(id) AS sales, SUM(rate_amount) AS amount, MONTH(created) as created
                          FROM `' . Membership::pTable . '`
                          WHERE status = ?
                          GROUP BY MONTH(created);
                        ';
                        
                        $query = $this->db->rawQuery($sql, array(1));
                        foreach ($query->run() as $result) {
                            $reg_data[$result->created] = array(
                                'month' => Date::doDate('MMM', date('F', mktime(0, 0, 0, $result->created, 10))),
                                'sales' => $result->sales,
                                'amount' => $result->amount
                            );
                        }
                        
                        $total_sum = 0;
                        $total_sales = 0;
                        
                        
                        foreach ($reg_data as $key => $value) {
                            $data['sales'][] = array($key, $value['sales']);
                            $data['amount'][] = array($key, $value['amount']);
                            $data['data'][$key][Language::$word->TRX_SALES] = $value['sales'];
                            $data['data'][$key][Language::$word->TRX_AMOUNT] = $value['amount'];
                            $total_sum += $value['amount'];
                            $total_sales += $value['sales'];
                        }
                        
                        $data['totalsum'] = Utility::formatMoney($total_sum);
                        $data['totalsales'] = $total_sales;
                        $data['sales_str'] = implode(',', array_column($data['sales'], 1));
                        $data['amount_str'] = implode(',', array_column($data['amount'], 1));
                        
                        foreach ($labels as $k => $label) {
                            $data['label'][] = $label;
                            $data['color'][] = $color[$k];
                            $data['legend'][] = '<div class="item"><span class="wojo right ring label spaced" style="background:' . $color[$k] . '"> </span> ' . $label . '</div>';
                        }
                        $data['data'] = array_values($data['data']);
                        print json_encode($data);
                        break;
                    // membership stats
                    case 'main':
                        $data = array();
                        $data['label'] = array();
                        $data['color'] = array();
                        $data['legend'] = array();
                        $data['preUnits'] = Utility::currencySymbol();
                        
                        $color = array(
                            '#f44336',
                            '#2196f3',
                            '#e91e63',
                            '#4caf50',
                            '#ff9800',
                            '#ff5722',
                            '#795548',
                            '#607d8b',
                            '#00bcd4',
                            '#9c27b0'
                        );
                        
                        $begin = new DateTime(date('Y') . '-01');
                        $ends = new DateTime(date('Y') . '-12');
                        
                        $end = $ends->modify('+1 month');

                        $interval = new DateInterval('P1M');
                        $date_range = new DatePeriod($begin, $interval, $end);
                        $lg = Language::$lang;
                        
                        $sql = "
                        SELECT DATE_FORMAT(p.created, '%Y-%m') as cdate, m.title$lg AS title, p.membership_id, p.rate_amount
                          FROM `" . Membership::pTable . '` AS p
                          LEFT JOIN `' . Membership::mTable . '` AS m ON m.id = p.membership_id
                          WHERE status = ?;
                        ';
                        $query = $this->db->rawQuery($sql, array(1))->run();
                        $memberships = Utility::groupToLoop($query, 'title');
                        
                        foreach ($date_range as $k => $date) {
                            $data['data'][$k]['m'] = Date::doDate('MMM', $date->format('Y-m'));
                            if ($memberships) {
                                foreach ($memberships as $title => $rows) {
                                    $sum = 0;
                                    foreach ($rows as $row) {
                                        $data['data'][$k][$row->title] = $sum;
                                        if ($row->cdate == $date->format('Y-m')) {
                                            $sum += $row->rate_amount;
                                            $data['data'][$k][$title] = $sum;
                                        }
                                    }
                                    
                                }
                                
                            } else {
                                $data['data'][$k]['-/-'] = 0;
                            }
                        }
                        
                        if ($memberships) {
                            $k = 0;
                            foreach ($memberships as $label => $vals) {
                                $k++;
                                $data['label'][] = $label;
                                $data['color'][] = $color[$k];
                                $data['legend'][] = '<div class="item"><span class="wojo right ring label spaced" style="background:' . $color[$k] . '"> </span> ' . $label . '</div>';
                            }
                        } else {
                            $data['label'][] = '-/-';
                            $data['color'][] = $color[0];
                            $data['legend'][] = '<div class="item"><span class="wojo right ring label spaced" style="background:' . $color[0] . '"> </span> -/-</div>';
                        }
                        
                        print json_encode($data);
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