<?php
    
    /**
     * BackgroundController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2024
     * @version 6.20: BackgroundController.php, v1.00 1/19/2024 8:35 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin\Plugin\Background;
    
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Core\Plugin;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Plugin\Background\Background;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class BackgroundController extends Controller
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
            $this->view->data = Background::getAllPlayers();
            
            $this->view->crumbs = ['admin', 'plugins', 'background'];
            $this->view->caption = Language::$word->_PLG_VBG_TITLE;
            $this->view->title = Language::$word->_PLG_VBG_TITLE;
            $this->view->subtitle = Language::$word->_PLG_VBG_SUB1;
            $this->view->render('index', 'view/admin/plugins/background/view/', true, 'view/admin/');
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
            
            $this->view->crumbs = ['admin', 'plugins', 'background', 'new'];
            $this->view->caption = Language::$word->_PLG_VBG_SUB2;
            $this->view->title = Language::$word->_PLG_VBG_SUB2;
            $this->view->subtitle = null;
            $this->view->render('index', 'view/admin/plugins/background/view/', true, 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'plugins', 'background', 'edit'];
            $this->view->title = Language::$word->_PLG_VBG_TITLE2;
            $this->view->caption = Language::$word->_PLG_VBG_TITLE2;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Background::mTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->langlist = $this->core->langlist;
                
                $this->view->render('index', 'view/admin/plugins/background/view/', true, 'view/admin/');
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
        private function process(): void
        {
            $validate = Validator::run($_POST);
            
            foreach ($this->core->langlist as $lang) {
                $validate
                    ->set('title_' . $lang->abbr, Language::$word->NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>')->required()->string()->min_len(3)->max_len(80)
                    ->set('header_' . $lang->abbr, Language::$word->_PLG_VBG_HEADER)->string(true, true)
                    ->set('subtext_' . $lang->abbr, Language::$word->_PLG_VBG_TEXT)->string(true, true);
            }
            
            $validate
                ->set('type', Language::$word->_PLG_VBG_TYPE)->required()->string()
                ->set('header_color', Language::$word->COLOR)->color()
                ->set('subtext_color', Language::$word->COLOR)->color();
            
            if ($_POST['type'] == 'youtube') {
                $validate->set('source_youtube', Language::$word->_PLG_VBG_SOURCE)->required()->string();
            } else {
                $validate->set('source_local', Language::$word->_PLG_VBG_SOURCE)->required()->path();
            }
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data_m = array();
                foreach ($this->core->langlist as $lang) {
                    $data_m['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
                    $data_m['header_' . $lang->abbr] = $safe->{'header_' . $lang->abbr};
                    $data_m['subtext_' . $lang->abbr] = $safe->{'subtext_' . $lang->abbr};
                }
                
                $data_x = array(
                    'type' => $safe->type,
                    'header_color' => $safe->header_color,
                    'subtext_color' => $safe->subtext_color,
                    'source' => ($safe->type == 'youtube') ? $safe->source_youtube : $safe->source_local
                );
                $last_id = 0;
                $data = array_merge($data_m, $data_x);
                (Filter::$id) ? $this->db->update(Background::mTable, $data)->where('id', Filter::$id, '=')->run() : $last_id = $this->db->insert(Background::mTable, $data)->run();
                
                $message = Filter::$id ?
                    Message::formatSuccessMessage($data_m['title' . Language::$lang], Language::$word->_PLG_VBG_UPDATE_OK) :
                    Message::formatSuccessMessage($data_m['title' . Language::$lang], Language::$word->_PLG_VBG_ADDED_OK);
                
                Message::msgReply($this->db->affected(), 'success', $message);
                Logger::writeLog($message);
                
                if (!Filter::$id) {
                    // Insert new multi plugin
                    $plugin_id = 'background/' . Utility::randomString();
                    File::makeDirectory(FPLUGPATH . $plugin_id);
                    File::copyFile(FPLUGPATH . 'background/master.php', FPLUGPATH . $plugin_id . '/index.tpl.php');
                    $data_pm = array();
                    
                    $pid = $this->db->select(Plugin::mTable, array('id'))->where('plugalias', 'background', '=')->first()->run();
                    foreach ($this->core->langlist as $lang) {
                        $data_pm['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
                    }
                    $data_p = array(
                        'parent_id' => $pid->id,
                        'plugin_id' => $last_id,
                        'groups' => 'background',
                        'icon' => 'background/thumb.svg',
                        'plugalias' => $plugin_id,
                        'cplugin' => 1,
                        'active' => 1,
                    );
                    $this->db->insert(Plugin::mTable, array_merge($data_pm, $data_p))->run();
                }
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
            
            $res = $this->db->delete(Background::mTable)->where('id', Filter::$id, '=')->run();
            if ($row = $this->db->select(Plugin::mTable, array('id', 'plugalias'))->where('plugin_id', Filter::$id, '=')->where('groups', 'background', '=')->first()->run()) {
                $this->db->delete(Content::lTable)->where('plug_id', $row->id, '=')->run();
                $this->db->delete(Plugin::mTable)->where('id', $row->id, '=')->run();
                
                File::deleteDirectory(FPLUGPATH . $row->plugalias);
            }
            
            $message = str_replace('[NAME]', $title, Language::$word->_PLG_VBG_DEL_OK);
            Message::msgReply($res, 'success', $message);
            Logger::writeLog($message);
        }
    }