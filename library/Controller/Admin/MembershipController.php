<?php
    /**
     * MembershipController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: MembershipController.php, v1.00 5/10/2023 4:02 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use Wojo\Core\Controller;
    use Wojo\Core\Core;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Core\Membership;
    use Wojo\Core\Router;
    use Wojo\Core\User;
    use Wojo\Database\Paginator;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Stats\Stats;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class MembershipController extends Controller
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
            $lg = Language::$lang;
            $sql = "
			SELECT m.*, m.title$lg as title, m.description$lg as description,
			  (SELECT COUNT(p.membership_id) FROM `" . Membership::pTable . '` as p
			  WHERE p.membership_id = m.id) AS total
			  FROM memberships as m
			';
            $this->view->data = $this->db->rawQuery($sql)->run();
            
            $this->view->crumbs = ['admin', Language::$word->META_T4];
            $this->view->caption = Language::$word->META_T4;
            $this->view->title = Language::$word->META_T4;
            $this->view->subtitle = Language::$word->MEM_SUB;
            $this->view->render('membership', 'view/admin/');
        }
        
        /**
         * new
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function new(): void
        {
            $this->view->langlist = $this->core->langlist;
            
            $this->view->crumbs = ['admin', 'memberships', 'new'];
            $this->view->caption = Language::$word->META_T6;
            $this->view->title = Language::$word->META_T6;
            $this->view->subtitle = [];
            $this->view->render('membership', 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'memberships', 'edit'];
            $this->view->title = Language::$word->META_T5;
            $this->view->caption = Language::$word->META_T5;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Membership::mTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->langlist = $this->core->langlist;
                $this->view->render('membership', 'view/admin/');
            }
        }
        
        /**
         * history
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function history(): void
        {
            $this->view->crumbs = ['admin', 'memberships', 'edit'];
            $this->view->title = Language::$word->META_T7;
            $this->view->caption = Language::$word->META_T7;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Membership::mTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $pager = Paginator::instance();
                $pager->items_total = $this->db->count(Membership::pTable)->where('membership_id', $row->id, '=')->where('status', 1, '=')->run();
                $pager->default_ipp = $this->core->perpage;
                $pager->path = Url::url(Router::$path, '?');
                $pager->paginate();
                
                $sql = "
                SELECT p.rate_amount, p.tax, p.coupon, p.total, p.currency, p.created, p.user_id, CONCAT(u.fname,' ',u.lname) as name
                  FROM `" . Membership::pTable . '` AS p
                  LEFT JOIN ' . User::mTable . ' AS u ON u.id = p.user_id
                  WHERE p.membership_id = ?
                  AND p.status = ?
                  ORDER BY p.created
                  DESC' . $pager->limit;
                
                $this->view->data = $row;
                $this->view->plist = $this->db->rawQuery($sql, array($row->id, 1))->run();
                $this->view->pager = $pager;
                $this->view->langlist = $this->core->langlist;
                
                $this->view->render('membership', 'view/admin/');
            }
        }
        
        /**
         * action
         *
         * @return void
         */
        public function action(): void
        {
            $postAction = Validator::post('action');
            if ($postAction) {
                if (IS_AJAX) {
                    switch ($postAction) {
                        case 'add':
                        case 'update':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->process();
                            break;
                        
                        case 'trash':
                            IS_DEMO ? Message::msgReply(true, 'success', Language::$word->PROCESS_ERR_DEMO) : $this->_trash();
                            break;
                        
                        default:
                            Url::invalidMethod();
                            break;
                    }
                } else {
                    Url::invalidMethod();
                }
            }
            
            $getAction = Validator::get('action');
            if ($getAction) {
                switch ($getAction) {
                    case 'export':
                        $this->export();
                        break;
                    
                    case 'chart':
                        $this->chart();
                        break;
                    
                    default:
                        Url::invalidMethod();
                        break;
                }
            }
        }
        
        /**
         * process
         *
         * @return void
         */
        private function process(): void
        {
            
            $validate = Validator::run($_POST);
            foreach ($this->core->langlist as $lang) {
                $validate
                    ->set('title_' . $lang->abbr, Language::$word->NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>')->required()->string()->min_len(3)->max_len(60)
                    ->set('description_' . $lang->abbr, Language::$word->DESCRIPTION)->string();
            }
            
            $validate
                ->set('price', Language::$word->MEM_PRICE)->required()->float()
                ->set('days', Language::$word->MEM_DAYS)->required()->numeric()
                ->set('period', Language::$word->MEM_DAYS)->required()->string()->exact_len(1)
                ->set('recurring', Language::$word->MEM_REC)->required()->numeric()
                ->set('private', Language::$word->MEM_PRIVATE)->required()->numeric()
                ->set('active', Language::$word->PUBLISHED)->required()->numeric();
            
            $safe = $validate->safe();
            
            $thumb = File::upload('thumb', 3145728, 'png,jpg,jpeg');
            
            if (count(Message::$msgs) === 0) {
                $data_m = array();
                foreach ($this->core->langlist as $lang) {
                    $data_m['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
                    $data_m['description_' . $lang->abbr] = $safe->{'description_' . $lang->abbr};
                }
                $data_x = array(
                    'price' => $safe->price,
                    'days' => $safe->days,
                    'period' => $safe->period,
                    'recurring' => $safe->recurring,
                    'private' => $safe->private,
                    'active' => $safe->active,
                );
                
                if (array_key_exists('thumb', $_FILES)) {
                    $thumbPath = UPLOADS . 'memberships/';
                    $result = File::process($thumb, $thumbPath, 'MEM_');
                    $data['thumb'] = $result['fname'];
                }
                
                $data = array_merge($data_m, $data_x);
                (Filter::$id) ? $this->db->update(Membership::mTable, $data)->where('id', Filter::$id, '=')->run() : $this->db->insert(Membership::mTable, $data)->run();
                
                $message = Filter::$id ?
                    Message::formatSuccessMessage($data_m['title' . Language::$lang], Language::$word->MEM_UPDATE_OK) :
                    Message::formatSuccessMessage($data_m['title' . Language::$lang], Language::$word->MEM_ADDED_OK);
                
                Message::msgReply($this->db->affected(), 'success', $message);
                Logger::writeLog($message);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * export
         *
         * @return void
         */
        private function export(): void
        {
            header('Pragma: no-cache');
            header('Content-Type: text/csv; charset=utf-8');
            header(
                'Content-Disposition: attachment; filename=MembershipPayments.csv'
            );
            
            $data = fopen('php://output', 'w');
            fputcsv($data, array(
                    'TXN ID',
                    Language::$word->NAME,
                    Language::$word->TRX_AMOUNT,
                    Language::$word->TRX_TAX,
                    Language::$word->TRX_COUPON,
                    Language::$word->TRX_TOTAMT,
                    Language::$word->CURRENCY,
                    Language::$word->TRX_PP,
                    Language::$word->CREATED
                )
            );
            $result = Stats::exportMembershipPayments(Filter::$id);
            if ($result) {
                foreach ($result as $row) {
                    fputcsv($data, $row);
                }
                fclose($data);
            }
        }
        
        /**
         * chart
         *
         * @return void
         */
        private function chart(): void
        {
            $data = Stats::getMembershipPaymentsChart(Filter::$id);
            print json_encode($data);
        }
        
        /**
         * _trash
         *
         * @return void
         */
        private function _trash(): void
        {
            $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
            
            if ($row = $this->db->select(Membership::mTable)->where('id', Filter::$id, '=')->first()->run()) {
                $data = array(
                    'type' => 'membership',
                    'parent_id' => Filter::$id,
                    'dataset' => json_encode($row)
                );
                $this->db->insert(Core::txTable, $data)->run();
                $this->db->delete(Membership::mTable)->where('id', $row->id, '=')->run();
            }
            
            $json['type'] = 'success';
            $json['title'] = Language::$word->SUCCESS;
            $json['message'] = str_replace('[NAME]', $title, Language::$word->MEM_TRASH_OK);
            print json_encode($json);
            Logger::writeLog($json['message']);
        }
    }