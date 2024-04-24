<?php
    /**
     * Newsletter CLass
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Newsletter.php, v1.00 5/18/2023 10:32 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Plugin\Newsletter;
    
    use Wojo\Auth\Auth;
    use Wojo\Database\Database;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Newsletter
    {
        const mTable = 'plug_newsletter';
        
        /**
         * process
         *
         * @return void
         */
        public function process(): void
        {
            $validate = Validator::run($_POST);
            $validate->set('email', Language::$word->M_EMAIL)->email();
            
            $safe = $validate->safe();
            
            if (strlen($safe->email) !==0) {
                if (self::emailExists($safe->email) && $_POST['active'] == 1) {
                    Message::$msgs['email'] = Language::$word->M_EMAIL_R2;
                }
                
                if (!self::emailExists($safe->email) && $_POST['active'] == 0) {
                    Message::$msgs['email'] = Language::$word->M_EMAIL_R4;
                }
            }
            
            if (count(Message::$msgs) === 0) {
                if (Auth::emailExists($safe->email)) {
                    Database::Go()->delete(self::mTable)->where('email', $safe->email, '=')->run();
                    $message = Language::$word->_PLG_NSL_UNSUBOK;
                } else {
                    Database::Go()->insert(self::mTable, array('email' => $safe->email))->run();
                    $message = Language::$word->_PLG_NSL_SUBOK;
                }
                
                if (intval($_POST['active']) == 1) {
                    Database::Go()->insert(self::mTable, array('email' => $safe->email))->run();
                    $message = Language::$word->_PLG_NSL_SUBOK;
                } else {
                    Database::Go()->delete(self::mTable)->where('email', $safe->email, '=')->run();
                    $message = Language::$word->_PLG_NSL_UNSUBOK;
                }
                $json['type'] = 'success';
                $json['title'] = Language::$word->SUCCESS;
                $json['message'] = $message;
                print json_encode($json);
            } else {
                Message::msgSingleStatus();
            }
        }
    }