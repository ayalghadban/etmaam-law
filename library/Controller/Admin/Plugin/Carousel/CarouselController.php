<?php
    /**
     * CarouselController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: CarouselController.php, v1.00 5/18/2023 3:57 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin\Plugin\Carousel;
    
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Core\Plugin;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Plugin\Carousel\Carousel;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class CarouselController extends Controller
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
            $this->view->data = Carousel::getAllPlayers();
            
            $this->view->crumbs = ['admin', 'plugins', Language::$word->_PLG_CRL_TITLE];
            $this->view->caption = Language::$word->_PLG_CRL_TITLE;
            $this->view->title = Language::$word->_PLG_CRL_TITLE;
            $this->view->subtitle = Language::$word->_PLG_CRL_SUB1;
            $this->view->render('index', 'view/admin/plugins/carousel/view/', true, 'view/admin/');
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
            
            $this->view->crumbs = ['admin', 'plugins', 'carousel', 'new'];
            $this->view->caption = Language::$word->_PLG_CRL_SUB2;
            $this->view->title = Language::$word->_PLG_CRL_SUB2;
            $this->view->subtitle = null;
            $this->view->render('index', 'view/admin/plugins/carousel/view/', true, 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'plugins', 'carousel', 'edit'];
            $this->view->title = Language::$word->_PLG_CRL_TITLE2;
            $this->view->caption = Language::$word->_PLG_CRL_TITLE2;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Carousel::mTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->langlist = $this->core->langlist;
                $this->view->settings = Utility::jSonToArray($row->settings);
                
                $this->view->render('index', 'view/admin/plugins/carousel/view/', true, 'view/admin/');
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
                    ->set('body_' . $lang->abbr, Language::$word->BGIMG)->text('advanced');
            }
            
            $validate
                ->set('dots', Language::$word->_PLG_CRL_SUB11)->required()->numeric()
                ->set('nav', Language::$word->_PLG_CRL_SUB12)->required()->numeric()
                ->set('autoplay', Language::$word->_PLG_CRL_SUB7)->required()->numeric()
                ->set('margin', Language::$word->_PLG_CRL_SUB8)->required()->numeric()
                ->set('center', Language::$word->_PLG_CRL_SUB9)->required()->numeric()
                ->set('loop', Language::$word->_PLG_CRL_SUB13)->required()->numeric()
                ->set('large', Language::$word->_PLG_CRL_SUB14)->required()->numeric()
                ->set('medium', Language::$word->_PLG_CRL_SUB14)->required()->numeric()
                ->set('small', Language::$word->_PLG_CRL_SUB14)->required()->numeric();
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data_m = array();
                foreach ($this->core->langlist as $lang) {
                    $data_m['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
                    $data_m['body_' . $lang->abbr] = Url::in_url($safe->{'body_' . $lang->abbr});
                }
                
                $data_x = array(
                    'dots' => $safe->dots,
                    'nav' => $safe->nav,
                    'autoplay' => $safe->autoplay,
                    'margin' => $safe->margin,
                    'autoloop' => $safe->loop,
                    'center' => $safe->center,
                    'settings' => json_encode(array(
                        'rtl' => false,
                        'dots' => (bool) $safe->dots,
                        'nav' => (bool) $safe->nav,
                        'autoplay' => (bool) $safe->autoplay,
                        'margin' => (int) $safe->margin,
                        'loop' => (bool) $safe->loop,
                        'center' => (bool) $safe->center,
                        'responsive' => array(
                            0 => array('items' => (int) $safe->small),
                            769 => array('items' => (int) $safe->medium),
                            1024 => array('items' => (int) $safe->large)
                        )
                    ))
                );
                $last_id = 0;
                $data = array_merge($data_m, $data_x);
                (Filter::$id) ? $this->db->update(Carousel::mTable, $data)->where('id', Filter::$id, '=')->run() : $last_id = $this->db->insert(Carousel::mTable, $data)->run();
                
                $message = Filter::$id ?
                    Message::formatSuccessMessage($data_m['title' . Language::$lang], Language::$word->_PLG_CRL_UPDATE_OK) :
                    Message::formatSuccessMessage($data_m['title' . Language::$lang], Language::$word->_PLG_CRL_ADDED_OK);
                
                Message::msgReply($this->db->affected(), 'success', $message);
                Logger::writeLog($message);
                
                if (!Filter::$id) {
                    // Insert new multi plugin
                    $plugin_id = 'carousel/' . Utility::randomString();
                    File::makeDirectory(FPLUGPATH . $plugin_id);
                    File::copyFile(FPLUGPATH . 'carousel/master.php', FPLUGPATH . $plugin_id . '/index.tpl.php');
                    $data_pm = array();
                    
                    $pid = $this->db->select(Plugin::mTable, array('id'))->where('plugalias', 'carousel', '=')->first()->run();
                    foreach ($this->core->langlist as $lang) {
                        $data_pm['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
                    }
                    $data_p = array(
                        'parent_id' => $pid->id,
                        'plugin_id' => $last_id,
                        'groups' => 'carousel',
                        'icon' => 'carousel/thumb.svg',
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
            
            $res = $this->db->delete(Carousel::mTable)->where('id', Filter::$id, '=')->run();
            if ($row = $this->db->select(Plugin::mTable, array('id', 'plugalias'))->where('plugin_id', Filter::$id, '=')->where('groups', 'carousel', '=')->first()->run()) {
                $this->db->delete(Content::lTable)->where('plug_id', $row->id, '=')->run();
                $this->db->delete(Plugin::mTable)->where('id', $row->id, '=')->run();
                
                File::deleteDirectory(FPLUGPATH . $row->plugalias);
            }
            
            $message = str_replace('[NAME]', $title, Language::$word->_PLG_CRL_DEL_OK);
            Message::msgReply($res, 'success', $message);
            Logger::writeLog($message);
        }
    }