<?php
    /**
     * AdblockController
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: AdblockController.php, v1.00 5/19/2023 10:10 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin\Module\Adblock;
    
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Core\Plugin;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Exception\NotFoundException;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Module\Adblock\Adblock;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class AdblockController extends Controller
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
            $this->view->data = $this->db->select(Adblock::mTable)->orderBy('title' . Language::$lang, 'ASC')->run();
            
            $this->view->crumbs = ['admin', 'modules', Language::$word->_MOD_AB_TITLE];
            $this->view->caption = Language::$word->_MOD_AB_TITLE;
            $this->view->title = Language::$word->_MOD_AB_TITLE;
            $this->view->subtitle = Language::$word->_MOD_AB_INFO;
            $this->view->render('index', 'view/admin/modules/adblock/view/', true, 'view/admin/');
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
            
            $this->view->crumbs = ['admin', 'modules', 'adblock', 'new'];
            $this->view->caption = Language::$word->_MOD_AB_ADD;
            $this->view->title = Language::$word->_MOD_AB_ADD;
            $this->view->subtitle = [];
            $this->view->render('index', 'view/admin/modules/adblock/view/', true, 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'modules', 'adblock', 'edit'];
            $this->view->title = Language::$word->_MOD_AB_EDIT;
            $this->view->caption = Language::$word->_MOD_AB_EDIT;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Adblock::mTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->langlist = $this->core->langlist;
                
                $this->view->render('index', 'view/admin/modules/adblock/view/', true, 'view/admin/');
            }
        }
        
        /**
         * action
         *
         * @return void
         * @throws NotFoundException
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
                            IS_DEMO ? Message::msgReply(true, 'success', Language::$word->PROCCESS_ERR_DEMO) : $this->_delete();
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
                $validate->set('title_' . $lang->abbr, Language::$word->NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>')->required()->string()->min_len(3)->max_len(80);
            }
            
            $validate
                ->set('start_date_submit', Language::$word->_MOD_AB_SUB10)->required()->date()
                ->set('end_date_submit', Language::$word->_MOD_AB_SUB11)->required()->required()->date()
                ->set('max_views', Language::$word->_MOD_AB_SUB1)->required()->numeric()
                ->set('max_clicks', Language::$word->_MOD_AB_SUB2)->required()->numeric()
                ->set('min_ctr', Language::$word->_MOD_AB_SUB3)->required()->numeric()->min_numeric(0)->max_numeric(1)
                ->set('banner_html', Language::$word->_MOD_AB_SUB2)->string();
            
            switch ($_POST['banner_type']) {
                case 'yes':
                    $validate
                        ->set('image_link', Language::$word->_MOD_AB_SUB5)->required()->url()
                        ->set('image_alt', Language::$word->_MOD_AB_SUB6)->required()->string();
                    break;
                
                case 'no':
                    $validate->set('html', Language::$word->_MOD_AB_SUB7)->required()->min_len(10)->text('advanced');
                    break;
            }
            
            (Filter::$id) ? $this->_update($validate) : $this->_add($validate);
        }
        
        /**
         * _add
         *
         * @param Validator $validate
         * @return void
         */
        public function _add(Validator $validate): void
        {
            $banner = File::upload('image', Adblock::MAXSIZE, 'png,jpg,jpeg,gif');
            
            $safe = $validate->safe();
            $data_m = array();
            if (count(Message::$msgs) === 0) {
                foreach ($this->core->langlist as $lang) {
                    $data_m['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
                }
                
                $data_x = array(
                    'plugin_id' => 'adblock/' . Utility::randomString(),
                    'start_date' => $this->db::toDate($safe->start_date_submit, false),
                    'end_date' => $this->db::toDate($safe->end_date_submit, false),
                    'total_views_allowed' => $safe->max_views,
                    'total_clicks_allowed' => $safe->max_clicks,
                    'minimum_ctr' => $safe->min_ctr
                );
                
                switch ($_POST['banner_type']) {
                    case 'yes':
                        $data_x['image_link'] = $safe->image_link;
                        $data_x['image_alt'] = $safe->image_alt;
                        break;
                    
                    case 'no':
                        $data_x['html'] = $safe->html;
                        break;
                }
                
                // Create a new plugin
                File::makeDirectory(FPLUGPATH . $data_x['plugin_id']);
                
                //process banner
                if (array_key_exists('image', $_FILES)) {
                    $path = FPLUGPATH . $data_x['plugin_id'] . '/';
                    $result = File::process($banner, $path, 'BANNER_');
                    $data_x['image'] = $result['fname'];
                }
                
                $last_id = $this->db->insert(Adblock::mTable, array_merge($data_m, $data_x))->run();
                
                $plugin_file_main = FPLUGPATH . $data_x['plugin_id'] . '/index.tpl.php';
                $plugin_file = FPLUGPATH . 'adblock/master.php';
                File::writeToFile($plugin_file_main, File::loadFile($plugin_file));
                
                $data_xq = array(
                    'system' => 1,
                    'cplugin' => 1,
                    'plugin_id' => $last_id,
                    'icon' => 'adblock/thumb.svg',
                    'plugalias' => $data_x['plugin_id'],
                    'active' => 1,
                );
                
                $this->db->insert(Plugin::mTable, array_merge($data_m, $data_xq))->run();
                $message = Message::formatSuccessMessage($data_m['title' . Language::$lang], Language::$word->_MOD_AB_ADDED_OK);
                
                if ($last_id) {
                    $json = array(
                        'type' => 'success',
                        'title' => Language::$word->SUCCESS,
                        'message' => $message,
                        'redirect' => Url::url('admin/modules/adblock')
                    );
                } else {
                    $json = array(
                        'type' => 'alert',
                        'title' => Language::$word->ALERT,
                        'message' => $message
                    );
                }
                print json_encode($json);
                Logger::writeLog($message);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * _update
         *
         * @param Validator $validate
         * @return void
         */
        private function _update(Validator $validate): void
        {
            $validate->set('plugin_id', 'Plugin ID')->required()->path();
            
            $banner = File::upload('image', Adblock::MAXSIZE, 'png,jpg,jpeg,gif');
            
            $safe = $validate->safe();
            $data_m = array();
            
            if (count(Message::$msgs) === 0) {
                foreach ($this->core->langlist as $lang) {
                    $data_m['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
                }
                
                $data_x = array(
                    'start_date' => $this->db::toDate($safe->start_date_submit, false),
                    'end_date' => $this->db::toDate($safe->end_date_submit, false),
                    'total_views_allowed' => $safe->max_views,
                    'total_clicks_allowed' => $safe->max_clicks,
                    'minimum_ctr' => $safe->min_ctr
                );
                
                switch ($_POST['banner_type']) {
                    case 'yes':
                        $data_x['image_link'] = $safe->image_link;
                        $data_x['image_alt'] = $safe->image_alt;
                        break;
                    
                    case 'no':
                        $data_x['html'] = $safe->html;
                        break;
                }
                
                //process banner
                if (array_key_exists('image', $_FILES)) {
                    $row = $this->db->select(Adblock::mTable, array('image'))->where('id', Filter::$id, '=')->first()->run();
                    
                    $path = FPLUGPATH . $safe->plugin_id . '/';
                    $result = File::process($banner, $path, 'BANNER_');
                    File::deleteFile($path . $row->image);
                    $data_x['image'] = $result['fname'];
                }
                
                $this->db->update(Adblock::mTable, array_merge($data_m, $data_x))->where('id', Filter::$id, '=')->run();
                
                $message = Message::formatSuccessMessage($data_m['title' . Language::$lang], Language::$word->_MOD_AB_UPDATE_OK);
                Message::msgReply($this->db->affected(), 'success', $message);
                Logger::writeLog($message);
            } else {
                Message::msgSingleStatus();
            }
        }
    }