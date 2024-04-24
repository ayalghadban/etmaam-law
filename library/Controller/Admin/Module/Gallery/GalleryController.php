<?php
    /**
     * GalleryController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: GalleryController.php, v1.00 5/12/2023 8:31 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin\Module\Gallery;
    
    use Exception;
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Core\Module;
    use Wojo\Core\Upload;
    use Wojo\Debug\Debug;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Exception\NotFoundException;
    use Wojo\File\File;
    use Wojo\Image\Image;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Module\Gallery\Gallery;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class GalleryController extends Controller
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
            
            $this->view->data = Gallery::getAllGalleries();
            
            $this->view->crumbs = ['admin', 'modules', Language::$word->_MOD_GA_TITLE];
            $this->view->caption = Language::$word->_MOD_GA_TITLE;
            $this->view->title = Language::$word->_MOD_GA_TITLE;
            $this->view->subtitle = Language::$word->_MOD_GA_SUB;
            $this->view->render('index', 'view/admin/modules/gallery/view/', true, 'view/admin/');
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
            
            $this->view->crumbs = ['admin', 'modules', 'gallery', 'new'];
            $this->view->caption = Language::$word->_MOD_GA_NEW;
            $this->view->title = Language::$word->_MOD_GA_NEW;
            $this->view->subtitle = [];
            $this->view->render('index', 'view/admin/modules/gallery/view/', true, 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'modules', 'gallery', 'edit'];
            $this->view->title = Language::$word->_MOD_GA_TITLE1;
            $this->view->caption = Language::$word->_MOD_GA_TITLE1;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Gallery::mTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->langlist = $this->core->langlist;
                
                $this->view->render('index', 'view/admin/modules/gallery/view/', true, 'view/admin/');
            }
        }
        
        /**
         * photos
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function photos(): void
        {
            $this->view->crumbs = ['admin', 'modules', 'gallery', 'photos'];
            $this->view->title = Language::$word->_MOD_GA_SUB4;
            $this->view->caption = Language::$word->_MOD_GA_SUB4;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Gallery::mTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->langlist = $this->core->langlist;
                $this->view->photos = $this->db->select(Gallery::dTable)->where('parent_id', $row->id, '=')->orderBy('sorting', 'ASC')->run();
                
                $this->view->subtitle = $row->{'title' . Language::$lang};
                $this->view->render('index', 'view/admin/modules/gallery/view/', true, 'view/admin/');
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
                        
                        case 'photo':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->photo();
                            break;
                        
                        case 'sort':
                            IS_DEMO ? print '0' : $this->sort();
                            break;
                        
                        case 'poster':
                            IS_DEMO ? Message::msgReply(true, 'success', Language::$word->PROCESS_ERR_DEMO) : $this->poster();
                            break;
                        
                        case 'resize':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->resize();
                            break;
                        
                        case 'upload':
                            IS_DEMO ? Message::msgReply(true, 'success', Language::$word->PROCESS_ERR_DEMO) : $this->upload();
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
                        case 'load':
                            $this->load();
                            break;
                        
                        case 'photo':
                            $this->view->data = $this->db->select(Gallery::dTable)->where('id', Filter::$id, '=')->first()->run();
                            $this->view->langlist = $this->core->langlist;
                            $this->view->render('editPhoto', 'view/admin/modules/gallery/snippets/', false);
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
        public function process(): void
        {
            $validate = Validator::run($_POST);
            foreach ($this->core->langlist as $lang) {
                $validate
                    ->set('title_' . $lang->abbr, Language::$word->NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>')->required()->string()->min_len(3)->max_len(60)
                    ->set('slug_' . $lang->abbr, Language::$word->ITEMSLUG . ' <span class="flag icon ' . $lang->abbr . '"></span>')->string()
                    ->set('description_' . $lang->abbr, Language::$word->DESCRIPTION . ' <span class="flag icon ' . $lang->abbr . '"></span>')->string();
            }
            
            $validate
                ->set('thumb_w', Language::$word->_MOD_GA_THUMBW)->required()->numeric()->min_len(2)->max_len(3)
                ->set('thumb_h', Language::$word->_MOD_GA_THUMBH)->required()->numeric()->min_len(2)->max_len(3)
                ->set('cols', Language::$word->_MOD_GA_COLS)->required()->numeric()
                ->set('watermark', Language::$word->_MOD_GA_WMARK)->required()->numeric()
                ->set('likes', Language::$word->_MOD_GA_LIKE)->required()->numeric()
                ->set('resize', Language::$word->_MOD_GA_RESIZE_THE)->required()->string();
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data_m = array();
                $data_md = array();
                foreach ($this->core->langlist as $i => $lang) {
                    $slug[$i] = (strlen($safe->{'slug_' . $lang->abbr}) === 0)
                        ? Url::doSeo($safe->{'title_' . $lang->abbr})
                        : Url::doSeo($safe->{'slug_' . $lang->abbr});
                    $data_m['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
                    $data_m['slug_' . $lang->abbr] = $slug[$i];
                    $data_m['description_' . $lang->abbr] = $safe->{'description_' . $lang->abbr};
                    
                    //module
                    $data_md['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
                    $data_md['description_' . $lang->abbr] = $safe->{'description_' . $lang->abbr};
                }
                
                
                $data_x = array(
                    'thumb_w' => $safe->thumb_w,
                    'thumb_h' => $safe->thumb_h,
                    'cols' => $safe->cols,
                    'watermark' => $safe->watermark,
                    'likes' => $safe->likes,
                    'resize' => $safe->resize,
                );
                
                if (!Filter::$id) {
                    $data_x['dir'] = Utility::randomString(12);
                    File::makeDirectory(FMODPATH . Gallery::GALDATA . $data_x['dir'] . '/thumbs/');
                }
                $data = array_merge($data_m, $data_x);
                $last_id = 0;
                (Filter::$id) ? $this->db->update(Gallery::mTable, $data)->where('id', Filter::$id, '=')->run() : $last_id = $this->db->insert(Gallery::mTable, $data)->run();
                
                if (Filter::$id) {
                    $message = Message::formatSuccessMessage($data_m['title' . Language::$lang], Language::$word->_MOD_GA_UPDATE_OK);
                    Message::msgReply($this->db->affected(), 'success', $message);
                    $this->db->update(Module::mTable, $data_md)->where('parent_id', Filter::$id, '=')->where('modalias', 'gallery', '=')->run();
                    Logger::writeLog($message);
                } else {
                    if ($last_id) {
                        $data_p = array(
                            'modalias' => 'gallery',
                            'is_builder' => 1,
                            'parent_id' => $last_id,
                            'icon' => 'gallery/thumb.svg',
                            'active' => 1,
                        );
                        $this->db->insert(Module::mTable, array_merge($data_md, $data_p))->run();
                        
                        $message = Message::formatSuccessMessage($data_m['title' . Language::$lang], Language::$word->_MOD_GA_ADDED_OK);
                        $json['type'] = 'success';
                        $json['title'] = Language::$word->SUCCESS;
                        $json['message'] = $message;
                        $json['redirect'] = Url::url('admin/modules/gallery/photos', $last_id);
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
         * photo
         *
         * @return void
         */
        private function photo(): void
        {
            $validate = Validator::run($_POST);
            foreach ($this->core->langlist as $lang) {
                $validate
                    ->set('title_' . $lang->abbr, Language::$word->PAG_NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>')->required()->string()->min_len(3)->max_len(60)
                    ->set('description_' . $lang->abbr, Language::$word->DESCRIPTION . ' <span class="flag icon ' . $lang->abbr . '"></span>')->string();
            }
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data = array();
                foreach ($this->core->langlist as $lang) {
                    $data['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
                    $data['description_' . $lang->abbr] = $safe->{'description_' . $lang->abbr};
                }
                
                $this->db->update(Gallery::dTable, $data)->where('id', Filter::$id, '=')->run();
                $html = '<h4>' . $data['title' . Language::$lang] . '</h4><p>' . $data['description' . Language::$lang] . '</p>';
                Message::msgModalReply($this->db->affected(), 'success', Message::formatSuccessMessage($data['title' . Language::$lang], Language::$word->_MOD_GA_PHOTO_OK), $html);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * resize
         *
         * @return void
         */
        private function resize(): void
        {
            $validate = Validator::run($_POST);
            $validate
                ->set('resize', Language::$word->_MOD_GA_RESIZE_THE)->required()->string()->min_len(3)->max_len(60)
                ->set('thumb_w', Language::$word->_MOD_GA_THUMBW)->required()->numeric()->min_len(2)->max_len(3)
                ->set('thumb_h', Language::$word->_MOD_GA_THUMBH)->required()->numeric()->min_len(2)->max_len(3)
                ->set('dir', Language::$word->_MOD_GA_DIR)->required()->path();
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $images = File::findFiles(FMODPATH . Gallery::GALDATA . $safe->dir . '/', array('fileTypes' => array('jpg', 'png'), 'level' => 0, 'returnType' => 'fileOnly'));
                if ($images) {
                    switch ($safe->resize) {
                        case 'thumbnail':
                            foreach ($images as $row) {
                                $img = new Image(FMODPATH . Gallery::GALDATA . $safe->dir . '/' . $row);
                                $img->thumbnail($safe->thumb_w, $safe->thumb_h)->save(FMODPATH . Gallery::GALDATA . $safe->dir . '/thumbs/' . $row);
                            }
                            break;
                        case 'resize':
                            foreach ($images as $row) {
                                $img = new Image(FMODPATH . Gallery::GALDATA . $safe->dir . '/' . $row);
                                $img->resize($safe->thumb_w, $safe->thumb_h)->save(FMODPATH . Gallery::GALDATA . $safe->dir . '/thumbs/' . $row);
                            }
                            break;
                        case 'bestFit':
                            foreach ($images as $row) {
                                $img = new Image(FMODPATH . Gallery::GALDATA . $safe->dir . '/' . $row);
                                $img->bestFit($safe->thumb_w, $safe->thumb_h)->save(FMODPATH . Gallery::GALDATA . $safe->dir . '/thumbs/' . $row);
                            }
                            break;
                        case 'resizeToHeight':
                            foreach ($images as $row) {
                                $img = new Image(FMODPATH . Gallery::GALDATA . $safe->dir . '/' . $row);
                                $img->resizeToHeight($safe->thumb_h)->save(FMODPATH . Gallery::GALDATA . $safe->dir . '/thumbs/' . $row);
                            }
                            break;
                        case 'resizeToWidth':
                            foreach ($images as $row) {
                                $img = new Image(FMODPATH . Gallery::GALDATA . $safe->dir . '/' . $row);
                                $img->resizeToWidth($safe->thumb_w)->save(FMODPATH . Gallery::GALDATA . $safe->dir . '/thumbs/' . $row);
                            }
                            break;
                    }
                    $json['type'] = 'success';
                    $json['title'] = Language::$word->SUCCESS;
                    $json['message'] = str_replace('[NUMBER]', '<b>' . count($images) . '</b>', Language::$word->_MOD_GA_RESIZE_OK);
                } else {
                    $json['type'] = 'error';
                    $json['title'] = Language::$word->ERROR;
                    $json['message'] = Language::$word->FU_ERROR16;
                }
                print json_encode($json);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * load
         *
         * @return void
         * @throws FileNotFoundException
         */
        private function load(): void
        {
            if ($row = $this->db->select(Gallery::dTable)->where('parent_id', Filter::$id, '=')->run()) {
                $this->view->photos = $row;
                $this->view->data = $this->db->select(Gallery::mTable, array('dir', 'poster'))->where('id', Filter::$id, '=')->first()->run();
                $json['type'] = 'success';
                $json['html'] = $this->view->snippet('loadPhotos', 'view/admin/modules/gallery/snippets/');
            } else {
                $json['type'] = 'error';
            }
            print json_encode($json);
        }
        
        /**
         * upload
         *
         * @return void
         */
        private function upload(): void
        {
            if (array_key_exists('file', $_FILES)) {
                $dir = File::validateDirectory(FMODPATH . Gallery::GALDATA, Validator::post('dir')) . '/';
                $upl = Upload::instance($this->core->file_size, 'png,jpg,jpeg');
                $upl->process('file', $dir, false, $_FILES['file']['name'], false);
                if (count(Message::$msgs) === 0) {
                    $row = $this->db->select(Gallery::mTable, array('id', 'thumb_w', 'thumb_h', 'resize', 'dir'))->where('dir', Validator::sanitize($_POST['dir']), '=')->first()->run();
                    try {
                        $img = new Image($dir . $upl->fileInfo['fname']);
                        switch ($row->resize) {
                            case 'resizeToHeight' :
                                $img->resizeToHeight($row->thumb_h)->save($dir . '/thumbs/' . $upl->fileInfo['fname']);
                                break;
                            
                            case 'resizeToWidth' :
                                $img->resizeToWidth($row->thumb_w)->save($dir . '/thumbs/' . $upl->fileInfo['fname']);
                                break;
                            
                            case 'bestFit' :
                                $img->bestFit($row->thumb_w, $row->thumb_h)->save($dir . '/thumbs/' . $upl->fileInfo['fname']);
                                break;
                            
                            case 'resize' :
                                $img->resize($row->thumb_w, $row->thumb_h)->save($dir . '/thumbs/' . $upl->fileInfo['fname']);
                                break;
                            
                            default :
                                $img->thumbnail($row->thumb_w, $row->thumb_h)->save($dir . '/thumbs/' . $upl->fileInfo['fname']);
                                break;
                            
                        }
                        $data = array_merge(Utility::insertLangSlugs('title', $upl->fileInfo['xame']), array('parent_id' => $row->id, 'thumb' => $upl->fileInfo['fname']));
                        $this->db->insert(Gallery::dTable, $data)->run();
                    } catch (Exception $e) {
                        Debug::addMessage('errors', '<i>Error</i>', $e->getMessage(), 'session');
                    }
                    $json['type'] = 'success';
                    $json['filename'] = FMODULEURL . Gallery::GALDATA . $row->dir . '/thumbs/' . $upl->fileInfo['fname'];
                } else {
                    $json['type'] = 'error';
                    $json['filename'] = '';
                    $json['message'] = Message::$msgs['name'];
                }
                print json_encode($json);
            }
        }
        
        /**
         * poster
         *
         * @return void
         */
        private function poster(): void
        {
            if ($this->db->update(Gallery::mTable, array('poster' => Validator::sanitize($_POST['thumb'])))->where('id', Filter::$id, '=')->run()) {
                $json['type'] = 'success';
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
            $table = ($_POST['type'] == 'sortAlbums') ? Gallery::mTable : Gallery::dTable;
            $i = 0;
            $query = 'UPDATE `' . $table . '` SET `sorting` = CASE ';
            $list = '';
            foreach ($_POST['sorting'] as $item):
                $i++;
                $query .= ' WHEN id = ' . $item . ' THEN ' . $i . ' ';
                $list .= $item . ',';
            endforeach;
            $list = substr($list, 0, -1);
            $query .= '
				  END
				  WHERE id IN (' . $list . ')';
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
            $res = 0;
            if ($type == 'photo') {
                if ($row = $this->db->select(Gallery::dTable, array('thumb'))->where('id', Filter::$id, '=')->first()->run()) {
                    $res = $this->db->delete(Gallery::dTable)->where('id', Filter::$id, '=')->run();
                    File::deleteFile(FMODPATH . Gallery::GALDATA . $_POST['dir'] . '/' . $row->thumb);
                    File::deleteFile(FMODPATH . Gallery::GALDATA . $_POST['dir'] . '/thumbs/' . $row->thumb);
                }
                
                $message = str_replace('[NAME]', $title, Language::$word->_MOD_GA_PHOTO_DEL_OK);
                Message::msgReply($res, 'success', $message);
            } else {
                if ($row = $this->db->select(Gallery::mTable, array('dir'))->where('id', Filter::$id, '=')->first()->run()) {
                    $res = $this->db->delete(Gallery::mTable)->where('id', Filter::$id, '=')->run();
                    $this->db->delete(Gallery::dTable)->where('parent_id', Filter::$id, '=')->run();
                    $this->db->delete(Module::mcTable)->where('parent_id', Filter::$id, '=')->where('section', 'gallery', '=')->run();
                    $this->db->delete(Module::mTable)->where('parent_id', Filter::$id, '=')->where('modalias', 'gallery', '=')->run();
                    File::deleteRecursive(FMODPATH . Gallery::GALDATA . $row->dir, true);
                }
                
                $message = str_replace('[NAME]', $title, Language::$word->_MOD_GA_DEL_OK);
                Message::msgReply($res, 'success', $message);
                Logger::writeLog($message);
            }
        }
    }