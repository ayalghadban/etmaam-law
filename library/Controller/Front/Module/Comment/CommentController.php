<?php
    
    /**
     * CommentController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: CommentController.php, v1.00 6/19/2023 10:50 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Front\Module\Comment;
    
    use PHPMailer\PHPMailer\Exception;
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Core\Mailer;
    use Wojo\Core\Session;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Exception\NotFoundException;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Module\Comment\Comment;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class CommentController extends Controller
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
         * @throws FileNotFoundException
         * @throws NotFoundException
         */
        public function action(): void
        {
            $postAction = Validator::post('action');
            $getAction = Validator::get('action');
            if ($postAction or $getAction) {
                if ($postAction) {
                    if (IS_AJAX) {
                        switch ($postAction) {
                            case 'comment':
                            case 'reply':
                                IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->comment();
                                break;
                            
                            case 'vote':
                                $this->vote();
                                break;
                            
                            case 'delete':
                                $this->_delete();
                                IS_DEMO ? Message::msgReply(true, 'success', Language::$word->PROCESS_ERR_DEMO) : $this->_delete();
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
                        case 'load':
                            $this->load();
                            break;
                        
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
         * comment
         *
         * @return void
         * @throws Exception
         * @throws FileNotFoundException
         * @throws NotFoundException
         */
        private function comment(): void
        {
            $validate = Validator::run($_POST);
            $validate
                ->set('username', Language::$word->NAME)->required()->string()
                ->set('message', Language::$word->MESSAGE)->required()->string(true, true)
                ->set('parent_id', 'Invalid ID detected')->required()->numeric()
                ->set('url', 'URL')->required()->path()
                ->set('type', 'TYPE')->string()
                ->set('section', 'Section')->required()->string()
                ->set('id', 'Invalid ID detected')->required()->numeric();
            
            $settings = Comment::settings();
            if ($settings->show_captcha) {
                if ($_POST['action'] != 'reply') {
                    if (Session::get('wcaptcha') != $_POST['captcha']) {
                        Message::$msgs['captcha'] = Language::$word->CAPTCHA;
                    }
                }
            }
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data = array(
                    'comment_id' => $safe->id,
                    'user_id' => ($this->auth->logged_in) ? $this->auth->uid : 0,
                    'parent_id' => $safe->parent_id,
                    'username' => ($this->auth->logged_in) ? '' : $safe->username,
                    'section' => ($this->core->pageslug == $safe->section) ? 'page' : $this->core->moddir[$safe->section],
                    'body' => Validator::censored($safe->message, $settings->blacklist_words),
                    'active' => ($settings->auto_approve) ? 1 : 0,
                );
                
                $last_id = $this->db->insert(Comment::mTable, $data)->run();
                
                // Set shop product ratings
                if ($safe->id == 0 and $data['section'] == 'shop') {
                    $this->db->rawQuery('UPDATE `mod_shop` SET `ratings` = `ratings` + 1, `likes` = `likes` + ' . intval($_POST['star']) . ' WHERE `id` = ?', array($safe->parent_id))->run();
                }
                
                if ($settings->auto_approve) {
                    $message = Language::$word->_MOD_CM_MSGOK1;
                    $this->view->data = Comment::singleComment($last_id);
                    $this->view->settings = $settings;
                    $json['html'] = $this->view->snippet('loadComment', 'view/front/modules/comments/snippets/');
                } else {
                    $message = Language::$word->_MOD_CM_MSGOK2;
                }
                $json = array(
                    'type' => 'success',
                    'title' => Language::$word->SUCCESS,
                    'message' => $message,
                );
                print json_encode($json);
                
                if ($settings->notify_new) {
                    $lg = Language::$lang;
                    $user = ($this->auth->logged_in) ? $this->auth->name : $safe->username;
                    $mailer = Mailer::sendMail();
                    
                    $tpl = $this->db->select(Content::eTable, array("body$lg as body", "subject$lg as subject"))->where('typeid', 'newComment', '=')->first()->run();
                    $body = str_replace(array(
                        '[NAME]',
                        '[PAGEURL]',
                        '[MESSAGE]',
                        '[IP]',
                        '[COMPANY]',
                        '[SITE_NAME]',
                        '[FB]',
                        '[TW]',
                        '[CEMAIL]',
                        '[DATE]',
                        '[SITEURL]'
                    ), array(
                        ($this->auth->logged_in) ? $this->auth->name : $safe->username,
                        SITEURL . $safe->url,
                        $data['body'],
                        Url::getIP(),
                        $this->core->company,
                        $this->core->site_name,
                        $this->core->social->facebook,
                        $this->core->social->twitter,
                        $this->core->site_email,
                        date('Y'),
                        SITEURL
                    ), $tpl->body);
                    
                    $mailer->setFrom($this->core->site_email, $this->core->company);
                    $mailer->addAddress($this->core->site_email, $user);
                    
                    $mailer->isHTML();
                    $mailer->Subject = $tpl->subject;
                    $mailer->Body = $body;
                    
                    $mailer->send();
                }
                
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * load
         *
         * @return void
         * @throws FileNotFoundException
         */
        private function load(): void
        {
            $this->view->auth = $this->auth;
            $this->view->settings = Comment::settings();
            $json = array(
                'status' => 'success',
                'html' => $this->view->snippet('replyForm', 'view/front/modules/comments/snippets/')
            );
            print json_encode($json);
        }
        
        /**
         * vote
         *
         * @return void
         */
        private function vote(): void
        {
            if (Filter::$id) {
                $type = Validator::sanitize($_POST['type'], 'alphalow', 4);
                $vote = ($type == 'down') ? 'vote_down = vote_down - 1' : 'vote_up = vote_up + 1';
                
                $this->db->rawQuery('UPDATE `' . Comment::mTable . "`SET $vote WHERE id = ?", array(Filter::$id))->run();
                $json = array(
                    'status' => 'success',
                    'type' => $type
                );
                print json_encode($json);
            }
        }
        
        /**
         * _delete
         *
         * @return void
         */
        private function _delete(): void
        {
            if ($this->auth->is_Admin()) {
                $this->db->delete(Comment::mTable)->where('id', Filter::$id, '=')->run();
                $this->db->delete(Comment::mTable)->where('comment_id', Filter::$id, '=')->run();
            }
        }
    }