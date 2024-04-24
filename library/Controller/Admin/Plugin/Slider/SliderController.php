<?php
    /**
     * SliderController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: SliderController.php, v1.00 5/18/2023 8:14 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin\Plugin\Slider;
    
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Core\Plugin;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Exception\NotFoundException;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Plugin\Slider\Slider;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class SliderController extends Controller
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
            $this->view->data = Slider::getSliders();
            
            $this->view->crumbs = ['admin', 'plugins', Language::$word->_PLG_SL_TITLE];
            $this->view->caption = Language::$word->_PLG_SL_TITLE;
            $this->view->title = Language::$word->_PLG_SL_TITLE;
            $this->view->subtitle = Language::$word->_PLG_SL_SUB2;
            $this->view->render('index', 'view/admin/plugins/slider/view/', true, 'view/admin/');
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
            
            $this->view->crumbs = ['admin', 'plugins', 'slider', 'new'];
            $this->view->caption = Language::$word->_PLG_SL_TITLE1;
            $this->view->title = Language::$word->_PLG_SL_TITLE1;
            $this->view->subtitle = null;
            $this->view->render('index', 'view/admin/plugins/slider/view/', true, 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'plugins', 'slider', 'edit'];
            $this->view->title = Language::$word->_PLG_SL_TITLE2;
            $this->view->caption = Language::$word->_PLG_SL_TITLE2;
            $this->view->subtitle = Language::$word->_PLG_PL_SUB1;
            
            if (!$row = $this->db->select(Slider::mTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->slides = Slider::getSlides($row->id);
                $this->view->langlist = $this->core->langlist;
                $this->view->render('index', 'view/admin/plugins/slider/view/', true, 'view/admin/');
            }
        }
        
        /**
         * builder
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function builder(): void
        {
            
            $this->view->crumbs = ['admin', 'plugins', 'slider', 'builder'];
            $this->view->title = Language::$word->_PLG_SL_SUB14;
            $this->view->caption = Language::$word->_PLG_SL_SUB14;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Slider::dTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->row = $this->db->select(Slider::mTable)->where('id', $row->parent_id, '=')->first()->run();
                $this->view->render('_slider_builder', 'view/admin/plugins/slider/view/', false);
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
                        
                        case 'newSlide':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->newSlide();
                            break;
                        
                        case 'updateSlide':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->updateSlide();
                            break;
                        
                        case 'saveSlideData':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->saveSlideData();
                            break;
                        
                        case 'properties':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->properties();
                            break;
                        
                        case 'rename':
                            IS_DEMO ? print 'New Title' : $this->rename();
                            break;
                        
                        case 'duplicate':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->duplicate();
                            break;
                        
                        case 'sort':
                            IS_DEMO ? print '0' : $this->sort();
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
            
            $getAction = Validator::get('action');
            if ($getAction) {
                if (IS_AJAX) {
                    switch ($getAction) {
                        case 'loadSlide':
                            $this->loadSlide();
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
                ->set('autoplay', Language::$word->_PLG_SL_AUTOPLAY)->required()->numeric()
                ->set('autoplaySpeed', Language::$word->_PLG_SL_ASPEED)->required()->numeric()->min_len(3)->min_len(4)
                ->set('autoplayHoverPause', Language::$word->_PLG_SL_PONHOVER)->required()->numeric()
                ->set('autoloop', Language::$word->_PLG_SL_LOOPS)->required()->numeric()
                ->set('height', Language::$word->_PLG_SL_HEIGHT)->required()->numeric()
                ->set('layout', Language::$word->_PLG_SL_LAYOUT)->required()->string()
                ->set('transition', Language::$word->_PLG_SL_LAYOUT)->string();
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data_m = array();
                $settings = array(
                    'rtl' => false,
                    'autoloop' => !($safe->autoloop == 0),
                    'fullscreen' => (isset($_POST['fullscreen'])) ? 1 : 0,
                    'autoplay' => !($safe->autoplay == 0),
                    'autoplaySpeed' => $safe->autoplaySpeed,
                    'autoplayHoverPause' => !($safe->autoplayHoverPause == 0),
                    'layout' => $safe->layout,
                    'height' => $safe->height,
                    'arrows' => $safe->layout == 'standard' or $safe->layout == 'arrows',
                    'buttons' => $safe->layout == 'standard' or $safe->layout == 'dots',
                );
                
                $data = array(
                    'title' => $safe->title,
                    'autoplay' => $safe->autoplay,
                    'autoloop' => $safe->autoloop,
                    'autoplaySpeed' => $safe->autoplaySpeed,
                    'autoplayHoverPause' => $safe->autoplayHoverPause,
                    'fullscreen' => (isset($_POST['fullscreen'])) ? 1 : 0,
                    'layout' => $safe->layout,
                    'height' => $safe->height,
                    'settings' => json_encode($settings),
                );
                $last_id = 0;
                (Filter::$id) ? $this->db->update(Slider::mTable, $data)->where('id', Filter::$id, '=')->run() : $last_id = $this->db->insert(Slider::mTable, $data)->run();
                
                if (Filter::$id) {
                    $message = Message::formatSuccessMessage($data['title'], Language::$word->_PLG_SL_UPDATED);
                    Message::msgReply($this->db->affected(), 'success', $message);
                    Logger::writeLog($message);
                } else {
                    if ($last_id) {
                        // Insert new multi plugin
                        $plugin_id = 'slider/' . Utility::randomString();
                        File::makeDirectory(FPLUGPATH . $plugin_id);
                        File::copyFile(FPLUGPATH . 'slider/master.php', FPLUGPATH . $plugin_id . 'index.tpl.php');
                        
                        $pid = $this->db->select(Plugin::mTable, array('id'))->where('plugalias', 'slider', '=')->first()->run();
                        foreach ($this->core->langlist as $lang) {
                            $data_m['title_' . $lang->abbr] = $safe->title;
                        }
                        $data_x = array(
                            'parent_id' => $pid->id,
                            'plugin_id' => $last_id,
                            'groups' => 'slider',
                            'icon' => 'slider/thumb.svg',
                            'plugalias' => $plugin_id,
                            'cplugin' => 1,
                            'active' => 1,
                        );
                        $this->db->insert(Plugin::mTable, array_merge($data_m, $data_x))->run();
                        
                        $message = Message::formatSuccessMessage($data_m['title' . Language::$lang], Language::$word->_PLG_SL_ADDED);
                        $json['type'] = 'success';
                        $json['title'] = Language::$word->SUCCESS;
                        $json['message'] = $message;
                        $json['redirect'] = Url::url('/admin/plugins/slider/edit', Filter::$id ? : $last_id);
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
         * saveSlideData
         *
         * @return void
         */
        private function saveSlideData(): void
        {
            if (Filter::$id) {
                $data = array(
                    'image' => $_POST['image'] ? Validator::sanitize(str_replace(UPLOADURL, '', $_POST['image'])) : 'NULL',
                    'color' => $_POST['color'] ? Validator::sanitize($_POST['color']) : 'NULL',
                    'attrib' => Validator::sanitize($_POST['attr']),
                    'html' => Url::in_url(Validator::cleanOut($_POST['html'])),
                    'html_raw' => Url::in_url(Validator::cleanOut($_POST['html_raw'])),
                );
                $this->db->update(Slider::dTable, $data)->where('id', Filter::$id, '=')->run();
                $json['type'] = 'success';
            }
            $json['title'] = Language::$word->SUCCESS;
            $json['message'] = Message::formatSuccessMessage($_POST['slidename'], Language::$word->_PLG_SL_UPDATED);
            
            print json_encode($json);
        }
        
        /**
         * loadSlide
         *
         * @return void
         */
        private function loadSlide(): void
        {
            if ($row = $this->db->select(Slider::dTable)->where('id', Filter::$id, '=')->first()->run()) {
                $data = $this->db->select(Slider::mTable, array('height'))->where('id', $row->parent_id, '=')->first()->run();
                
                $json['html'] = Url::out_url($row->html_raw);
                $json['image'] = $row->image;
                $json['color'] = $row->color;
                $json['type'] = 'success';
                $json['height'] = $data->height == 100 ? '100vh' : $data->height . 'vh';
            } else {
                $json['type'] = 'error';
            }
            print json_encode($json);
        }
        
        /**
         * newSlide
         *
         * @return void
         * @throws FileNotFoundException
         * @noinspection CssUnknownTarget
         */
        private function newSlide(): void
        {
            $data = array(
                'parent_id' => Filter::$id,
                'title' => 'New Slide',
                'color' => '#ffffff',
                'image' => Validator::sanitize($_POST['image']),
                'mode' => 'bg',
            );
            $last_id = $this->db->insert(Slider::dTable, $data)->run();
            $data['id'] = $last_id;
            
            $sdata['html_raw'] = '
              <div class="uitem" id="item_' . $last_id . '" data-type="bg">
                <div class="uimage mh400" style="background-size: cover; background-position: center center; background-repeat: no-repeat; background-image: url([SITEURL]uploads/' . $data['image'] . ');">
                  <div class="ucontent max-height400">
                    <div class="row">
                      <div class="columns">
                        <div class="ws-layer" data-delay="50" data-duration="600" data-animation="popInLeft">
                          <div data-text="true" style="font-size: 40px;"><span style="color: #ffffff;">WELCOME TO CMS PRO</span></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>';
            $this->db->update(Slider::dTable, $sdata)->where('id', $last_id, '=')->run();
            
            $this->view->data = (object) $data;
            
            $json['id'] = $last_id;
            $json['mode'] = $data['mode'];
            $json['image'] = $data['image'];
            $json['thumb'] = $this->view->snippet('loadThumb', 'view/admin/plugins/slider/snippets/');
            $json['type'] = 'success';
            
            print json_encode($json);
        }
        
        /**
         * updateSlide
         *
         * @return void
         */
        private function updateSlide(): void
        {
            if ($row = $this->db->select(Slider::dTable)->where('id', Filter::$id, '=')->first()->run()) {
                $data = array(
                    'image' => isset($_POST['image']) ? Validator::sanitize($_POST['image']) : 'NULL',
                    'color' => isset($_POST['color']) ? Validator::sanitize($_POST['color']) : 'NULL',
                    'mode' => Validator::sanitize($_POST['mode']),
                );
                
                if (isset($_POST['image'])) {
                    $new_data = str_replace($row->image, $data['image'], $row->html_raw);
                    $data['html_raw'] = $new_data;
                }
                
                $this->db->update(Slider::dTable, $data)->where('id', Filter::$id, '=')->run();
            }
            
            $json['type'] = 'success';
            print json_encode($json);
        }
        
        /**
         * properties
         *
         * @return void
         * @throws FileNotFoundException
         */
        private function properties(): void
        {
            if ($row = $this->db->select(Slider::dTable)->where('id', Filter::$id, '=')->first()->run()) {
                $this->view->data = $row;
                $json = array(
                    'id' => $row->id,
                    'html' => $row->html_raw,
                    'thumb' => $this->view->snippet('loadThumb', 'view/admin/plugins/slider/snippets/'),
                    'color' => $row->color,
                    'mode' => $row->mode,
                    'image' => $row->image,
                    'baseimage' => basename($row->image),
                    'type' => 'success',
                );
            } else {
                $json['type'] = 'error';
            }
            print json_encode($json);
        }
        
        /**
         * rename
         *
         * @return void
         */
        private function rename(): void
        {
            $title = Validator::cleanOut($_POST['title']);
            $this->db->update(Slider::dTable, array('title' => $title))->where('id', Filter::$id, '=')->run();
            
            $json['title'] = Validator::truncate($title, 20);
            print json_encode($json);
        }
        
        /**
         * duplicate
         *
         * @return void
         * @throws FileNotFoundException
         */
        private function duplicate(): void
        {
            if ($row = $this->db->select(Slider::dTable)->where('id', Filter::$id, '=')->first()->run()) {
                $data = array(
                    'parent_id' => $row->parent_id,
                    'title' => $row->title,
                    'image' => $row->image ? : 'NULL',
                    'color' => $row->color ? : 'NULL',
                    'mode' => $row->mode,
                    'html_raw' => str_replace('item_' . Filter::$id, 'item_' . intval(Filter::$id + 1), $row->html_raw),
                    'html' => $row->html,
                );
                $last_id = $this->db->insert(Slider::dTable, $data)->run();
                $data['id'] = $last_id;
                
                $this->view->data = (object) $data;
                $json = array(
                    'id' => $last_id,
                    'html' => Url::out_url($row->html_raw),
                    'thumb' => $this->view->snippet('loadThumb', 'view/admin/plugins/slider/snippets/'),
                    'color' => $row->color,
                    'mode' => $row->mode,
                    'image' => $row->image,
                    'type' => 'success',
                );
            } else {
                $json['type'] = 'error';
            }
            print json_encode($json);
        }
        
        /**
         * sort
         *
         * @return void
         */
        private function sort(): void
        {
            $i = 0;
            $query = 'UPDATE `' . Slider::dTable . '` SET `sorting` = CASE ';
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
        
        
        /**
         * _delete
         *
         * @param string $type
         * @return void
         */
        private function _delete(string $type): void
        {
            $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
            
            if ($type == 'slide') {
                if ($this->db->delete(Slider::dTable)->where('id', Filter::$id, '=')->run()) {
                    $json['type'] = 'success';
                    print json_encode($json);
                }
            } else {
                $res = $this->db->delete(Slider::mTable)->where('id', Filter::$id, '=')->run();
                $this->db->delete(Slider::dTable)->where('parent_id', Filter::$id, '=')->run();
                if ($row = $this->db->select(Plugin::mTable, array('id', 'plugalias'))->where('plugin_id', Filter::$id, '=')->where('slider', 'poll', '=')->first()->run()) {
                    $this->db->delete(Content::lTable)->where('plug_id', $row->id, '=')->run();
                    $this->db->delete(Plugin::mTable)->where('id', $row->id, '=')->run();
                    
                    File::deleteDirectory(FPLUGPATH . $row->plugalias);
                }
                
                $message = str_replace('[NAME]', $title, Language::$word->_PLG_SL_DEL_OK);
                Message::msgReply($res, 'success', $message);
                Logger::writeLog($message);
            }
        }
    }