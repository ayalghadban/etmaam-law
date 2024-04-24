<?php
    /**
     * RoleController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: RoleController.php, v1.00 4/29/2023 7:14 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use Wojo\Container\Container;
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Core\User;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class RoleController extends Controller
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
            $this->view->data = $this->db->select(User::rTable)->run();
            
            $this->view->crumbs = ['admin', Language::$word->M_TITLE1];
            $this->view->caption = Language::$word->M_TITLE1;
            $this->view->title = Language::$word->M_TITLE1;
            $this->view->subtitle = Language::$word->M_SUB3;
            $this->view->render('role', 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'permissions', Language::$word->M_TITLE2];
            $this->view->title = Language::$word->M_TITLE2;
            $this->view->caption = Language::$word->M_TITLE2;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(User::rTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->subtitle = str_replace('[ROLE]', '<span class="wojo mini transparent label">' . $row->name . '</span>', Language::$word->M_SUB4);
                $this->view->subtitle .= ($row->code != 'owner') ? ' ' . Language::$word->M_INFO : null;
                
                Container::$namespace = "\\Wojo\\Core\\";
                $this->view->role = $row;
                $this->view->result = Utility::groupToLoop(Container::User()->getPrivileges($this->view->matches), 'type');
                $this->view->render('role', 'view/admin/');
            }
        }
        
        /**
         * action
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function action(): void
        {
            $postAction = Validator::post('action');
            if ($postAction) {
                if (IS_AJAX) {
                    switch ($postAction) {
                        case 'edit':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->process();
                            break;
                        
                        case 'role':
                            IS_DEMO ? print '0' : $this->role();
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
                        case 'edit':
                            $this->view->data = $this->db->select(User::rTable)->where('id', Filter::$id, '=')->first()->run();
                            $this->view->render('editRole', 'view/admin/snippets/', false);
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
                ->set('name', Language::$word->NAME)->required()->string()->string()->min_len(4)->max_len(20)
                ->set('description', Language::$word->DESCRIPTION)->required()->string()->min_len(10)->max_len(150);
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data = array(
                    'name' => $safe->name,
                    'description' => $safe->description
                );
                
                $this->db->update(User::rTable, $data)->where('id', Filter::$id, '=')->run();
                Message::msgModalReply($this->db->affected(), 'success', Language::$word->M_INFO2, Validator::truncate($data['description'], 100));
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * role
         *
         * @return void
         */
        private function role(): void
        {
            if ($this->view->auth->checkAcl('owner')) {
                $this->db->update(User::rpTable, array('active' => intval($_POST['active'])))->where('id', Filter::$id, '=')->run();
            }
        }
    }