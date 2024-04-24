<?php
    /**
     * FaqController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: FaqController.php, v1.00 5/21/2023 9:58 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin\Module\Faq;
    
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Module\Faq\Faq;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class FaqController extends Controller
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
            SELECT m.id, m.category_id, m.question$lg as title, c.name$lg as name
              FROM `" . Faq::mTable . '` as m
              LEFT JOIN `' . Faq::cTable . '` as c ON c.id = m.category_id
              ORDER BY c.sorting, m.sorting
            ';
            
            $query = $this->db->rawQuery($sql)->run();
            
            $data = array();
            if ($query) {
                foreach ($query as $i => $row) {
                    if (!array_key_exists($row->name, $data)) {
                        $data[$row->name]['name'] = $row->name;
                        $data[$row->name]['category_id'] = $row->category_id;
                    }
                    
                    $data[$row->name]['items'][$i]['question'] = $row->title;
                    $data[$row->name]['items'][$i]['id'] = $row->id;
                }
            }
            
            $this->view->data = $data;
            
            $this->view->crumbs = ['admin', 'modules', Language::$word->_MOD_FAQ_TITLE];
            $this->view->caption = Language::$word->_MOD_FAQ_TITLE;
            $this->view->title = Language::$word->_MOD_FAQ_TITLE;
            $this->view->subtitle = null;
            $this->view->render('index', 'view/admin/modules/faq/view/', true, 'view/admin/');
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
            $this->view->categories = $this->db->select(Faq::cTable)->orderBy('sorting', 'ASC')->run();
            
            $this->view->crumbs = ['admin', 'modules', 'faq', Language::$word->_MOD_FAQ_TITLE2];
            $this->view->caption = Language::$word->_MOD_FAQ_TITLE2;
            $this->view->title = Language::$word->_MOD_FAQ_TITLE2;
            $this->view->subtitle = [];
            $this->view->render('index', 'view/admin/modules/faq/view/', true, 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'modules', 'faq', Language::$word->_MOD_FAQ_TITLE1];
            $this->view->title = Language::$word->_MOD_FAQ_TITLE1;
            $this->view->caption = Language::$word->_MOD_FAQ_TITLE1;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Faq::mTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->categories = $this->db->select(Faq::cTable)->orderBy('sorting', 'ASC')->run();
                $this->view->langlist = $this->core->langlist;
                
                $this->view->render('index', 'view/admin/modules/faq/view/', true, 'view/admin/');
            }
        }
        
        /**
         * categoryNew
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function categoryNew(): void
        {
            $this->view->langlist = $this->core->langlist;
            $this->view->tree = Faq::categoryTree();
            $this->view->sortlist = Faq::getSortCategoryList($this->view->tree);
            
            $this->view->crumbs = ['admin', 'modules', 'faq', Language::$word->_MOD_FAQ_TITLE2];
            $this->view->caption = Language::$word->_MOD_FAQ_SUB3;
            $this->view->title = Language::$word->_MOD_FAQ_SUB3;
            $this->view->subtitle = [];
            $this->view->render('index', 'view/admin/modules/faq/view/', true, 'view/admin/');
        }
        
        /**
         * categoryEdit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function categoryEdit(): void
        {
            $this->view->crumbs = ['admin', 'modules', 'faq', Language::$word->_MOD_FAQ_SUB3];
            $this->view->title = Language::$word->_MOD_FAQ_SUB3;
            $this->view->caption = Language::$word->_MOD_FAQ_SUB3;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Faq::cTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->langlist = $this->core->langlist;
                $this->view->tree = Faq::categoryTree();
                $this->view->sortlist = Faq::getSortCategoryList($this->view->tree);
                
                $this->view->render('index', 'view/admin/modules/faq/view/', true, 'view/admin/');
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
                        
                        case 'category':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->processCategory();
                            break;
                        
                        case 'sort':
                            IS_DEMO ? print '0' : $this->sort(Validator::post('type'));
                            break;
                        
                        case 'delete':
                            IS_DEMO ? Message::msgReply(true, 'success', Language::$word->PROCESS_ERR_DEMO) : $this->_delete(Validator::post('type'));
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
                    ->set('question_' . $lang->abbr, Language::$word->_MOD_FAQ_QUESTION . ' <span class="flag icon ' . $lang->abbr . '"></span>')->required()->string()->min_len(3)->max_len(100)
                    ->set('answer_' . $lang->abbr, Language::$word->DESCRIPTION . ' <span class="flag icon ' . $lang->abbr . '"></span>')->text('advanced');
            }
            $validate->set('category_id', Language::$word->CATEGORY)->required()->numeric();
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data_m = array();
                foreach ($this->core->langlist as $lang) {
                    $data_m['question_' . $lang->abbr] = $safe->{'question_' . $lang->abbr};
                    $data_m['answer_' . $lang->abbr] = Url::in_url($safe->{'answer_' . $lang->abbr});
                }
                
                $data_x = array(
                    'category_id' => $safe->category_id,
                );
                
                (Filter::$id) ?
                    $this->db->update(Faq::mTable, array_merge($data_m, $data_x))->where('id', Filter::$id, '=')->run() :
                    $this->db->insert(Faq::mTable, array_merge($data_m, $data_x))->run();
                
                $message = Filter::$id ?
                    Message::formatSuccessMessage($data_m['question' . Language::$lang], Language::$word->_MOD_FAQ_UPDATED) :
                    Message::formatSuccessMessage($data_m['question' . Language::$lang], Language::$word->_MOD_FAQ_ADDED);
                
                Message::msgReply($this->db->affected(), 'success', $message);
                Logger::writeLog($message);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * processCategory
         *
         * @return void
         */
        public function processCategory(): void
        {
            $validate = Validator::run($_POST);
            
            foreach ($this->core->langlist as $lang) {
                $validate->set('name_' . $lang->abbr, Language::$word->NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>')->required()->string()->min_len(3)->max_len(80);
            }
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $datam = array();
                foreach ($this->core->langlist as $lang) {
                    $datam['name_' . $lang->abbr] = $safe->{'name_' . $lang->abbr};
                }
                $last_id = 0;
                (Filter::$id) ? $this->db->update(Faq::cTable, $datam)->where('id', Filter::$id, '=')->run() : $last_id = $this->db->insert(Faq::cTable, $datam)->run();
                if (Filter::$id) {
                    $message = Message::formatSuccessMessage($datam['name' . Language::$lang], Language::$word->_MOD_FAQ_CAT_UPDATE_OK);
                    Message::msgReply($this->db->affected(), 'success', $message);
                    Logger::writeLog($message);
                } else {
                    if ($last_id) {
                        $message = Message::formatSuccessMessage($datam['name' . Language::$lang], Language::$word->_MOD_FAQ_CAT_ADDED_OK);
                        $json['type'] = 'success';
                        $json['title'] = Language::$word->SUCCESS;
                        $json['message'] = $message;
                        $json['redirect'] = Url::url('admin/modules/faq/categories');
                        Logger::writeLog($message);
                    } else {
                        $json['type'] = 'success';
                        $json['title'] = Language::$word->ALERT;
                        $json['message'] = Language::$word->NOPROCESS;
                    }
                    print json_encode($json);
                }
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * sort
         *
         * @param string $type
         * @return void
         */
        private function sort(string $type): void
        {
            $i = 0;
            $list = '';
            
            $query = ($type == 'items') ? 'UPDATE `' . Faq::mTable . '` SET `sorting` = CASE ' : 'UPDATE `' . Faq::cTable . '` SET `sorting` = CASE ';
            
            foreach ($_POST['sorting'] as $item) {
                $i++;
                $query .= ' WHEN id = ' . $item . ' THEN ' . $i . ' ';
                $list .= $item . ',';
            }
            
            $list = substr($list, 0, -1);
            $query .= 'END WHERE id IN (' . $list . ')';
            $this->db->rawQuery($query)->run();
        }
        
        /**
         * _delete
         *
         * @param string $type
         * @return void
         */
        private function _delete(string $type): void
        {
            $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
            
            if ($type == 'item') {
                $this->db->delete(Faq::mTable)->where('id', Filter::$id, '=')->run();
                $message = str_replace('[NAME]', $title, Language::$word->_MOD_FAQ_DEL_OK);
            } else {
                $this->db->delete(Faq::cTable)->where('id', Filter::$id, '=')->run();
                $message = str_replace('[NAME]', $title, Language::$word->_MOD_FAQ_CAT_DEL_OK);
            }
            Message::msgReply(true, 'success', $message);
            Logger::writeLog($message);
        }
    }