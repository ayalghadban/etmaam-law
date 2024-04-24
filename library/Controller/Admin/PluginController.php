<?php
    /**
     * PluginController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: PluginController.php, v1.00 5/12/2023 7:10 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Core;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Core\Plugin;
    use Wojo\Core\Router;
    use Wojo\Database\Paginator;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class PluginController extends Controller
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
            if (Validator::get('letter')) {
                $letter = Validator::sanitize($_GET['letter'], 'default', 2);
                $and = "AND `title$lg` REGEXP '^" . $letter . "'";
                $counter = $this->db->count(Plugin::mTable, "WHERE parent_id = 0 $and")->run();
            } else {
                $counter = $this->db->count(Plugin::mTable)->where('parent_id', 1, '<')->run();
                $and = null;
            }
            
            $pager = Paginator::instance();
            $pager->items_total = $counter;
            $pager->default_ipp = $this->core->perpage;
            $pager->path = Url::url(Router::$path, '?');
            $pager->paginate();
            
            $sql = 'SELECT * FROM `' . Plugin::mTable . "` WHERE parent_id = 0 $and ORDER BY hasconfig DESC, title" . $lg . $pager->limit;
            $this->view->data = $this->db->rawQuery($sql)->run();
            $this->view->pager = $pager;
            
            $this->view->crumbs = ['admin', Language::$word->PLG_TITLE];
            $this->view->caption = Language::$word->PLG_TITLE;
            $this->view->title = Language::$word->PLG_TITLE;
            $this->view->subtitle = Language::$word->PLG_SUB;
            $this->view->render('plugin', 'view/admin/');
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
            
            $this->view->crumbs = ['admin', 'plugins', 'new'];
            $this->view->caption = Language::$word->META_T29;
            $this->view->title = Language::$word->META_T29;
            $this->view->subtitle = [];
            $this->view->render('plugin', 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'plugins', 'edit'];
            $this->view->title = Language::$word->META_T28;
            $this->view->caption = Language::$word->META_T28;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Plugin::mTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->langlist = $this->core->langlist;
                $this->view->render('plugin', 'view/admin/');
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
                    $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
                    switch ($postAction) {
                        case 'add':
                        case 'update':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->process();
                            break;
                        
                        case 'trash':
                            IS_DEMO ? Message::msgReply(true, 'success', Language::$word->PROCESS_ERR_DEMO) : $this->_trash($title);
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
                    ->set('title_' . $lang->abbr, Language::$word->NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>')->required()->string()->min_len(3)->max_len(80)
                    ->set('info_' . $lang->abbr, Language::$word->DESCRIPTION)->string(true, true)
                    ->set('body_' . $lang->abbr, Language::$word->CONTENT)->text();
            }
            
            $validate
                //->set("jscode", Lang::$word->PAG_JSCODE)->text("script")
                ->set('alt_class', Language::$word->PLG_CLASS)->string()
                ->set('show_title', Language::$word->PLG_SHOWTITLE)->required()->numeric()
                ->set('active', Language::$word->ACTIVE)->required()->numeric();
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data_m = array();
                foreach ($this->core->langlist as $lang) {
                    $data_m['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
                    $data_m['info_' . $lang->abbr] = $safe->{'info_' . $lang->abbr};
                    $data_m['body_' . $lang->abbr] = Url::in_url($safe->{'body_' . $lang->abbr});
                }
                
                $data_x = array(
                    'show_title' => $safe->show_title,
                    'alt_class' => $safe->alt_class,
                    //'jscode' => json_encode($safe->jscode),
                    'active' => $safe->active,
                );
                
                $data = array_merge($data_m, $data_x);
                (Filter::$id) ? $this->db->update(Plugin::mTable, $data)->where('id', Filter::$id, '=')->run() : $this->db->insert(Plugin::mTable, $data)->run();
                
                $message = Filter::$id ?
                    Message::formatSuccessMessage($data_m['title' . Language::$lang], Language::$word->PLG_UPDATE_OK) :
                    Message::formatSuccessMessage($data_m['title' . Language::$lang], Language::$word->PLG_ADDED_OK);
                
                Message::msgReply($this->db->affected(), 'success', $message);
                Logger::writeLog($message);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * _trash
         *
         * @param string $title
         * @return void
         */
        private function _trash(string $title): void
        {
            if ($row = $this->db->select(Plugin::mTable)->where('id', Filter::$id, '=')->first()->run()) {
                $data = array(
                    'type' => 'plugin',
                    'parent_id' => Filter::$id,
                    'dataset' => json_encode($row)
                );
                $this->db->insert(Core::txTable, $data)->run();
                $this->db->delete(Plugin::mTable)->where('id', $row->id, '=')->run();
                $this->db->delete(Content::lTable)->where('plug_id', $row->id, '=')->run();
            }
            
            $json['type'] = 'success';
            $json['title'] = Language::$word->SUCCESS;
            $json['message'] = str_replace('[NAME]', $title, Language::$word->PLG_TRASH_OK);
            print json_encode($json);
            Logger::writeLog($json['message']);
        }
        
        /**
         * _delete
         *
         * @param string $title
         * @return void
         */
        private function _delete(string $title): void
        {
            if ($this->db->delete(Plugin::mTable)->where('id', Filter::$id, '=')->run()) {
                $json['type'] = 'success';
            }
            
            $json['title'] = Language::$word->SUCCESS;
            $json['message'] = str_replace('[NAME]', $title, Language::$word->PLG_DEL_OK);
            print json_encode($json);
        }
    }