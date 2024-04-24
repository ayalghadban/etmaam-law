<?php
    /**
     * NewsletterController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: NewsletterController.php, v1.00 6/13/2023 9:46 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Front\Plugin\Newsletter;
    
    use Wojo\Core\Controller;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
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
                        case 'process':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->process();
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
         * process
         *
         * @return void
         */
        public function process(): void
        {
            $validate = Validator::run($_POST);
            
            $validate->set('email', Language::$word->M_EMAIL)->required()->email();
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                if ($this->emailExists($safe->email)) {
                    $this->db->delete(Newsletter::mTable)->where('email', $safe->email, '=')->run();
                    $json['message'] = Language::$word->_PLG_NSL_UNSUBOK;
                } else {
                    $this->db->insert(Newsletter::mTable, array('email' => $safe->email))->run();
                    $json['message'] = Language::$word->_PLG_NSL_SUBOK;
                }
                $json = array(
                    'type' => 'success',
                    'title' => Language::$word->SUCCESS
                );
                print json_encode($json);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * emailExists
         *
         * @param string $email
         * @return mixed
         */
        private function emailExists(string $email): mixed
        {
            return $this->db->select(Newsletter::mTable, array('email'))->where('email', $email, '=')->first()->run();
        }
    }