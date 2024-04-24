<?php
    /**
     * BackupController
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: BackupController.php, v1.00 5/11/2023 10:14 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use Wojo\Core\Controller;
    use Wojo\Core\Core;
    use Wojo\Database\DatabaseTools;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Exception\NotFoundException;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class BackupController extends Controller
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
            $this->view->dbdir = UPLOADS . 'backups/';
            $this->view->data = File::findFiles($this->view->dbdir, array('fileTypes' => array('sql'), 'returnType' => 'fileOnly'));
            
            $this->view->crumbs = ['admin', Language::$word->DBM_TITLE];
            $this->view->caption = Language::$word->DBM_TITLE;
            $this->view->title = Language::$word->DBM_TITLE;
            $this->view->subtitle = Language::$word->DBM_INFO;
            $this->view->render('backup', 'view/admin/');
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
            $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
            if ($postAction) {
                if (IS_AJAX) {
                    switch ($postAction) {
                        case 'backup':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->backup();
                            break;
                        
                        case 'restore':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->restore($title);
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
         * backup
         *
         * @return void
         * @throws FileNotFoundException
         */
        private function backup(): void
        {
            if ($sql = DatabaseTools::fetch()) {
                $fname = UPLOADS . 'backups/';
                $fname .= date(DatabaseTools::suffix);
                $fname .= '.sql';
                
                DatabaseTools::save($fname, $sql, false);
                
                $data['backup'] = basename($fname);
                $this->db->update(Core::sTable, $data)->where('id', 1, '=')->run();
                
                $this->view->backup = $data['backup'];
                $this->view->dbdir = UPLOADS . 'backups/';
                Message::msgModalReply($this->db->affected(), 'success', Language::$word->DBM_BKP_OK, $this->view->snippet('loadDatabaseBackup', 'view/admin/snippets/'));
            }
        }
        
        /**
         * restore
         *
         * @param string $title
         * @return void
         */
        private function restore(string $title): void
        {
            DatabaseTools::doRestore($title);
        }
    }