<?php
    /**
     * ProfileController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version : ProfileController.php, v1.00 4/29/2023 8:34 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use Wojo\Auth\Auth;
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Session;
    use Wojo\Core\User;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class ProfileController extends Controller
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
            $this->view->data = $this->db->select(User::mTable)->where('id', $this->view->auth->uid, '=')->first()->run();
            $this->view->custom_fields = Content::renderCustomFields($this->view->auth->uid, 'profile');
            
            $this->view->crumbs = ['admin', Language::$word->M_TITLE];
            $this->view->caption = Language::$word->META_T1;
            $this->view->title = Language::$word->M_TITLE;
            $this->view->subtitle = null;
            $this->view->render('account', 'view/admin/');
        }
        
        /**
         * password
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function password(): void
        {
            $this->view->crumbs = ['admin', Language::$word->M_SUB2];
            $this->view->caption = Language::$word->M_SUB2;
            $this->view->title = Language::$word->M_SUB2;
            $this->view->subtitle = null;
            $this->view->render('password', 'view/admin/');
            
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
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->updateAccount();
                            break;
                        
                        case 'password':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->updatePassword();
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
         * updateAccount
         *
         * @return void
         */
        public function updateAccount(): void
        {
            $validate = Validator::run($_POST);
            $validate
                ->set('fname', Language::$word->M_FNAME)->required()->string()->min_len(2)->max_len(60)
                ->set('lname', Language::$word->M_LNAME)->required()->string()->min_len(2)->max_len(60)
                ->set('email', Language::$word->M_EMAIL)->required()->email();
            
            $safe = $validate->safe();
            
            $thumb = File::upload('avatar', 512000, 'png,jpg,jpeg');
            
            Content::verifyCustomFields('profile');
            
            if (count(Message::$msgs) === 0) {
                $data = array(
                    'email' => $safe->email,
                    'lname' => $safe->lname,
                    'fname' => $safe->fname
                );
                
                if (array_key_exists('avatar', $_FILES)) {
                    $thumbPath = UPLOADS . '/avatars/';
                    if (Auth::$udata->avatar != '') {
                        File::deleteFile(UPLOADS . '/avatars/' . Auth::$udata->avatar);
                    }
                    $result = File::process($thumb, $thumbPath, 'AVT_');
                    $this->view->auth->avatar = Session::set('avatar', $result['fname']);
                    $data['avatar'] = $result['fname'];
                }
                
                $this->db->update(User::mTable, $data)->where('id', $this->view->auth->uid, '=')->run();
                if ($this->db->affected()) {
                    $this->view->auth->fname = Session::set('fname', $data['fname']);
                    $this->view->auth->lname = Session::set('lname', $data['lname']);
                    $this->view->auth->email = Session::set('email', $data['email']);
                }
                
                // Start Custom Fields
                $fl_array = Utility::array_key_exists_wildcard($_POST, 'custom_*', 'key-value');
                if ($fl_array) {
                    foreach ($fl_array as $key => $val) {
                        $cfdata['field_value'] = Validator::sanitize($val);
                        $this->db->update(Content::cfdTable, $cfdata)->where('id', $this->view->auth->uid, '=')->where('field_name', str_replace('custom_', '', $key), '=')->run();
                    }
                }
                
                $message = str_replace('[NAME]', '', Language::$word->M_UPDATED);
                Message::msgReply(true, 'success', $message);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * updatePassword
         *
         * @return void
         */
        public function updatePassword(): void
        {
            
            $validate = Validator::run($_POST);
            $validate->set('password', Language::$word->NEWPASS)->required()->string()->min_len(6)->max_len(20);
            $validate->set('password2', Language::$word->CONPASS)->required()->string()->equals($_POST['password'])->min_len(6)->max_len(20);
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data['hash'] = Auth::doHash($safe->password);
                
                $this->db->update(User::mTable, $data)->where('id', $this->view->auth->uid, '=')->run();
                Message::msgReply($this->db->affected(), 'success', Language::$word->M_PASSUPD_OK);
            } else {
                Message::msgSingleStatus();
            }
        }
    }