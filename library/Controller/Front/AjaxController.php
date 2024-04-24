<?php
    /**
     * AjaxController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: AjaxController.php, v1.00 6/10/2023 5:55 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Front;
    
    use PHPMailer\PHPMailer\Exception;
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Mailer;
    use Wojo\Core\Session;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class AjaxController extends Controller
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
         * action
         *
         * @return void
         * @throws Exception
         */
        public function action(): void
        {
            $postAction = Validator::post('action');
            $getAction = Validator::get('action');
            
            if ($postAction or $getAction) {
                if ($postAction) {
                    if (IS_AJAX) {
                        switch ($postAction) {
                            // contact form
                            case 'contact':
                                IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->contact();
                                break;
                            
                            //clear console sessions
                            case 'debugSession':
                                Session::remove('debug-queries');
                                Session::remove('debug-warnings');
                                Session::remove('debug-errors');
                                print 'ok';
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
         * contact
         *
         * @return void
         * @throws Exception
         */
        private function contact(): void
        {
            $validate = Validator::run($_POST);
            $validate
                ->set('name', Language::$word->NAME)->required()->string()->min_len(2)->max_len(60)
                ->set('notes', Language::$word->MESSAGE)->required()->string(true, true)->min_len(3)->max_len(250)
                ->set('email', Language::$word->M_EMAIL)->required()->email()
                ->set('captcha', Language::$word->CAPTCHA)->required()->numeric()->equals(Session::get('wcaptcha'))->exact_len(5)
                ->set('agree', Language::$word->PRIVACY)->required()->numeric()
                ->set('subject', Language::$word->PRIVACY)->string()
                ->set('phone', Language::$word->PRIVACY)->string();
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $lg = Language::$lang;
                $tpl = $this->db->select(Content::eTable, array("body$lg  as ebody", "subject$lg as esubject"))->where('typeid', 'contact', '=')->first()->run();
                $mailer = Mailer::sendMail();
                
                $body = str_replace(array(
                    '[LOGO]',
                    '[EMAIL]',
                    '[NAME]',
                    '[MAILSUBJECT]',
                    '[PHONE]',
                    '[MESSAGE]',
                    '[IP]',
                    '[DATE]',
                    '[COMPANY]',
                    '[SITE_NAME]',
                    '[CEMAIL]',
                    '[FB]',
                    '[TW]',
                    '[SITEURL]'
                ), array(
                    $this->core->plogo,
                    $safe->email,
                    $safe->name,
                    $safe->subject,
                    $safe->phone,
                    $safe->notes,
                    Url::getIP(),
                    date('Y'),
                    $this->core->company,
                    $this->core->site_name,
                    $this->core->site_email,
                    $this->core->social->facebook,
                    $this->core->social->twitter,
                    SITEURL
                ), $tpl->ebody);
                
                $mailer->addReplyTo($safe->email, $safe->name);
                $mailer->setFrom($this->core->site_email, $this->core->company);
                $mailer->addAddress($this->core->site_email, $this->core->company);
                
                $mailer->isHTML();
                $mailer->Subject = $tpl->esubject;
                $mailer->Body = $body;
                
                if ($mailer->send()) {
                    $json = array(
                        'type' => 'success',
                        'title' => Language::$word->SUCCESS,
                        'message' => Language::$word->CF_OK
                    );
                } else {
                    $json = array(
                        'type' => 'error',
                        'title' => Language::$word->ERROR,
                        'message' => Language::$word->CF_ERR,
                    );
                }
                print json_encode($json);
            } else {
                Message::msgSingleStatus();
            }
        }
    }