<?php
    /**
     * RssController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: RssController.php, v1.00 5/18/2023 1:29 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin\Plugin\Rss;
    
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Core\Plugin;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Plugin\Rss\Rss;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class RssController extends Controller
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
            $this->view->data = Rss::getAllRss();
            
            $this->view->crumbs = ['admin', 'plugins', Language::$word->_PLG_RSS_TITLE];
            $this->view->caption = Language::$word->_PLG_RSS_TITLE;
            $this->view->title = Language::$word->_PLG_RSS_TITLE;
            $this->view->subtitle = Language::$word->_PLG_RSS_SUB1;
            $this->view->render('index', 'view/admin/plugins/rss/view/', true, 'view/admin/');
        }
        
        /**
         * new
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function new(): void
        {
            $this->view->crumbs = ['admin', 'plugins', 'rss', 'new'];
            $this->view->caption = Language::$word->_PLG_RSS_TITLE1;
            $this->view->title = Language::$word->_PLG_RSS_TITLE1;
            $this->view->subtitle = null;
            $this->view->render('index', 'view/admin/plugins/rss/view/', true, 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'plugins', 'rss', 'edit'];
            $this->view->title = Language::$word->_PLG_RSS_TITLE2;
            $this->view->caption = Language::$word->_PLG_RSS_TITLE2;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Rss::mTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->render('index', 'view/admin/plugins/rss/view/', true, 'view/admin/');
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
            $validate
                ->set('title', Language::$word->NAME)->required()->string()->min_len(3)->max_len(80)
                ->set('url', Language::$word->_PLG_RSS_URL)->required()->url()
                ->set('items', Language::$word->_PLG_RSS_ITEMS)->required()->numeric()->min_len(1)->max_len(2)
                ->set('show_date', Language::$word->_PLG_RSS_SHOW_DATE)->required()->numeric()
                ->set('show_desc', Language::$word->_PLG_RSS_SHOW_DESC)->required()->numeric()
                ->set('max_words', Language::$word->_PLG_RSS_BODYTRIM)->required()->numeric()->min_len(1)->max_len(3);
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data_m = array();
                $data = array(
                    'title' => $safe->title,
                    'url' => $safe->url,
                    'items' => $safe->items,
                    'show_date' => $safe->show_date,
                    'show_desc' => $safe->show_desc,
                    'max_words' => $safe->max_words,
                );
                
                $last_id = 0;
                (Filter::$id) ? $this->db->update(Rss::mTable, $data)->where('id', Filter::$id, '=')->run() : $last_id = $this->db->insert(Rss::mTable, $data)->run();
                
                $message = Filter::$id ?
                    Message::formatSuccessMessage($data['title'], Language::$word->_PLG_RSS_UPDATE_OK) :
                    Message::formatSuccessMessage($data['title'], Language::$word->_PLG_RSS_ADDED_OK);
                
                Message::msgReply($this->db->affected(), 'success', $message);
                Logger::writeLog($message);
                
                if (!Filter::$id) {
                    // Insert new multi plugin
                    $plugin_id = 'rss/' . Utility::randomString();
                    File::makeDirectory(FPLUGPATH . $plugin_id);
                    File::copyFile(FPLUGPATH . 'rss/master.php', FPLUGPATH . $plugin_id . '/index.tpl.php');
                    
                    $pid = $this->db->select(Plugin::mTable, array('id'))->where('plugalias', 'rss')->first()->run();
                    foreach ($this->core->langlist as $lang) {
                        $data_m['title_' . $lang->abbr] = $safe->title;
                    }
                    $data_x = array(
                        'parent_id' => $pid->id,
                        'plugin_id' => $last_id,
                        'groups' => 'rss',
                        'icon' => 'rss/thumb.svg',
                        'plugalias' => $plugin_id,
                        'active' => 1,
                    );
                    $this->db->insert(Plugin::mTable, array_merge($data_m, $data_x))->run();
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
            
            $this->db->delete(Rss::mTable)->where('id', Filter::$id, '=')->run();
            if ($row = $this->db->select(Plugin::mTable, array('id', 'plugalias'))->where('plugin_id', Filter::$id, '=')->where('groups', 'rss', '=')->first()->run()) {
                $this->db->delete(Content::lTable)->where('plug_id', $row->id, '=')->run();
                $this->db->delete(Plugin::mTable)->where('id', $row->id, '=')->run();
                
                File::deleteDirectory(FPLUGPATH . $row->plugalias);
            }
            
            $message = str_replace('[NAME]', $title, Language::$word->_PLG_RSS_DEL_OK);
            Message::msgReply(true, 'success', $message);
            Logger::writeLog($message);
        }
    }