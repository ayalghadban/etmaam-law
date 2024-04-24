<?php
    /**
     * TimelineController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: TimelineController.php, v1.00 5/29/2023 8:21 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin\Module\Timeline;
    
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Core\Module;
    use Wojo\Core\Plugin;
    use Wojo\Core\Router;
    use Wojo\Database\Paginator;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Module\Timeline\Timeline;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class TimelineController extends Controller
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
            $this->view->data = $this->db->select(Timeline::mTable)->orderBy('created', 'DESC')->run();
            
            $this->view->crumbs = ['admin', 'modules', Language::$word->_MOD_TML_TITLE];
            $this->view->caption = Language::$word->_MOD_TML_TITLE;
            $this->view->title = Language::$word->_MOD_TML_TITLE;
            $this->view->subtitle = Language::$word->_MOD_GA_SUB;
            $this->view->render('index', 'view/admin/modules/timeline/view/', true, 'view/admin/');
        }
        
        /**
         * new
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function new(): void
        {
            $this->view->layoutlist = Timeline::layoutMode();
            $this->view->typelist = Timeline::typeList();
            
            $this->view->crumbs = ['admin', 'modules', 'timeline', Language::$word->_MOD_TML_ADD];
            $this->view->caption = Language::$word->_MOD_TML_ADD;
            $this->view->title = Language::$word->_MOD_TML_ADD;
            $this->view->subtitle = [];
            $this->view->render('index', 'view/admin/modules/timeline/view/', true, 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'modules', 'timeline', Language::$word->_MOD_TML_SUB];
            $this->view->title = Language::$word->_MOD_TML_SUB;
            $this->view->caption = Language::$word->_MOD_TML_SUB;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Timeline::mTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->layoutlist = Timeline::layoutMode();
                
                $this->view->render('index', 'view/admin/modules/timeline/view/', true, 'view/admin/');
            }
        }
        
        /**
         * customItems
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function customItems(): void
        {
            $this->view->crumbs = ['admin', 'modules', 'timeline', Language::$word->_MOD_TML_SUB10];
            $this->view->title = Language::$word->_MOD_TML_SUB10;
            $this->view->caption = Language::$word->_MOD_TML_SUB10;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Timeline::mTable)->where('id', $this->view->matches, '=')->where('type', 'custom', '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $pager = Paginator::instance();
                $pager->items_total = $this->db->count(Timeline::dTable)->run();
                $pager->default_ipp = $this->core->perpage;
                $pager->path = Url::url(Router::$path, '?');
                $pager->paginate();
                
                $this->view->pager = $pager;
                $this->view->row = $row;
                $this->view->data = $this->db->rawQuery('SELECT * FROM `' . Timeline::dTable . '` WHERE timeline_id = ? ORDER BY created DESC' . $pager->limit, array($row->id))->run();
                
                $this->view->subtitle = $row->name;
                $this->view->render('index', 'view/admin/modules/timeline/view/', true, 'view/admin/');
            }
        }
        
        /**
         * customSave
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function customNew(): void
        {
            $this->view->crumbs = ['admin', 'modules', 'timeline', Language::$word->_MOD_TML_SUB11];
            $this->view->title = Language::$word->_MOD_TML_SUB11;
            $this->view->caption = Language::$word->_MOD_TML_SUB11;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Timeline::mTable)->where('id', $this->view->matches, '=')->where('type', 'custom', '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->row = $row;
                $this->view->langlist = $this->core->langlist;
                
                $this->view->crumbs = ['admin', 'modules', 'timeline', array(0 => 'items', 1 => 'items/' . $this->view->matches), Language::$word->_MOD_TML_SUB11];
                $this->view->render('index', 'view/admin/modules/timeline/view/', true, 'view/admin/');
            }
        }
        
        /**
         * customEdit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function customEdit(): void
        {
            $this->view->crumbs = ['admin', 'modules', 'timeline', Language::$word->_MOD_TML_SUB10];
            $this->view->title = Language::$word->_MOD_TML_SUB12;
            $this->view->caption = Language::$word->_MOD_TML_SUB12;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Timeline::mTable)->where('id', $this->view->matches[0], '=')->where('type', 'custom', '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->row = $row;
                $this->view->langlist = $this->core->langlist;
                $this->view->data = $this->db->select(Timeline::dTable)->where('id', $this->view->matches[1], '=')->first()->run();
                $this->view->imagedata = Utility::jSonToArray($this->view->data->images);
                
                $this->view->crumbs = ['admin', 'modules', 'timeline', array(0 => 'items', 1 => 'items/' . $this->view->matches[0]), 'edit'];
                $this->view->render('index', 'view/admin/modules/timeline/view/', true, 'view/admin/');
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
                        
                        case 'customAdd':
                        case 'customUpdate':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->processItem();
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
        private function process(): void
        {
            $validate = Validator::run($_POST);
            
            $validate
                ->set('name', Language::$word->NAME)->required()->string()->min_len(3)->max_len(80)
                ->set('colmode', Language::$word->_MOD_TML_LMODE)->required()->string()
                ->set('showmore', Language::$word->_MOD_TML_SUB5)->required()->numeric()
                ->set('maxitems', Language::$word->_MOD_TML_SUB4)->required()->numeric()
                ->set('type', Language::$word->_MOD_TML_SUB9)->required()->string();
            
            switch ($_POST['type']) {
                case 'rss':
                    $validate->set('rssurl', Language::$word->_MOD_TML_SUB8)->required()->url();
                    break;
                
                case 'facebook':
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
        private function _add(Validator $validate): void
        {
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data_m = array();
                $data = array(
                    'name' => $safe->name,
                    'plugin_id' => 'timeline/' . Utility::randomString(),
                    'showmore' => $safe->showmore,
                    'maxitems' => $safe->maxitems,
                    'colmode' => $safe->colmode,
                    'type' => $safe->type,
                    'rssurl' => $safe->type == 'rss' ? $safe->rssurl : 'NULL',
                );
                
                $last_id = $this->db->insert(Timeline::mTable, $data)->run();
                
                foreach ($this->core->langlist as $lang) {
                    $data_m['title_' . $lang->abbr] = $safe->name;
                }
                
                $data_p = array(
                    'modalias' => $data['plugin_id'],
                    'parent_id' => $last_id,
                    'icon' => 'timeline/thumb.svg',
                    'active' => 1,
                    'is_builder' => 1,
                );
                
                // Create a new plugin
                File::makeDirectory(FMODPATH . $data['plugin_id']);
                File::copyFile(FMODPATH . 'timeline/master.php', FMODPATH . $data['plugin_id'] . '/index.tpl.php');
                
                $message = Message::formatSuccessMessage($data['name'], Language::$word->_MOD_TML_ADDED_OK);
                $this->db->insert(Module::mTable, array_merge($data_m, $data_p))->run();
                
                if ($safe->type == 'custom') {
                    $json['redirect'] = Url::url('admin/modules/timeline/items', $last_id);
                }
                $json['type'] = 'success';
                $json['title'] = Language::$word->SUCCESS;
                $json['message'] = $message;
                
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
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data_f = array();
                $data = array(
                    'name' => $safe->name,
                    'showmore' => $safe->showmore,
                    'maxitems' => $safe->maxitems,
                    'colmode' => $safe->colmode,
                    'rssurl' => $safe->type == 'rss' ? $safe->rssurl : 'NULL',
                );
                
                foreach ($this->core->langlist as $lang) {
                    $data_f['title_' . $lang->abbr] = $safe->name;
                }
                
                $this->db->update(Timeline::mTable, $data)->where('id', Filter::$id, '=')->run();
                
                $message = Message::formatSuccessMessage($data['name'], Language::$word->_MOD_TML_UPDATE_OK);
                Message::msgReply($this->db->affected(), 'success', $message);
                $this->db->update(Module::mTable, $data_f)->where('parent_id', Filter::$id, '=')->where('modalias', 'timeline', '=')->run();
                Logger::writeLog($message);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * processItem
         *
         * @return void
         */
        private function processItem(): void
        {
            $validate = Validator::run($_POST);
            foreach ($this->core->langlist as $lang) {
                $validate
                    ->set('title_' . $lang->abbr, Language::$word->NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>')->required()->string()->min_len(3)->max_len(80)
                    ->set('body_' . $lang->abbr, Language::$word->DESCRIPTION)->text('advanced');
            }
            
            $validate
                ->set('type', Language::$word->_MOD_TML_SUB14)->required()->string()
                ->set('timeline_id', 'Timeline ID')->required()->numeric()
                ->set('readmore', Language::$word->PUBLISHED)->string();
            
            switch ($_POST['type']) {
                case 'iframe':
                    $validate->set('dataurl', Language::$word->_MOD_TML_IURL)->required()->url();
                    break;
                
                case 'gallery':
                    $validate->set('images', Language::$word->IMAGES)->one();
                    break;
            }
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $datam = array();
                foreach ($this->core->langlist as $lang) {
                    $datam['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
                    $datam['body_' . $lang->abbr] = Url::in_url($_POST['body_' . $lang->abbr]);
                }
                $datax = array(
                    'type' => $safe->type,
                    'timeline_id' => $safe->timeline_id,
                    'readmore' => $safe->readmore,
                    'images' => isset($_POST['images']) ? json_encode($_POST['images']) : 'NULL',
                    'dataurl' => isset($_POST['dataurl']) ? Validator::sanitize($_POST['dataurl'], 'url') : 'NULL',
                    'height' => isset($_POST['height']) ? intval($_POST['height']) : 300,
                );
                
                $data = array_merge($datam, $datax);
                $last_id = 0;
                (Filter::$id) ? $this->db->update(Timeline::dTable, $data)->where('id', Filter::$id, '=')->run() : $last_id = $this->db->insert(Timeline::dTable, $data)->run();
                
                if (Filter::$id) {
                    $message = Message::formatSuccessMessage($datam['title' . Language::$lang], Language::$word->_MOD_TML_ITMUPDATE_OK);
                    Message::msgReply($this->db->affected(), 'success', $message);
                    Logger::writeLog($message);
                } else {
                    if ($last_id) {
                        $message = Message::formatSuccessMessage($datam['title' . Language::$lang], Language::$word->_MOD_TML_ITMADDED_OK);
                        $json['type'] = 'success';
                        $json['title'] = Language::$word->SUCCESS;
                        $json['message'] = $message;
                        $json['redirect'] = Url::url('admin/modules/timeline/items', $safe->timeline_id);
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
         * _delete
         *
         * @param string $type
         * @return void
         */
        private function _delete(string $type): void
        {
            $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
            
            if ($type == 'custom') {
                $this->db->delete(Timeline::dTable)->where('id', Filter::$id, '=')->run();
                $message = str_replace('[NAME]', $title, Language::$word->_MOD_TML_DELI_OK);
            } else {
                if ($row = $this->db->select(Timeline::mTable, array('id', 'plugin_id'))->where('id', Filter::$id, '=')->first()->run()) {
                    $this->db->delete(Timeline::mTable)->where('id', $row->id, '=')->run();
                    $this->db->delete(Timeline::dTable)->where('timeline_id', $row->id, '=')->run();
                    $this->db->delete(Plugin::mTable)->where('plugalias', $row->plugin_id, '=')->run();
                    $this->db->delete(Module::mTable)->where('parent_id', Filter::$id, '=')->where('modalias', 'timeline', '=')->run();
                    File::deleteDirectory(FPLUGPATH . $row->plugin_id);
                }
                
                $message = str_replace('[NAME]', $title, Language::$word->_MOD_TML_DEL_OK);
            }
            Message::msgReply(true, 'success', $message);
            Logger::writeLog($message);
        }
    }