<?php
    /**
     * FileManagerController CLass
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: FileManagerController.php, v1.00 5/11/2023 1:14 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use Exception;
    use Wojo\Core\Controller;
    use Wojo\Core\Upload;
    use Wojo\Debug\Debug;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\File\File;
    use Wojo\Image\Image;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class FileManagerController extends Controller
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
            $this->view->crumbs = ['admin', Language::$word->META_T20];
            $this->view->caption = Language::$word->META_T20;
            $this->view->title = Language::$word->META_T20;
            $this->view->subtitle = [];
            $this->view->render('manager', 'view/admin/');
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
                        case 'upload':
                            IS_DEMO ? Message::msgReply(true, 'success', Language::$word->PROCESS_ERR_DEMO) : $this->upload();
                            break;
                        
                        case 'folder':
                            IS_DEMO ? Message::msgReply(true, 'success', Language::$word->PROCESS_ERR_DEMO) : $this->folder();
                            break;
                        
                        case 'delete':
                            IS_DEMO ? Message::msgReply(true, 'success', Language::$word->PROCESS_ERR_DEMO) : $this->delete();
                            break;
                        
                        case 'unzip':
                            IS_DEMO ? Message::msgReply(true, 'success', Language::$word->PROCESS_ERR_DEMO) : $this->unzip();
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
                        case 'files':
                            $this->files();
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
         * upload
         *
         * @return void
         */
        private function upload(): void
        {
            if (array_key_exists('file', $_FILES)) {
                $dir = File::validateDirectory(UPLOADS, Validator::post('dir')) . '/';
                $upl = Upload::instance($this->core->file_size, $this->core->file_ext);
                $upl->process('file', $dir, false, $_FILES['file']['name'], false);
                
                if (count(Message::$msgs) === 0) {
                    $img = new Image($dir . $upl->fileInfo['fname']);
                    if ($img->getSourceWidth()) {
                        try {
                            $img->resizeToWidth($this->core->img_w)->save($dir . $upl->fileInfo['fname']);
                            $img->resizeToWidth($this->core->thumb_w)->save(UPLOADS . 'thumbs/' . $upl->fileInfo['fname']);
                            
                            rename($dir . $upl->fileInfo['fname'], $dir . str_replace(' ', '', $upl->fileInfo['fname']));
                            rename(UPLOADS . 'thumbs/' . $upl->fileInfo['fname'], UPLOADS . 'thumbs/' . str_replace(' ', '', $upl->fileInfo['fname']));
                        } catch (Exception $e) {
                            Debug::addMessage('errors', '<i>Error</i>', $e->getMessage(), 'session');
                        }
                        $json['filename'] = UPLOADURL . Validator::post('dir') . '/' . str_replace(' ', '', $upl->fileInfo['fname']);
                    } else {
                        $json['filename'] = ADMINVIEW . 'images/mime/' . $upl->fileInfo['ext'] . '.svg';
                    }
                    $json['type'] = 'success';
                } else {
                    $json['type'] = 'error';
                    $json['filename'] = '';
                    $json['message'] = Message::$msgs['name'];
                }
                print json_encode($json);
            }
        }
        
        /**
         * delete
         *
         * @return void
         */
        private function delete(): void
        {
            if (isset($_POST['items'])) {
                foreach ($_POST['items'] as $item) {
                    File::deleteMulti(UPLOADS . $item);
                    File::deleteMulti(UPLOADS . 'thumbs/' . $item);
                }
                $json['type'] = 'success';
                print json_encode($json);
            }
        }
        
        /**
         * folder
         *
         * @return void
         */
        private function folder(): void
        {
            if (isset($_POST['name'])) {
                if (File::makeDirectory(UPLOADS . Validator::sanitize($_POST['dir'] . '/' . $_POST['name'], 'file'))) {
                    $json['type'] = 'success';
                } else {
                    $json['error'] = 'error';
                }
                print json_encode($json);
            }
        }
        
        /**
         * unzip
         *
         * @return void
         */
        private function unzip(): void
        {
            if (isset($_POST['item'])) {
                $dir = pathinfo(UPLOADS . $_POST['item']);
                if (File::unzip(UPLOADS . $_POST['item'], $dir['dirname'])) {
                    $json['type'] = 'success';
                } else {
                    $json['error'] = 'error';
                }
                print json_encode($json);
            }
        }
        
        /**
         * files
         *
         * @return void
         */
        private function files(): void
        {
            $include = null;
            if ($type = Validator::notEmptyGet('exts')) {
                $include = match ($type) {
                    'doc' => array(
                        'include' => array('txt', 'doc', 'docx', 'pdf', 'xls', 'xlsx', 'css', 'nfo',),
                    ),
                    'pic' => array(
                        'include' => array('jpg', 'jpeg', 'svg', 'bmp', 'webp', 'png'),
                    ),
                    'vid' => array(
                        'include' => array('mp4', 'avi', 'sfw', 'webm', 'ogv', 'mov'),
                    ),
                    'aud' => array('include' => array('mp3', 'wav')),
                    default => null,
                };
            }
            
            $result = File::scanDirectory(File::validateDirectory(UPLOADS, Validator::get('dir')), $include, Validator::get('sorting'));
            print json_encode($result);
        }
    }