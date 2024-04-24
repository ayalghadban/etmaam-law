<?php
    
    /**
     * GalleryController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: GalleryController.php, v1.00 6/13/2023 9:29 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Front\Module\Gallery;
    
    
    use Exception;
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Core\Module;
    use Wojo\Core\Plugin;
    use Wojo\Debug\Debug;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\File\File;
    use Wojo\Image\Image;
    use Wojo\Language\Language;
    use Wojo\Module\Gallery\Gallery;
    use Wojo\Url\Url;
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
            $lg = Language::$lang;
            $this->view->title = str_replace('[COMPANY]', $this->core->company, Language::$word->META_T31);
            $this->view->keywords = null;
            $this->view->description = null;
            $this->view->meta = null;
            $this->view->crumbs = [array(0 => Language::$word->HOME, 1 => ''), Language::$word->_MOD_GA_TITLE];
            
            if (!$this->view->data = $this->db->select(Module::mTable, array("title$lg as title", "info$lg", "keywords$lg", "description$lg"))->where('modalias', 'gallery', '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid gallery module detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                    $this->view->render('error', 'view/front/themes/' . $this->core->theme . '/');
                } else {
                    $this->view->title = Language::$word->META_ERROR;
                    $this->view->render('404', 'view/front/themes/' . $this->core->theme . '/');
                }
            } else {
                $this->view->title = Url::formatMeta($this->view->data->title, $this->core->company);
                $this->view->keywords = $this->view->data->{'keywords' . $lg};
                $this->view->description = $this->view->data->{'description' . $lg};
                $this->view->crumbs = [array(0 => Language::$word->HOME, 1 => ''), $this->view->data->title];
                
                $this->view->rows = Gallery::getAllGalleries();
                $this->view->plugins = Plugin::getModulePlugins('gallery');
                $this->view->layout = Plugin::moduleLayout($this->view->plugins);
                $this->view->render('mod_index', 'view/front/themes/' . $this->core->theme . '/');
            }
        }
        
        public function action(): void
        {
            $postAction = Validator::post('action');
            $getAction = Validator::get('action');
            if ($postAction or $getAction) {
                if ($postAction) {
                    if (IS_AJAX) {
                        switch ($postAction) {
                            case 'like':
                                $this->like();
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
                    switch ($getAction) {
                        case 'watermark':
                            $this->watermark();
                            break;
                        default:
                            Url::invalidMethod();
                            break;
                    }
                }
            } else {
                Url::invalidMethod();
            }
        }
        
        /**
         * render
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function render(): void
        {
            $lg = Language::$lang;
            $this->view->title = str_replace('[COMPANY]', $this->core->company, Language::$word->META_T31);
            $this->view->keywords = null;
            $this->view->description = null;
            $this->view->meta = null;
            $this->view->crumbs = [array(0 => Language::$word->HOME, 1 => ''), Language::$word->_MOD_GA_TITLE];
            
            if (!$row = $this->db->select(Gallery::mTable)->where("slug$lg", $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid gallery album "' . $this->view->matches . '" detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                    $this->view->render('error', 'view/front/themes/' . $this->core->theme . '/');
                } else {
                    $this->view->title = Language::$word->META_ERROR;
                    $this->view->render('404', 'view/front/themes/' . $this->core->theme . '/');
                }
            } else {
                $this->view->data = $this->db->select(Module::mTable, array("title$lg as title", "info$lg", "keywords$lg", "description$lg"))->where('modalias', 'gallery', '=')->first()->run();
                $this->view->row = $row;
                $this->view->photos = $this->db->select(Gallery::dTable)->where('parent_id', $row->id, '=')->orderBy('sorting', 'ASC')->run();
                $this->view->plugins = Plugin::getModulePlugins('gallery');
                $this->view->layout = Plugin::moduleLayout($this->view->plugins);
                
                $this->view->title = Url::formatMeta($this->view->data->title, $this->core->modname['gallery']);
                $this->view->keywords = $this->view->data->{'keywords' . $lg};
                $this->view->description = $this->view->data->{'description' . $lg};
                $this->view->crumbs = [array(0 => Language::$word->HOME, 1 => ''), array(0 => $this->view->data->title, 1 => $this->core->modname['gallery']), $row->{'title' . Language::$lang}];
                
                $this->view->render('mod_index', 'view/front/themes/' . $this->core->theme . '/');
            }
        }
        
        /**
         * watermark
         *
         * @return void
         */
        private function watermark(): void
        {
            if ($dir = Validator::get('dir') and $thumb = Validator::get('thumb')) {
                if (File::exists($old = FMODPATH . Gallery::GALDATA . $dir . '/' . $thumb)) {
                    $new = FMODPATH . Gallery::GALDATA . $dir . '/w_' . $thumb;
                    try {
                        $img = new Image($old);
                        $img->overlay(UPLOADS . 'watermark.png', 'bottom right', .35, -30, -30)->save($new);
                        $image = imagecreatefromjpeg($new);
                        
                        header('Content-type: image/jpeg');
                        header('Content-Disposition: inline; filename=' . $new);
                        imagejpeg($image);
                        imagedestroy($image);
                        exit;
                    } catch (Exception $e) {
                        Debug::addMessage('errors', '<i>Error</i>', $e->getMessage(), 'session');
                    }
                }
            }
        }
        
        /**
         * like
         *
         * @return void
         */
        private function like(): void
        {
            if (Filter::$id) {
                $this->db->rawQuery('
				  UPDATE `' . Gallery::dTable . "`
				  SET likes = likes + 1
				  WHERE id = '" . Filter::$id . "'
			  ")->run();
            }
        }
    }