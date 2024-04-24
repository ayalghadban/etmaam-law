<?php
    /**
     * LoginController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: LoginController.php, v1.00 4/25/2023 1:45 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Front;
    
    use PHPMailer\PHPMailer\Exception;
    use Wojo\Auth\Auth;
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Mailer;
    use Wojo\Core\Session;
    use Wojo\Core\User;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Exception\NotFoundException;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class LoginController extends Controller
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
         * Index
         *
         * @return void
         * @throws FileNotFoundException
         */
        function index(): void
        {
            $this->view->title = Url::formatMeta(Language::$word->LOGIN, $this->core->company);
            $this->view->keywords = null;
            $this->view->description = null;
            $this->view->meta = null;
            $this->view->crumbs = null;
            
            $this->view->render('login', THEMEBASE);
        }
        
        /**
         * login
         *
         * @return void
         */
        public function login(): void
        {
            if (isset($_POST['username']) and isset($_POST['password'])) {
                $this->view->auth->login($_POST['username'], $_POST['password']);
            } else {
                Url::invalidMethod();
            }
        }
        
        /**
         * action
         *
         * @return void
         * @throws Exception
         * @throws NotFoundException
         */
        public function action(): void
        {
            $postAction = Validator::post('action');
            if ($postAction) {
                if (IS_AJAX) {
                    switch ($postAction) {
                        case 'register':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->register();
                            break;
                        
                        case 'reset':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->reset();
                            break;
                        
                        case 'password':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->password();
                            break;
                        
                        case 'check':
                            $this->_check();
                            break;
                        
                        default:
                            Url::invalidMethod();
                            break;
                    }
                } else {
                    Url::invalidMethod();
                }
            } else {
                Url::invalidMethod();
            }
        }
        
        /**
         * register
         *
         * @return void
         * @throws Exception
         * @throws NotFoundException
         */
        private function register(): void
        {
            $validate = Validator::run($_POST);
            $validate
                ->set('fname', Language::$word->M_FNAME)->required()->string()->min_len(2)->max_len(60)
                ->set('lname', Language::$word->M_LNAME)->required()->string()->min_len(2)->max_len(60)
                ->set('password', Language::$word->M_PASSWORD)->required()->string()->min_len(8)->max_len(16)
                ->set('email', Language::$word->M_EMAIL)->required()->email()
                ->set('agree', Language::$word->PRIVACY)->required()->numeric()
                ->set('captcha', Language::$word->CAPTCHA)->required()->numeric()->equals(Session::get('wcaptcha'))->exact_len(5);
            
            if ($this->core->enable_tax) {
                $validate
                    ->set('address', Language::$word->M_ADDRESS)->required()->string()->min_len(3)->max_len(80)
                    ->set('city', Language::$word->M_CITY)->required()->string()->min_len(2)->max_len(60)
                    ->set('zip', Language::$word->M_ZIP)->required()->string()->min_len(3)->max_len(30)
                    ->set('state', Language::$word->M_STATE)->required()->string()->min_len(2)->max_len(60)
                    ->set('country', Language::$word->M_COUNTRY)->required()->string()->exact_len(2);
            }
            
            $safe = $validate->safe();
            
            if (strlen($safe->email)) {
                if ($this->auth::emailExists($safe->email)) {
                    Message::$msgs['email'] = Language::$word->M_EMAIL_R2;
                }
            }
            
            Content::verifyCustomFields('profile');
            if (count(Message::$msgs) === 0) {
                $hash = Auth::doHash($safe->password);
                $username = Utility::randomString();
                $lg = Language::$lang;
                
                if ($this->core->reg_verify == 1) {
                    $active = 't';
                } elseif ($this->core->auto_verify == 0) {
                    $active = 'n';
                } else {
                    $active = 'y';
                }
                
                $data = array(
                    'username' => $username,
                    'email' => $safe->email,
                    'lname' => $safe->lname,
                    'fname' => $safe->fname,
                    'hash' => $hash,
                    'type' => 'member',
                    'token' => Utility::randNumbers(),
                    'active' => $active,
                    'userlevel' => 1,
                );
                
                if ($this->core->enable_tax) {
                    $data['address'] = $safe->address;
                    $data['city'] = $safe->city;
                    $data['state'] = $safe->state;
                    $data['zip'] = $safe->zip;
                    $data['country'] = $safe->country;
                }
                
                $last_id = $this->db->insert(User::mTable, $data)->run();
                
                // Start Custom Fields
                $fl_array = Utility::array_key_exists_wildcard($_POST, 'custom_*', 'key-value');
                $dataArray = array();
                if ($fl_array) {
                    $fields = $this->db->select(Content::cfTable)->run();
                    foreach ($fields as $row) {
                        $dataArray[] = array(
                            'user_id' => $last_id,
                            'field_id' => $row->id,
                            'field_name' => $row->name,
                            'section' => 'profile',
                        );
                    }
                    $this->db->batch(Content::cfdTable, $dataArray)->run();
                    
                    foreach ($fl_array as $key => $val) {
                        $cfdata['field_value'] = Validator::sanitize($val);
                        $this->db->update(Content::cfdTable, $cfdata)->where('user_id', $last_id, '=')->where('field_name', str_replace('custom_', '', $key), '=')->run();
                    }
                }
                
                $mailer = Mailer::sendMail();
                
                if ($this->core->reg_verify == 1) {
                    $message = Language::$word->M_INFO7;
                    $json['redirect'] = SITEURL;
                    
                    $tpl = $this->db->select(Content::eTable, array("body$lg as body", "subject$lg as subject"))->where('typeid', 'regMail', '=')->first()->run();
                    
                    $body = str_replace(array(
                        '[LOGO]',
                        '[DATE]',
                        '[COMPANY]',
                        '[USERNAME]',
                        '[PASSWORD]',
                        '[LINK]',
                        '[FB]',
                        '[TW]',
                        '[CEMAIL]',
                        '[SITEURL]'
                    ), array(
                        $this->core->plogo,
                        date('Y'),
                        $this->core->company,
                        $safe->email,
                        $safe->password,
                        Url::url($this->core->system_slugs->activate[0]->{'slug' . Language::$lang}, '?token=' . $data['token'] . '&email=' . $data['email']),
                        $this->core->social->facebook,
                        $this->core->social->twitter,
                        $this->core->site_email,
                        SITEURL
                    ), $tpl->body);
                    
                } elseif ($this->core->auto_verify == 0) {
                    $message = Language::$word->M_INFO7;
                    $json['redirect'] = SITEURL;
                    
                    $tpl = $this->db->select(Content::eTable, array("body$lg as body", "subject$lg as subject"))->where('typeid', 'regMailPending', '=')->first()->run();
                    
                    $body = str_replace(array(
                        '[LOGO]',
                        '[DATE]',
                        '[COMPANY]',
                        '[USERNAME]',
                        '[PASSWORD]',
                        '[FB]',
                        '[TW]',
                        '[CEMAIL]',
                        '[SITEURL]'
                    ), array(
                        $this->core->plogo,
                        date('Y'),
                        $this->core->company,
                        $safe->email,
                        $safe->password,
                        $this->core->social->facebook,
                        $this->core->social->twitter,
                        $this->core->site_email,
                        SITEURL
                    ), $tpl->body);
                    
                } else {
                    //login user
                    $this->view->auth->login($safe->email, $safe->password, false);
                    $message = Language::$word->M_INFO8;
                    $json['redirect'] = Url::url($this->core->system_slugs->account[0]->{'slug' . Language::$lang});
                    
                    $tpl = $this->db->select(Content::eTable, array("body$lg as body", "subject$lg as subject"))->where('typeid', 'welcomeEmail', '=')->first()->run();
                    
                    $body = str_replace(array(
                        '[LOGO]',
                        '[DATE]',
                        '[COMPANY]',
                        '[USERNAME]',
                        '[PASSWORD]',
                        '[LINK]',
                        '[FB]',
                        '[TW]',
                        '[CEMAIL]',
                        '[SITEURL]'
                    ), array(
                        $this->core->plogo,
                        date('Y'),
                        $this->core->company,
                        $safe->email,
                        $safe->password,
                        Url::url(''),
                        $this->core->social->facebook,
                        $this->core->social->twitter,
                        $this->core->site_email,
                        SITEURL
                    ), $tpl->body);
                    
                }
                
                $mailer->Subject = $tpl->subject;
                $mailer->Body = $body;
                $mailer->setFrom($this->core->site_email, $this->core->company);
                $mailer->addAddress($data['email'], $data['fname'] . ' ' . $data['lname']);
                $mailer->isHTML();
                $mailer->send();
                
                if ($this->core->notify_admin) {
                    $tpl = $this->db->select(Content::eTable, array("body$lg as body", "subject$lg as subject"))->where('typeid', 'notifyAdmin', '=')->first()->run();
                    
                    $body = str_replace(array(
                        '[LOGO]',
                        '[DATE]',
                        '[COMPANY]',
                        '[USERNAME]',
                        '[FB]',
                        '[TW]',
                        '[CEMAIL]',
                        '[SITEURL]'
                    ), array(
                        $this->core->plogo,
                        date('Y'),
                        $this->core->company,
                        $safe->email,
                        $this->core->social->facebook,
                        $this->core->social->twitter,
                        $this->core->site_email,
                        SITEURL
                    ), $tpl->body);
                    
                    $mailer->Subject = $tpl->subject;
                    $mailer->Body = $body;
                    $mailer->setFrom($this->core->site_email, $this->core->company);
                    $mailer->addAddress($this->core->site_email, $this->core->company);
                    $mailer->isHTML();
                    $mailer->send();
                }
                
                if ($this->db->affected()) {
                    $json = array(
                        'type' => 'success',
                        'title' => Language::$word->SUCCESS,
                        'message' => $message
                    );
                } else {
                    $json = array(
                        'type' => 'error',
                        'title' => Language::$word->ERROR,
                        'message' => Language::$word->M_INFO11,
                    );
                }
                print json_encode($json);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * reset
         *
         * @return void
         * @throws Exception
         */
        private function reset(): void
        {
            $validate = Validator::run($_POST);
            
            $validate->set('email', Language::$word->M_EMAIL)->required()->email();
            $safe = $validate->safe();
            
            if (strlen($safe->email)) {
                $row = $this->db->select(User::mTable, array('email', 'fname', 'lname', 'id'))
                    ->where('email', $safe->email, '=')
                    ->where('active', 'y', '=')
                    ->first()->run();
                if (!$row) {
                    Message::$msgs['email'] = Language::$word->M_EMAIL_R4;
                }
            }
            
            if (count(Message::$msgs) === 0) {
                $lg = Language::$lang;
                
                $row = $this->db->select(User::mTable, array('email', 'fname', 'lname', 'id', 'type'))
                    ->where('email', $safe->email, '=')
                    ->where('active', 'y', '=')
                    ->first()->run();
                
                $token = substr(md5(uniqid(rand(), true)), 0, 10);
                $template = ($row->type == 'member') ? 'userPassReset ' : 'adminPassReset';
                
                $mailer = Mailer::sendMail();
                $tpl = $this->db->select(Content::eTable, array("body$lg as body", "subject$lg as subject"))->where('typeid', $template, '=')->first()->run();
                
                $body = str_replace(array(
                    '[LOGO]',
                    '[NAME]',
                    '[DATE]',
                    '[COMPANY]',
                    '[SITE_NAME]',
                    '[LINK]',
                    '[IP]',
                    '[FB]',
                    '[TW]',
                    '[CEMAIL]',
                    '[SITEURL]'
                ), array(
                    $this->core->plogo,
                    $row->fname . ' ' . $row->lname,
                    date('Y'),
                    $this->core->company,
                    $this->core->site_name,
                    Url::url('/password', $token),
                    Url::getIP(),
                    $this->core->social->facebook,
                    $this->core->social->twitter,
                    $this->core->site_email,
                    SITEURL
                ), $tpl->body);
                
                $mailer->setFrom($this->core->site_email, $this->core->company);
                $mailer->addAddress($row->email, $row->fname . ' ' . $row->lname);
                
                $mailer->isHTML();
                $mailer->Subject = $tpl->subject;
                $mailer->Body = $body;
                
                $this->db->update(User::mTable, array('token' => $token))->where('id', $row->id, '=')->run();
                
                if ($mailer->send()) {
                    $json = array(
                        'type' => 'success',
                        'title' => Language::$word->SUCCESS,
                        'message' => Language::$word->M_PASSWORD_RES_D
                    );
                    print json_encode($json);
                }
            } else {
                $json = array(
                    'type' => 'error',
                    'title' => Language::$word->ERROR,
                    'message' => Language::$word->M_EMAIL_R5
                );
                print json_encode($json);
            }
        }
        
        /**
         * password
         *
         * @return void
         */
        private function password(): void
        {
            $validate = Validator::run($_POST);
            
            $validate
                ->set('token', 'Token')->required()->string()
                ->set('password', Language::$word->NEWPASS)->required()->string()->min_len(8)->max_len(12);
            
            $safe = $validate->safe();
            
            if (!$row = $this->db->select(User::mTable, array('id', 'type'))->where('token', $safe->token, '=')->first()->run()) {
                Message::$msgs['token'] = 'Invalid Token.';
            }
            
            if (count(Message::$msgs) === 0) {
                $data = array(
                    'hash' => Auth::doHash($safe->password),
                    'token' => 0,
                );
                
                $this->db->update(User::mTable, $data)->where('id', $row->id, '=')->run();
                $json = array(
                    'type' => 'success',
                    'title' => Language::$word->SUCCESS,
                    'message' => Language::$word->M_PASSUPD_OK2,
                    'redirect' => Url::url($this->core->system_slugs->login[0]->{'slug' . Language::$lang})
                );
                print json_encode($json);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * _check
         *
         * @return void
         */
        private function _check(): void
        {
            $type = $this->db->select(User::mTable, array('id'))->where('sesid', $this->auth->sesid, '=')->first()->run() ? 'success' : 'error';
            
            $json['type'] = $type;
            print json_encode($json);
        }
    }