<?php
    /**
     * CustomFieldController CLass
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: CustomFieldController.php, v1.00 5/9/2023 12:39 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Core\Module;
    use Wojo\Core\User;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Module\Digishop\Digishop;
    use Wojo\Module\Portfolio\Portfolio;
    use Wojo\Module\Shop\Shop;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class CustomFieldController extends Controller
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
            $this->view->data = $this->db->select(Content::cfTable)->orderBy('sorting', 'ASC')->run();
            
            $this->view->crumbs = ['admin', Language::$word->META_T15];
            $this->view->caption = Language::$word->CF_TITLE;
            $this->view->title = Language::$word->META_T15;
            $this->view->subtitle = Language::$word->CF_INFO;
            $this->view->render('field', 'view/admin/');
        }
        
        /**
         * new
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function new(): void
        {
            $this->view->modlist = Module::moduleFieldList();
            $this->view->langlist = $this->core->langlist;
            
            $this->view->crumbs = ['admin', 'fields', 'new'];
            $this->view->caption = Language::$word->META_T17;
            $this->view->title = Language::$word->META_T17;
            $this->view->subtitle = [];
            $this->view->render('field', 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'fields', 'edit'];
            $this->view->title = Language::$word->META_T16;
            $this->view->caption = Language::$word->META_T16;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Content::cfTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->langlist = $this->core->langlist;
                $this->view->modlist = Module::moduleFieldList();
                $this->view->render('field', 'view/admin/');
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
            $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
            if ($postAction) {
                if (IS_AJAX) {
                    switch ($postAction) {
                        case 'add':
                        case 'update':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->process();
                            break;
                        
                        case 'sort':
                            IS_DEMO ? print '0' : $this->sort();
                            break;
                        
                        case 'delete':
                            IS_DEMO ? Message::msgReply(true, 'success', Language::$word->PROCESS_ERR_DEMO) : $this->_delete($title);
                            break;
                        
                        default:
                            Url::invalidMethod();
                            break;
                    }
                } else {
                    Url::invalidMethod();
                }
            } else {
                Url::invalidMethod();
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
                    ->set('tooltip_' . $lang->abbr, Language::$word->CF_TIP . ' <span class="flag icon ' . $lang->abbr . '"></span>')->string();
            }
            
            $validate
                ->set('required', Language::$word->CF_REQUIRED)->required()->numeric()
                ->set('active', Language::$word->PUBLISHED)->required()->numeric()
                ->set('section', Language::$word->SECTION)->required()->string();
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data_m = array();
                foreach ($this->core->langlist as $lang) {
                    $data_m['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
                    $data_m['tooltip_' . $lang->abbr] = $safe->{'tooltip_' . $lang->abbr};
                }
                
                $data_x = array(
                    'section' => $safe->section,
                    'required' => $safe->required,
                    'active' => $safe->active,
                );
                
                if (!Filter::$id) {
                    $data_m['name'] = Utility::randomString(6);
                }
                
                $data = array_merge($data_m, $data_x);
                $last_id = 0;
                (Filter::$id) ? $this->db->update(Content::cfTable, $data)->where('id', Filter::$id, '=')->run() : $last_id = $this->db->insert(Content::cfTable, $data)->run();
                
                if (!Filter::$id) {
                    switch ($safe->section) {
                        case 'digishop':
                            $dataDigishop = array();
                            if ($this->db->exist('mod_digishop')->run()) {
                                $digishop = $this->db->select(Digishop::mTable)->run();
                                foreach ($digishop as $row) {
                                    $dataDigishop[] = array(
                                        'digishop_id' => $row->id,
                                        'field_id' => $last_id,
                                        'section' => 'digishop',
                                        'field_name' => $data_m['name'],
                                    );
                                }
                                $this->db->batch(Content::cfdTable, $dataDigishop)->run();
                            }
                            break;
                        
                        case 'shop':
                            if ($this->db->exist('mod_shop')->run()) {
                                $dataShop = array();
                                $shop = $this->db->select(Shop::mTable)->run();
                                foreach ($shop as $row) {
                                    $dataShop[] = array(
                                        'shop_id' => $row->id,
                                        'field_id' => $last_id,
                                        'section' => 'shop',
                                        'field_name' => $data_m['name'],
                                    );
                                }
                                $this->db->batch(Content::cfdTable, $dataShop)->run();
                            }
                            break;
                        
                        case 'portfolio':
                            if ($this->db->exist('mod_portfolio')->run()) {
                                $portfolio = $this->db->select(Portfolio::mTable)->run();
                                $dataPortfolio = array();
                                foreach ($portfolio as $row) {
                                    $dataPortfolio[] = array(
                                        'portfolio_id' => $row->id,
                                        'field_id' => $last_id,
                                        'section' => 'portfolio',
                                        'field_name' => $data_m['name'],
                                    );
                                }
                                $this->db->batch(Content::cfdTable, $dataPortfolio)->run();
                            }
                            break;
                        
                        case 'profile':
                            $users = $this->db->select(User::mTable)->run();
                            $dataUsers = array();
                            foreach ($users as $row) {
                                $dataUsers[] = array(
                                    'user_id' => $row->id,
                                    'field_id' => $last_id,
                                    'section' => $safe->section,
                                    'field_name' => $data_m['name'],
                                );
                            }
                            $this->db->batch(Content::cfdTable, $dataUsers)->run();
                            break;
                    }
                }
                
                $message = Filter::$id ?
                    Message::formatSuccessMessage($data_m['title' . Language::$lang], Language::$word->CF_UPDATE_OK) :
                    Message::formatSuccessMessage($data_m['title' . Language::$lang], Language::$word->CF_ADDED_OK);
                
                Message::msgReply($this->db->affected(), 'success', $message);
                Logger::writeLog($message);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * sort
         *
         * @return void
         */
        private function sort(): void
        {
            if (isset($_POST['sorting']) and is_array($_POST['sorting'])) {
                $i = 0;
                $query = 'UPDATE `' . Content::cfTable . '` SET `sorting` = CASE ';
                $list = '';
                foreach ($_POST['sorting'] as $item) {
                    $i++;
                    $query .= ' WHEN id = ' . $item . ' THEN ' . $i . ' ';
                    $list .= $item . ',';
                }
                $list = substr($list, 0, -1);
                $query .= 'END WHERE id IN (' . $list . ')';
                $this->db->rawQuery($query)->run();
            }
        }
        
        /**
         * _delete
         *
         * @param string $title
         * @return void
         */
        private function _delete(string $title): void
        {
            
            if ($this->db->delete(Content::cfTable)->where('id', Filter::$id, '=')->run()) {
                $this->db->delete(Content::cfdTable)->where('field_id', Filter::$id, '=')->run();
                $json['type'] = 'success';
            }
            $json['title'] = Language::$word->SUCCESS;
            $json['message'] = str_replace('[NAME]', $title, Language::$word->CF_DEL_OK);
            
            print json_encode($json);
        }
    }