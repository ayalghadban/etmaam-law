<?php
    /**
     * MapController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: MapController.php, v1.00 5/22/2023 9:05 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin\Module\Map;
    
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Core\Plugin;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Module\Map\Map;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class MapController extends Controller
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
            
            $this->view->data = $this->db->select(Map::mTable)->orderBy('name', 'ASC')->run();
            
            $this->view->crumbs = ['admin', 'modules', Language::$word->_MOD_GM_TITLE];
            $this->view->caption = Language::$word->_MOD_GM_TITLE;
            $this->view->title = Language::$word->_MOD_GM_TITLE;
            $this->view->subtitle = null;
            $this->view->render('index', 'view/admin/modules/maps/view/', true, 'view/admin/');
        }
        
        /**
         * new
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function new(): void
        {
            $this->view->mtype = Map::mapType();
            $this->view->styles = File::findFiles(AMODPATH . 'maps/view/images/styles/', array('fileTypes' => array('png'), 'returnType' => 'fileOnly'));
            $this->view->pins = File::findFiles(FMODPATH . 'maps/view/images/pins/', array('fileTypes' => array('png'), 'returnType' => 'fileOnly'));
            
            $this->view->crumbs = ['admin', 'modules', 'maps', Language::$word->_MOD_GM_TITLE2];
            $this->view->caption = Language::$word->_MOD_GM_TITLE2;
            $this->view->title = Language::$word->_MOD_GM_TITLE2;
            $this->view->subtitle = [];
            $this->view->render('index', 'view/admin/modules/maps/view/', true, 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'modules', 'maps', Language::$word->_MOD_GM_TITLE1];
            $this->view->title = Language::$word->_MOD_GM_TITLE1;
            $this->view->caption = Language::$word->_MOD_GM_TITLE1;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Map::mTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->minmaxzoom = explode(',', $row->minmaxzoom);
                $this->view->mtype = Map::mapType();
                $this->view->styles = File::findFiles(AMODPATH . 'maps/view/images/styles/', array('fileTypes' => array('png'), 'returnType' => 'fileOnly'));
                $this->view->pins = File::findFiles(FMODPATH . 'maps/view/images/pins/', array('fileTypes' => array('png'), 'returnType' => 'fileOnly'));
                
                $this->view->render('index', 'view/admin/modules/maps/view/', true, 'view/admin/');
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
            $getAction = Validator::get('action');
            if ($getAction) {
                if (IS_AJAX) {
                    switch ($getAction) {
                        case 'load':
                            $this->load();
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
                ->set('name', Language::$word->NAME)->required()->string()->min_len(3)->max_len(80)
                ->set('lat', Language::$word->_MOD_GM_LAT)->required()->float()
                ->set('lng', Language::$word->_MOD_GM_LNG)->required()->float()
                ->set('body', Language::$word->M_ADDRESS)->required()->string(true, true)
                ->set('zoom', Language::$word->_MOD_GM_SUB1)->required()->numeric()->min_numeric(1)->max_numeric(20)
                ->set('minzoom', Language::$word->_MOD_GM_SUB1_1)->required()->numeric()->min_numeric(1)->max_numeric(10)
                ->set('maxzoom', Language::$word->_MOD_GM_SUB1_2)->required()->numeric()->min_numeric(1)->max_numeric(20)
                ->set('layout', Language::$word->_MOD_GM_SUB4)->required()->string()
                ->set('type', Language::$word->_MOD_GM_SUB)->required()->string()
                ->set('type_control', Language::$word->_MOD_GM_SUB2)->required()->numeric()
                ->set('streetview', Language::$word->_MOD_GM_SUB3)->required()->numeric()
                ->set('pin', Language::$word->_MOD_GM_SUB6)->required()->string();
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data_m = array();
                foreach ($this->core->langlist as $lang) {
                    $data_m['title_' . $lang->abbr] = $safe->name;
                }
                
                $data = array(
                    'name' => $safe->name,
                    'lat' => $safe->lat,
                    'lng' => $safe->lng,
                    'body' => $safe->body,
                    'zoom' => $safe->zoom,
                    'minmaxzoom' => $safe->minzoom . ',' . $safe->maxzoom,
                    'layout' => $safe->layout,
                    'type' => $safe->type,
                    'type_control' => $safe->type_control,
                    'streetview' => $safe->streetview,
                    'style' => File::loadFile(AMODPATH . 'maps/snippets/' . $safe->layout . '.json'),
                    'pin' => $safe->pin,
                );
                
                if (!Filter::$id) {
                    $data['plugin_id'] = 'maps/' . Utility::randomString();
                }
                $last_id = 0;
                (Filter::$id) ? $this->db->update(Map::mTable, $data)->where('id', Filter::$id, '=')->run() : $last_id = $this->db->insert(Map::mTable, $data)->run();
                
                // Create a new plugin
                if (!Filter::$id) {
                    File::makeDirectory(FPLUGPATH . $data['plugin_id']);
                    
                    $plugin_file_main = FPLUGPATH . $data['plugin_id'] . '/index.tpl.php';
                    $plugin_file = FPLUGPATH . 'maps/master.php';
                    File::writeToFile($plugin_file_main, str_replace('##MAPID##', $last_id, File::loadFile($plugin_file)));
                    
                    $data_xq = array(
                        'system' => 0,
                        'cplugin' => 0,
                        'plugin_id' => $last_id,
                        'icon' => 'maps/thumb.svg',
                        'plugalias' => $data['plugin_id'],
                        'groups' => 'maps',
                        'active' => 1,
                    );
                    
                    $this->db->insert(Plugin::mTable, array_merge($data_m, $data_xq))->run();
                }
                
                $message = Filter::$id ?
                    Message::formatSuccessMessage($data['name'], Language::$word->_MOD_GM_UPDATE_OK) :
                    Message::formatSuccessMessage($data['name'], Language::$word->_MOD_GM_ADDED_OK);
                
                Message::msgReply($this->db->affected(), 'success', $message);
                Logger::writeLog($message);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * load
         *
         * @return void
         */
        private function load(): void
        {
            print $this->db->select(Map::mTable)->where('id', Filter::$id, '=')->first()->run('json');
        }
        
        /**
         * _delete
         *
         * @return void
         */
        private function _delete(): void
        {
            $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
            
            if ($row = $this->db->select(Map::mTable, array('id', 'plugin_id'))->where('id', Filter::$id, '=')->first()->run()) {
                $this->db->delete(Map::mTable)->where('id', $row->id, '=')->run();
                if ($prow = $this->db->select(Plugin::mTable, array('id', 'plugin_id'))->where('plugalias', $row->plugin_id, '=')->first()->run()) {
                    $this->db->delete(Plugin::mTable)->where('id', $prow->id, '=')->run();
                    $this->db->delete(Plugin::lTable)->where('plug_id', $prow->id, '=')->run();
                }
                File::deleteDirectory(FPLUGPATH . $row->plugin_id);
            }
            
            $message = str_replace('[NAME]', $title, Language::$word->_MOD_GM_DEL_OK);
            Message::msgReply(true, 'success', $message);
            Logger::writeLog($message);
        }
    }