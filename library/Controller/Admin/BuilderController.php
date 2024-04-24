<?php
    /**
     * BuilderController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: BuilderController.php, v1.00 6/1/2023 10:08 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Core\Module;
    use Wojo\Core\Plugin;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Exception\NotFoundException;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class BuilderController extends Controller
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
            $this->view->crumbs = ['admin', 'pages', Language::$word->META_T9];
            $this->view->title = Language::$word->META_T9;
            $this->view->caption = Language::$word->META_T9;
            $this->view->subtitle = null;
            
            $data = json_decode(json_encode($this->core->langlist), true);
            if (!$row = $this->db->select(Content::pTable)->where('id', $this->view->matches[1], '=')->where('is_builder', 1, '=')->first()->run() or !in_array($this->view->matches[0], array_column($data, 'abbr'))) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches[1]) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->langlist = $this->core->langlist;
                $this->view->plugins = Plugin::getFreePlugins(null);
                $this->view->modules = Module::getFreeModules(null);
                $this->view->colors = Utility::parseColors();
                
                $this->view->render('builder', 'view/admin/', false);
            }
        }
        
        /**
         * action
         *
         * @return void
         * @throws FileNotFoundException
         * @throws NotFoundException
         */
        public function action(): void
        {
            $postAction = Validator::post('action');
            $getAction = Validator::get('action');
            if ($postAction) {
                if (IS_AJAX) {
                    switch ($postAction) {
                        case 'save':
                            $this->save();
                            break;
                        
                        default:
                            Url::invalidMethod();
                            break;
                    }
                } else {
                    Url::invalidMethod();
                }
            }
            if ($getAction) {
                if (IS_AJAX) {
                    switch ($getAction) {
                        case 'load':
                            $this->load();
                            break;
                        
                        case 'block':
                            $this->block();
                            break;
                        
                        case 'section':
                            $this->section();
                            break;
                        
                        case 'plugin':
                            $this->plugin();
                            break;
                        
                        case 'userPlugin':
                            $this->userPlugin();
                            break;
                        
                        case 'module':
                            $this->module();
                            break;
                        
                        case 'loadModules':
                            $this->loadModules();
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
         * save
         *
         * @return void
         */
        private function save(): void
        {
            $validate = Validator::run($_POST);
            
            $validate
                ->set('id', 'Invalid ID detected')->required()->numeric()
                ->set('lang', 'Invalid Language detected')->required()->string()->min_len(2)->max_len(3)
                ->set('pagename', 'Invalid ID detected')->string()
                ->set('content', Language::$word->DESCRIPTION)->text('advanced');
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data = array();
                if ($safe->lang == 'all') {
                    foreach ($this->core->langlist as $lang) {
                        $data['body_' . $lang->abbr] = Url::in_url($safe->content);
                    }
                } else {
                    $data['body_' . $safe->lang] = Url::in_url($safe->content);
                }
                
                $this->db->update(Content::pTable, $data)->where('id', Filter::$id, '=')->run();
                $message = Message::formatSuccessMessage($safe->pagename, Language::$word->PAG_UPDATE_OK);
                Message::msgReply($this->db->affected(), 'success', $message);
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
            $lang = Validator::sanitize($_GET['lang'], 'alphalow', 2);
            if ($row = $this->db->select(Content::pTable, array('body_' . $lang))->where('id', Filter::$id, '=')->first()->run()) {
                print Content::parseContentData($row->{'body_' . $lang}, true);
            }
        }
        
        /**
         * loadModules
         *
         * @return void
         * @throws FileNotFoundException
         */
        private function loadModules(): void
        {
            if (isset($_GET['modalias'])) {
                $this->view->data = Module::getAvailableModules(Utility::implodeFields($_GET['modalias'], ',', true));
            } else {
                $this->view->data = Module::getAvailableModules();
            }
            
            if ($this->view->data) {
                $json = array(
                    'html' => $this->view->snippet('loadBuilderModules', BASEPATH . 'view/admin/snippets/'),
                    'status' => 'success'
                );
            } else {
                $json['status'] = 'error';
            }
            print json_encode($json);
        }
        
        /**
         * module
         *
         * @return void
         * @throws FileNotFoundException
         * @throws NotFoundException
         */
        private function module(): void
        {
            $lg = Language::$lang;
            if ($row = $this->db->select(Module::mTable, array('id as module_id', "title$lg as title", 'modalias', 'parent_id as id'))->where('id', Filter::$id, '=')->first()->run()) {
                
                $this->view->id = $row->id;
                $this->view->module_id = $row->module_id;
                $this->view->alias = $row->modalias;
                $this->view->core = $this->core;
                $this->view->auth = $this->auth;
                
                if (File::is_File(FMODPATH . $row->modalias . '/themes/' . $this->core->theme . '/index.tpl.php')) {
                    $content = $this->view->snippet('index', FMODPATH . $row->modalias . '/themes/' . $this->core->theme . '/');
                } else {
                    $content = $this->view->snippet('index', FMODPATH . $row->modalias . '/');
                }
                
                $assets = Module::parseModuleAssets('%%' . $row->modalias . '|module|0|0"%%');
                $json = array(
                    'html' => $content,
                    'status' => 'success',
                    'assets' => $assets,
                    'assets_id' => $row->modalias
                );
            } else {
                $json = array(
                    'html' => '',
                    'status' => 'error'
                );
            }
            print json_encode($json);
        }
        
        /**
         * plugin
         *
         * @return void
         * @throws FileNotFoundException
         */
        private function plugin(): void
        {
            if ($row = Plugin::renderAll(Utility::implodeFields(array(Filter::$id)))) {
                
                $this->view->id = $row[0]->id;
                $this->view->plugin_id = $row[0]->plugin_id;
                $this->view->all = $row;
                $this->view->core = $this->core;
                $this->view->auth = $this->auth;
                
                if (File::is_File(FPLUGPATH . $row[0]->plugalias . '/themes/' . $this->core->theme . '/index.tpl.php')) {
                    $content = $this->view->snippet('index', FPLUGPATH . $row[0]->plugalias . '/themes/' . $this->core->theme . '/');
                } else {
                    $content = $this->view->snippet('index', FPLUGPATH . $row[0]->plugalias . '/');
                }
                $json = array(
                    'html' => $content,
                    'status' => 'success'
                );
            } else {
                $json['status'] = 'error';
                $json['html'] = '';
            }
            print json_encode($json);
        }
        
        /**
         * userPlugin
         *
         * @return void
         */
        private function userPlugin(): void
        {
            $lg = Language::$lang;
            if ($row = $this->db->select(Plugin::mTable, array('id', "title$lg as title", "body$lg as body", 'show_title', 'alt_class'))->where('id', Filter::$id, '=')->first()->run()) {
                $json = array(
                    'html' => Url::out_url($row->body),
                    'status' => 'success'
                );
            } else {
                $json['status'] = 'error';
                $json['html'] = '';
            }
            print json_encode($json);
        }
        
        /**
         * section
         *
         * @return void
         */
        private function section(): void
        {
            if (File::getFile(BUILDERBASE . 'themes/' . $_GET['file'] . '.tpl.php')) {
                $file = File::loadFile(BUILDERBASE . 'themes/' . $_GET['file'] . '.tpl.php');
                $file = str_replace('[SITEURL]', SITEURL, $file);
                $json = array(
                    'html' => $file,
                    'status' => 'success'
                );
            } else {
                $json['status'] = 'error';
            }
            print json_encode($json);
        }
        
        /**
         * block
         *
         * @return void
         */
        private function block(): void
        {
            if (File::getFile(BUILDERBASE . 'themes/' . $_GET['file'] . '.tpl.php')) {
                $file = File::loadFile(BUILDERBASE . 'themes/' . $_GET['file'] . '.tpl.php');
                $file = str_replace('[SITEURL]', SITEURL, $file);
                $json = array(
                    'html' => $file,
                    'status' => 'success'
                );
            } else {
                $json['status'] = 'error';
            }
            print json_encode($json);
        }
    }