<?php
    /**
     * Mailer Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Mailer.php, v1.00 5/9/2023 10:52 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Core;
    
    use PHPMailer\PHPMailer\PHPMailer;
    use Wojo\Container\Container;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Mailer
    {
        private static ?Mailer $instance = null;
        
        /**
         * instance
         *
         * @return Mailer
         */
        public static function instance(): Mailer
        {
            if (!self::$instance) {
                self::$instance = new Mailer();
            }
            
            return self::$instance;
        }
        
        /**
         * sendMail
         *
         * @return PHPMailer
         */
        public static function sendMail(): PHPMailer
        {
            require_once BASEPATH . 'vendor/PHPMailer/vendor/autoload.php';
            
            $core = Container::instance()->get('core');
            $mail = new PHPMailer(true);
            
            if ($core->mailer == 'SMTP') {
                $mail->isSMTP();
            } else {
                $mail->isSendmail($core->sendmail);
            }
            
            //$mail->SMTPDebug = 4;//Enable verbose debug output
            $mail->Host = $core->smtp_host;
            $mail->SMTPAuth = true;
            $mail->Username = $core->smtp_user;
            $mail->Password = $core->smtp_pass;
            $mail->SMTPSecure = $core->is_ssl ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $core->smtp_port;
            $mail->CharSet = PHPMailer::CHARSET_UTF8;
            
            return $mail;
        }
    }