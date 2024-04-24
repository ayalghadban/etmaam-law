<?php
    /**
     * SystemController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: SystemController.php, v1.00 4/30/2023 10:08 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use PDO;
    use Wojo\Core\Controller;
    use Wojo\Database\Database;
    use Wojo\Database\DatabaseTools;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Language\Language;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class SystemController extends Controller
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
            $this->view->crumbs = ['admin', Language::$word->M_TITLE1];
            $this->view->title = Language::$word->SYS_TITLE;
            $this->view->caption = Language::$word->SYS_TITLE;
            $this->view->subtitle = str_replace('[VER]', $this->core->wojov, Language::$word->SYS_INFO);
            
            $_oSTH = Database::Go()->prepare('SHOW TABLES FROM ' . DB_DATABASE);
            $_oSTH->execute();
            $this->view->data = $_oSTH->fetchAll(PDO::FETCH_COLUMN);
            $this->view->render('system', 'view/admin/');
        }
        
        /**
         * action
         *
         * @return void
         */
        public function action(): void
        {
            if (IS_AJAX and Validator::post('action') == 'optimize') {
                $json['type'] = 'success';
                print json_encode($json);
                if(!IS_DEMO) {
                    $json['html'] = DatabaseTools::optimize();
                }
            } else {
                Url::invalidMethod();
            }
        }
    }