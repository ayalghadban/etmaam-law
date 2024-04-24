<?php
    /**
     * MenuController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: MenuController.php, v1.00 5/5/2023 3:16 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Core;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Core\Module;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class MenuController extends Controller
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
            $this->view->contenttype = Content::getContentType();
            $this->view->langlist = $this->core->langlist;
            $this->view->tree = Content::menuTree();
            $this->view->droplist = Content::getMenuDropList($this->view->tree, 0, 0, '&#166;&nbsp;&nbsp;&nbsp;&nbsp;');
            $this->view->sortlist = Content::getSortMenuList($this->view->tree);
            
            $this->view->crumbs = ['admin', Language::$word->ADM_MENUS];
            $this->view->caption = Language::$word->ADM_MENUS;
            $this->view->title = Language::$word->META_T12;
            $this->view->subtitle = Language::$word->META_T12;
            $this->view->render('menu', 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            
            $this->view->crumbs = ['admin', 'menus', 'edit'];
            $this->view->title = Language::$word->MEN_SUB4;
            $this->view->caption = Language::$word->MEN_SUB4;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Content::mTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                
                $this->view->contenttype = Content::getContentType();
                $this->view->langlist = $this->core->langlist;
                $this->view->tree = Content::menuTree();
                $this->view->droplist = Content::getMenuDropList($this->view->tree, 0, 0, '&#166;&nbsp;&nbsp;&nbsp;&nbsp;', $row->parent_id);
                $this->view->sortlist = Content::getSortMenuList($this->view->tree);
                
                $this->view->pagelist = Content::getPageList();
                $this->view->modulelist = Module::getModuleList(false);
                $this->view->render('menu', 'view/admin/');
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
                        
                        case 'sort':
                            IS_DEMO ? print '0' : $this->sort();
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
                    ->set('name_' . $lang->abbr, Language::$word->MEN_NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>')->required()->string()->min_len(3)->max_len(80)
                    ->set('caption_' . $lang->abbr, Language::$word->PAG_CAPTION)->string();
            }
            
            $validate
                ->set('content_type', Language::$word->MEN_CTYPE)->required()->string()->min_len(3)->max_len(8)
                ->set('active', Language::$word->PUBLISHED)->required()->numeric()
                ->set('cols', Language::$word->MEN_COLS)->required()->numeric()
                ->set('parent_id', Language::$word->MEN_PARENT)->numeric()
                ->set('icon', Language::$word->MEN_COLS)->string();
            
            
            if ($_POST['content_type'] == 'page' and strlen($_POST['page_id']) === 0) {
                Message::$msgs['page_id'] = Language::$word->MEN_SUB2;
            }
            if ($_POST['content_type'] == 'module' and strlen($_POST['mod_id']) === 0) {
                Message::$msgs['mod_id'] = Language::$word->MEN_SUB2;
            }
            
            $safe = $validate->safe();
            if (count(Message::$msgs) === 0) {
                $data_m = array();
                if ($_POST['content_type'] == 'page') {
                    $slug = $this->db->select(Content::pTable, Utility::getLangSlugs('slug_'))->where('id', intval($_POST['page_id']), '=')->run();
                } elseif ($_POST['content_type'] == 'module') {
                    $slug = $this->db->select(Module::mTable)->where('modalias', intval($_POST['mod_id']), '=')->one()->run();
                } else {
                    $slug = 'NULL';
                }
                foreach ($this->core->langlist as $lang) {
                    $data_m['name_' . $lang->abbr] = $safe->{'name_' . $lang->abbr};
                    $data_m['caption_' . $lang->abbr] = $safe->{'caption_' . $lang->abbr};
                    if (isset($_POST['page_id'])) {
                        $data_m['page_slug_' . $lang->abbr] = $slug[0]->{'slug_' . $lang->abbr};
                    }
                }
                
                $data_x = array(
                    'mod_id' => (isset($_POST['mod_id'])) ? intval($_POST['mod_id']) : 0,
                    'mod_slug' => isset($_POST['mod_id']) ? $slug : 'NULL',
                    'page_id' => (isset($_POST['page_id'])) ? intval($_POST['page_id']) : 0,
                    'content_type' => $safe->content_type,
                    'parent_id' => $safe->parent_id,
                    'cols' => $safe->cols,
                    'icon' => $safe->icon,
                    'link' => (isset($_POST['web'])) ? Validator::sanitize($_POST['web']) : 'NULL',
                    'target' => (strlen($_POST['target']) === 0) ? '_blank' : Validator::sanitize($_POST['target'], 'db'),
                    'active' => $safe->active,
                );
                
                $data = array_merge($data_m, $data_x);
                $last_id = 0;
                
                (Filter::$id) ? $this->db->update(Content::mTable, $data)->where('id', Filter::$id, '=')->run() : $last_id = $this->db->insert(Content::mTable, $data)->run();
                
                if (Filter::$id) {
                    $message = Message::formatSuccessMessage($data_m['name' . Language::$lang], Language::$word->MEN_UPDATE_OK);
                    Message::msgReply($this->db->affected(), 'success', $message);
                    Logger::writeLog($message);
                } else {
                    if ($last_id) {
                        $message = Message::formatSuccessMessage($data_m['name' . Language::$lang], Language::$word->MEN_ADDED_OK);
                        $json['type'] = 'success';
                        $json['title'] = Language::$word->SUCCESS;
                        $json['message'] = $message;
                        $json['redirect'] = Url::url('admin/menus');
                        Logger::writeLog($message);
                    } else {
                        $json['type'] = 'alert';
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
         * @return void
         */
        private function sort(): void
        {
            if (isset($_POST['sorting'])) {
                $i = 0;
                $query = 'UPDATE `' . Content::mTable . '` SET `sorting` = CASE ';
                $list = '';
                foreach ($_POST['sorting'] as $item) {
                    $i++;
                    $query .= ' WHEN id = ' . $item . ' THEN ' . $i . ' ';
                    $list .= $item . ',';
                }
                $list = substr($list, 0, -1);
                $query .=
                    'END WHERE id IN (' . $list . ')';
                $this->db->rawQuery($query)->run();
            }
        }
        
        /**
         * _trash
         *
         * @return void
         */
        private function _trash(): void
        {
            $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
            
            if ($row = $this->db->select(Content::mTable)->where('id', Filter::$id, '=')->first()->run()) {
                $data = array(
                    'type' => 'menu',
                    'parent_id' => Filter::$id,
                    'dataset' => json_encode($row)
                );
                $this->db->insert(Core::txTable, $data)->run();
                $this->db->delete(Content::mTable)->where('id', $row->id, '=')->run();
                if ($result = $this->db->select(Content::mTable)->where('parent_id', $row->id, '=')->run()) {
                    $dataBatch = array();
                    foreach ($result as $item) {
                        $dataBatch[] = array(
                            'parent' => 'menu',
                            'type' => 'submenu',
                            'parent_id' => $row->id,
                            'dataset' => json_encode($item),
                        );
                    }
                    $this->db->batch(Core::txTable, $dataBatch)->run();
                    $this->db->delete(Content::mTable)->where('parent_id', $row->id, '=')->run();
                }
            }
            $json['type'] = 'success';
            $json['title'] = Language::$word->SUCCESS;
            $json['message'] = str_replace('[NAME]', $title, Language::$word->MEN_TRASH_OK);
            print json_encode($json);
            Logger::writeLog($json['message']);
        }
    }