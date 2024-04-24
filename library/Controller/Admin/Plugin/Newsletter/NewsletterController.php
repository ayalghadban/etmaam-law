<?php
    /**
     * NewsletterController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: NewsletterController.php, v1.00 5/18/2023 10:42 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin\Plugin\Newsletter;
    
    use Wojo\Core\Controller;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Language\Language;
    use Wojo\Plugin\Newsletter\Newsletter;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class NewsletterController extends Controller
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
            $this->view->data = $this->db->count(Newsletter::mTable)->run();
            
            $this->view->crumbs = ['admin', 'plugins', Language::$word->_PLG_NSL_TITLE];
            $this->view->caption = Language::$word->_PLG_NSL_TITLE;
            $this->view->title = Language::$word->_PLG_NSL_TITLE;
            $this->view->subtitle = null;
            $this->view->render('index', 'view/admin/plugins/newsletter/view/', true, 'view/admin/');
        }
        
        /**
         * action
         *
         * @return void
         */
        public function action(): void
        {
            $getAction = Validator::get('action');
            if ($getAction) {
                switch ($getAction) {
                    case 'export':
                        $this->export();
                        break;
                    
                    default:
                        Url::invalidMethod();
                        break;
                }
            }
        }
        
        /**
         * export
         *
         * @return void
         */
        private function export(): void
        {
            $result = $this->db->select(Newsletter::mTable, array('email', 'created'))->orderBy('created', 'DESC')->run('array');

            header('Pragma: no-cache');
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=Users.csv');
            
            $data = fopen('php://output', 'w');
            fputcsv($data, array(Language::$word->M_EMAIL, Language::$word->CREATED));

            if ($result) {
                foreach ($result as $row) {
                    fputcsv($data, $row);
                }
                fclose($data);
            }
        }
    }