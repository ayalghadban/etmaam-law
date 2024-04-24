<?php
    /**
     * GatewayController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: GatewayController.php, v1.00 4/30/2023 9:15 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use Wojo\Core\Controller;
    use Wojo\Core\Core;
    use Wojo\Core\Filter;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class GatewayController extends Controller
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
            $this->view->data = $this->db->select(Core::gTable)->run();
            
            $this->view->crumbs = ['admin', Language::$word->M_TITLE1];
            $this->view->caption = Language::$word->GW_TITLE;
            $this->view->title = Language::$word->META_T22;
            $this->view->subtitle = Language::$word->GW_SUB;
            $this->view->render('gateway', 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'gateways', 'edit'];
            $this->view->title = Language::$word->GW_TITLE1;
            $this->view->caption = Language::$word->GW_TITLE1;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Core::gTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->render('gateway', 'view/admin/');
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
                        
                        case 'status':
                            IS_DEMO ? print '0' : $this->status();
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
                ->set('displayname', Language::$word->GW_NAME)->required()->string()->min_len(3)->max_len(60)
                ->set('extra', Language::$word->GW_NAME)->required()->string()
                ->set('extra2', Language::$word->GW_NAME)->string()
                ->set('extra3', Language::$word->GW_NAME)->string()
                ->set('live', Language::$word->GW_LIVE)->numeric()
                ->set('active', Language::$word->ACTIVE)->numeric()
                ->set('id', 'ID')->required()->numeric();
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data = array(
                    'displayname' => $safe->displayname,
                    'extra' => $safe->extra,
                    'extra2' => $safe->extra2,
                    'extra3' => $safe->extra3,
                    'live' => $safe->live,
                    'active' => $safe->active,
                );
                
                $this->db->update(Core::gTable, $data)->where('id', Filter::$id, '=')->run();
                Message::msgReply($this->db->affected(), 'success', Message::formatSuccessMessage($data['displayname'], Language::$word->GW_UPDATED));
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * status
         *
         * @return void
         */
        private function status(): void
        {
            if ($this->view->auth->checkAcl('owner')) {
                $this->db->update(Core::gTable, array('active' => intval($_POST['active'])))->where('id', Filter::$id, '=')->run();
            }
        }
    }