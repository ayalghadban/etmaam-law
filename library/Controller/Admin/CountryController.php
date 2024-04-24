<?php
    /**
     * CountryController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: CountryController.php, v1.00 5/12/2023 9:46 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class CountryController extends Controller
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
            $this->view->data = $this->db->select(Content::cTable)->orderBy('sorting', 'DESC')->run();
            
            $this->view->crumbs = ['admin', Language::$word->CNT_TITLE];
            $this->view->caption = Language::$word->CNT_TITLE;
            $this->view->title = Language::$word->CNT_TITLE;
            $this->view->subtitle = Language::$word->CNT_INFO;
            $this->view->render('country', 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'countries', 'edit'];
            $this->view->title = Language::$word->CNT_EDIT;
            $this->view->caption = Language::$word->CNT_EDIT;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Content::cTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->render('country', 'view/admin/');
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
                        case 'update':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->process();
                            break;
                        
                        case 'tax':
                            IS_DEMO ? print json_encode(['title' => '1.00']) : $this->tax();
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
                ->set('name', Language::$word->NAME)->required()->string()->min_len(2)->max_len(60)
                ->set('abbr', Language::$word->CNT_ABBR)->required()->string()->uppercase()->exact_len(2)
                ->set('active', Language::$word->STATUS)->required()->numeric()
                ->set('home', Language::$word->DEFAULT)->required()->numeric()
                ->set('sorting', Language::$word->SORTING)->numeric()
                ->set('vat', Language::$word->TRX_TAX)->required()->float()
                ->set('id', 'ID')->required()->numeric();
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data = array(
                    'name' => $safe->name,
                    'abbr' => $safe->abbr,
                    'sorting' => $safe->sorting,
                    'home' => $safe->home,
                    'active' => $safe->active,
                    'vat' => $safe->vat,
                );
                
                if ($data['home'] == 1) {
                    $this->db->rawQuery('UPDATE `' . Content::cTable . '` SET `home`= ?;', array(0))->run();
                }
                
                $this->db->update(Content::cTable, $data)->where('id', Filter::$id, '=')->run();
                Message::msgReply($this->db->affected(), 'success', Message::formatSuccessMessage($data['name'], Language::$word->CNT_UPDATED));
                Logger::writeLog(Message::formatSuccessMessage($data['name'], Language::$word->CNT_UPDATED));
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * tax
         *
         * @return void
         */
        private function tax(): void
        {
            $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
            if (strlen($title) === 0) {
                print '0.000';
                exit;
            }
            $data['vat'] = Validator::sanitize($_POST['title'], 'float');
            $this->db->update(Content::cTable, $data)->where('id', Filter::$id, '=')->run();
            
            print json_encode(['title' => $title]);
        }
    }