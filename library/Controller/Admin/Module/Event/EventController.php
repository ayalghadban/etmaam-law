<?php
    /**
     * EventController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: EventController.php, v1.00 5/21/2023 7:08 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin\Module\Event;
    
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Core\Router;
    use Wojo\Database\Paginator;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Module\Event\Event;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class EventController extends Controller
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
            $end_date = (Validator::get('enddate') && Validator::get('enddate') <> '') ? Validator::sanitize($this->db::toDate(Validator::get('enddate'), false)) : date('Y-m-d');
            $from_date = Validator::get('fromdate') ? Validator::sanitize($this->db::toDate(Validator::get('fromdate'), false)) : null;
            $lg = Language::$lang;
            
            if (isset($_GET['letter']) and (Validator::get('fromdate') && Validator::get('fromdate') <> '')) {
                $letter = Validator::sanitize($_GET['letter'], 'string', 2);
                $counter = $this->db->count(Event::mTable, "WHERE `date_start` BETWEEN '" . trim($from_date) . "' AND '" . trim($end_date) . " 23:59:59' AND `title$lg` REGEXP '^" . $letter . "'")->run();
                $where = "WHERE `date_start` BETWEEN '" . trim($from_date) . "' AND '" . trim($end_date) . " 23:59:59' AND `title$lg` REGEXP '^$letter'";
                
            } elseif (Validator::get('fromdate') && Validator::get('fromdate') <> '') {
                $counter = $this->db->count(Event::mTable, "WHERE `date_start` BETWEEN '" . trim($from_date) . "' AND '" . trim($end_date) . " 23:59:59'")->run();
                $where = "WHERE `date_start` BETWEEN '" . trim($from_date) . "' AND '" . trim($end_date) . " 23:59:59'";
                
            } elseif (isset($_GET['letter'])) {
                $letter = Validator::sanitize($_GET['letter'], 'string', 2);
                $where = "WHERE `title$lg` REGEXP '^$letter'";
                $counter = $this->db->count(Event::mTable, "WHERE `title$lg` REGEXP '^" . $letter . "' LIMIT 1")->run();
            } else {
                $counter = $this->db->count(Event::mTable)->run();
                $where = null;
            }
            
            if (isset($_GET['order']) and count(explode('|', $_GET['order'])) == 2) {
                list($sort, $order) = explode('|', $_GET['order']);
                $sort = Validator::sanitize($sort, 'default', 16);
                $order = Validator::sanitize($order, 'default', 4);
                if (in_array($sort, array('title', 'venue', 'contact', 'ending'))) {
                    $ord = ($order == 'DESC') ? ' DESC' : ' ASC';
                    $sorting = $sort . $ord;
                } else {
                    $sorting = ' date_start DESC';
                }
            } else {
                $sorting = ' date_start DESC';
            }
            
            $pager = Paginator::instance();
            $pager->items_total = $counter;
            $pager->default_ipp = $this->core->perpage;
            $pager->path = Url::url(Router::$path, '?');
            $pager->paginate();
            
            $sql = "
            SELECT id, date_start, date_end as ending, time_start, time_end, contact_person as contact, title$lg as title, venue$lg as venue
              FROM `" . Event::mTable . "`
              $where
              ORDER BY $sorting " . $pager->limit;
            
            $this->view->pager = $pager;
            $this->view->data = $this->db->rawQuery($sql)->run();
            
            $this->view->crumbs = ['admin', 'modules', Language::$word->_MOD_EM_TITLE];
            $this->view->caption = Language::$word->_MOD_EM_TITLE;
            $this->view->title = Language::$word->_MOD_EM_TITLE;
            $this->view->subtitle = null;
            $this->view->render('index', 'view/admin/modules/events/view/', true, 'view/admin/');
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
            
            $this->view->crumbs = ['admin', 'modules', 'gallery', Language::$word->_MOD_EM_SUB];
            $this->view->caption = Language::$word->_MOD_EM_SUB;
            $this->view->title = Language::$word->_MOD_EM_SUB;
            $this->view->subtitle = [];
            $this->view->render('index', 'view/admin/modules/events/view/', true, 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'modules', 'events', Language::$word->_MOD_EM_TITLE1];
            $this->view->title = Language::$word->_MOD_EM_TITLE1;
            $this->view->caption = Language::$word->_MOD_EM_TITLE1;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Event::mTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->langlist = $this->core->langlist;
                
                $this->view->render('index', 'view/admin/modules/events/view/', true, 'view/admin/');
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
                        
                        case 'delete':
                            IS_DEMO ? Message::msgReply(true, 'success', Language::$word->PROCESS_ERR_DEMO) : $this->_delete();
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
        
        /**
         * process
         *
         * @return void
         */
        public function process(): void
        {
            $validate = Validator::run($_POST);
            
            foreach ($this->core->langlist as $lang) {
                $validate
                    ->set('title_' . $lang->abbr, Language::$word->NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>')->required()->string()->min_len(3)->max_len(80)
                    ->set('venue_' . $lang->abbr, Language::$word->_MOD_EM_VENUE . ' <span class="flag icon ' . $lang->abbr . '"></span>')->string()
                    ->set('body_' . $lang->abbr, Language::$word->DESCRIPTION)->text('advanced');
            }
            
            $validate
                ->set('date_start_submit', Language::$word->_MOD_EM_DATE_ST)->required()->date()
                ->set('date_end_submit', Language::$word->_MOD_EM_DATE_E)->required()->date()
                ->set('time_start', Language::$word->_MOD_EM_TIME_ST)->required()->time()
                ->set('time_end', Language::$word->_MOD_EM_TIME_ST)->required()->time()
                ->set('active', Language::$word->PUBLISHED)->required()->numeric()
                ->set('contact_person', Language::$word->_MOD_EM_CONTACT)->required()->string()
                ->set('contact_email', Language::$word->_MOD_EM_EMAIL)->email()
                ->set('contact_phone', Language::$word->_MOD_EM_PHONE)->string()
                ->set('color', Language::$word->_MOD_EM_COLOUR)->color();
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data_m = array();
                foreach ($this->core->langlist as $lang) {
                    $data_m['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
                    $data_m['venue_' . $lang->abbr] = $safe->{'venue_' . $lang->abbr};
                    $data_m['body_' . $lang->abbr] = Url::in_url($safe->{'body_' . $lang->abbr});
                }
                $data_x = array(
                    'date_start' => $this->db::toDate($safe->date_start_submit),
                    'date_end' => $this->db::toDate($safe->date_end_submit),
                    'time_start' => $safe->time_start,
                    'time_end' => $safe->time_end,
                    'contact_person' => $safe->contact_person,
                    'contact_email' => $safe->contact_email,
                    'contact_phone' => $safe->contact_phone,
                    'color' => $safe->color,
                    'active' => $safe->active,
                );
                
                $data = array_merge($data_m, $data_x);
                (Filter::$id) ? $this->db->update(Event::mTable, $data)->where('id', Filter::$id, '=')->run() : $this->db->insert(Event::mTable, $data)->run();
                
                $message = Filter::$id ?
                    Message::formatSuccessMessage($data_m['title' . Language::$lang], Language::$word->_MOD_EM_UPDATE_OK) :
                    Message::formatSuccessMessage($data_m['title' . Language::$lang], Language::$word->_MOD_EM_ADDED_OK);
                
                Message::msgReply($this->db->affected(), 'success', $message);
                Logger::writeLog($message);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * _delete
         *
         * @return void
         */
        private function _delete(): void
        {
            $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
            
            $this->db->delete(Event::mTable)->where('id', Filter::$id, '=')->run();
            
            $message = str_replace('[NAME]', $title, Language::$word->_MOD_EM_DEL_OK);
            Message::msgReply(true, 'success', $message);
            Logger::writeLog($message);
        }
    }