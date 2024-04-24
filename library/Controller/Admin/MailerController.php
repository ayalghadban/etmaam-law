<?php
    /**
     * MailerController CLass
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: MailerController.php, v1.00 5/11/2023 6:27 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use PHPMailer\PHPMailer\Exception;
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Mailer;
    use Wojo\Core\Upload;
    use Wojo\Core\User;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class MailerController extends Controller
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
            $type = Validator::get('email') ? 'singleMail' : 'newsletter';
            $this->view->data = $this->db->select(Content::eTable)->where('typeid', $type, '=')->first()->run();
            
            $this->view->crumbs = ['admin', Language::$word->META_T24];
            $this->view->caption = Language::$word->META_T24;
            $this->view->title = Language::$word->META_T24;
            $this->view->subtitle = Language::$word->NL_INFO1;
            $this->view->render('mailer', 'view/admin/');
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
            if ($postAction) {
                if (IS_AJAX) {
                    switch ($postAction) {
                        case 'send':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->send();
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
         * send
         *
         * @return void
         * @throws Exception
         */
        private function send(): void
        {
            $validate = Validator::run($_POST);
            $validate
                ->set('subject', Language::$word->NL_SUBJECT)->required()->string()->min_len(3)->max_len(100)
                ->set('recipient', Language::$word->NL_RCPT)->required()->string()
                ->set('body', Language::$word->ET_DESC)->text('advanced');
            
            $safe = $validate->safe();
            
            $user_row = false;
            $row = false;
            
            $upl = Upload::instance(20971520, 'zip,jpg,pdf,doc,docx');
            
            if (count(Message::$msgs) === 0) {
                $to = $safe->recipient;
                $subject = $safe->subject;
                $body = Validator::cleanOut($safe->body);
                $numSent = 0;
                $failedRecipients = array();
                $core = $this->core;
                
                $mailer = Mailer::sendMail();
                $mailer->Subject = $subject;
                $mailer->setFrom($core->site_email, $core->company);
                $mailer->isHTML();
                
                if (array_key_exists('name', $_FILES)) {
                    $upl->process('attachment', UPLOADS . 'attachments/', 'ATT_');
                    $attachment = '<a href="' . UPLOADURL . 'attachments/' . $upl->fileInfo['fname'] . '">' . Language::$word->NL_ATTACH . '</a>';
                } else {
                    $attachment = '';
                }
                
                switch ($to) {
                    case 'all':
                        $user_row = $this->db->select(User::mTable, array('email', 'CONCAT(fname," ",lname) as name'))->where('active', 'y', '=')->where('type', 'member', '=')->run();
                        break;
                    
                    case 'free':
                        $user_row = $this->db->select(User::mTable, array('email', 'CONCAT(fname," ",lname) as name'))->where('membership_id', 1, '<')->where('type', 'member', '=')->run();
                        break;
                    
                    case 'paid':
                        $user_row = $this->db->select(User::mTable, array('email', 'CONCAT(fname," ",lname) as name'))->where('membership_id', 0, '>')->where('type', 'member', '=')->run();
                        break;
                    
                    case 'newsletter':
                        $user_row = $this->db->select(User::mTable, array('email', 'CONCAT(fname," ",lname) as name'))->where('newsletter', 1, '=')->where('type', 'member', '=')->run();
                        break;
                    
                    default:
                        $row = $this->db->select(User::mTable, array("email, CONCAT(fname,' ',lname) as name"))->where('email', "%$to%", 'LIKE')->first()->run();
                        break;
                }
                
                switch ($to) {
                    case 'all':
                    case 'free':
                    case 'paid':
                    case 'newsletter':
                        if ($user_row) {
                            foreach ($user_row as $row) {
                                $html[$row->email] = str_replace(array(
                                    '[LOGO]',
                                    '[NAME]',
                                    '[DATE]',
                                    '[COMPANY]',
                                    '[SITE_NAME]',
                                    '[ATTACHMENT]',
                                    '[FB]',
                                    '[TW]',
                                    '[CEMAIL]',
                                    '[SITEURL]'
                                ), array(
                                    $core->plogo,
                                    $row->name,
                                    date('Y'),
                                    $core->company,
                                    $core->site_name,
                                    $attachment,
                                    $core->social->facebook,
                                    $core->social->twitter,
                                    $core->site_email,
                                    SITEURL
                                ), $body);
                                
                                $mailer->Body = $html;
                                $mailer->addAddress($row->email, $row->name);
                                
                                try {
                                    $mailer->send();
                                    $numSent++;
                                } catch (Exception) {
                                    $failedRecipients[] = htmlspecialchars($row->email);
                                    $mailer->getSMTPInstance()->reset();
                                }
                                $mailer->clearAddresses();
                                $mailer->clearAttachments();
                            }
                            unset($row);
                        }
                        break;
                    
                    default:
                        if ($row) {
                            $newbody = str_replace(array(
                                '[LOGO]',
                                '[COMPANY]',
                                '[SITE_NAME]',
                                '[NAME]',
                                '[SITEURL]',
                                '[ATTACHMENT]',
                                '[FB]',
                                '[TW]',
                                '[CEMAIL]',
                                '[DATE]'
                            ), array(
                                $core->plogo,
                                $core->company,
                                $core->site_name,
                                $row->name,
                                SITEURL,
                                $attachment,
                                $core->social->facebook,
                                $core->social->twitter,
                                $core->site_email,
                                date('Y')
                            ), $body);
                            
                            try {
                                $mailer->addAddress($to, $row->name);
                                $mailer->Body = $newbody;
                                $mailer->send();
                                $numSent++;
                            } catch (Exception) {
                                $failedRecipients[] = htmlspecialchars($to);
                            }
                        }
                        break;
                }
                
                if ($numSent) {
                    $json['type'] = 'success';
                    $json['title'] = Language::$word->SUCCESS;
                    $json['message'] = $numSent . ' ' . Language::$word->NL_SENT;
                } else {
                    $json['type'] = 'error';
                    $json['title'] = Language::$word->ERROR;
                    $res = '<ul>';
                    foreach ($failedRecipients as $failed) {
                        $res .= '<li>' . $failed . '</li>';
                    }
                    $res .= '</ul>';
                    $json['message'] = Language::$word->NL_ALERT . $res;
                    
                    unset($failed);
                }
                print json_encode($json);
            } else {
                Message::msgSingleStatus();
            }
        }
    }