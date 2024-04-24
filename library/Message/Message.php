<?php
    /**
     * Message Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Message.php, v1.00 4/26/2023 10:23 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Message;
    
    use Wojo\Language\Language;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Message
    {
        public static array $msgs = array();
        
        /**
         * msgAlert
         *
         * @param string $string
         * @return string
         */
        public static function msgAlert(string $string): string
        {
            $html = '<div class="wojo icon message alert"><i class="icon exclamation triangle"></i><i class="icon x alt"></i>';
            $html .= '<div class="content"><div class="header"> ' . Language::$word->ALERT . '</div><p>' . $string . '</p></div></div>';
            
            return $html;
            
        }
        
        /**
         * msgSingleAlert
         *
         * @param string $string
         * @return string
         */
        public static function msgSingleAlert(string $string): string
        {
            return '<div class="wojo alert small icon message"><i class="icon exclamation triangle"></i> <div class="content">' . $string . '</div></div>';
        }
        
        /**
         * msgOk
         *
         * @param string $string
         * @return string
         */
        public static function msgOk(string $string): string
        {
            $html = '<div class="wojo icon message positive"><i class="circle check icon"></i><i class="icon x alt"></i>';
            $html .= '<div class="content"><div class="header"> ' . Language::$word->SUCCESS . '</div><p>' . $string . '</p></div></div>';
            
            return $html;
        }
        
        /**
         * msgSingleOk
         *
         * @param string $string
         * @return string
         */
        public static function msgSingleOk(string $string): string
        {
            return '<div class="wojo positive small icon message"><i class="circle check icon"></i> <div class="content">' . $string . '</div></div>';
            
        }
        
        /**
         * msgInfo
         *
         * @param string $string
         * @return string
         */
        public static function msgInfo(string $string): string
        {
            $html = '<div class="wojo icon message info"><i class="icon information circle"></i><i class="icon x alt"></i>';
            $html .= '<div class="content"><div class="header"> ' . Language::$word->INFO . '</div><p>' . $string . '</p></div></div>';
            
            return $html;
        }
        
        /**
         * msgSingleInfo
         *
         * @param string $string
         * @return string
         */
        public static function msgSingleInfo(string $string): string
        {
            return '<div class="wojo info small icon message"><i class="icon information circle"></i> <div class="content">' . $string . '</div></div>';
        }
        
        /**
         * msgError
         *
         * @param string $string
         * @return string
         */
        public static function msgError(string $string): string
        {
            $html = '<div class="wojo icon message negative"><i class="icon dash circle"></i><i class="icon x alt"></i>';
            $html .= '<div class="content"><div class="header"> ' . Language::$word->ERROR . '</div><p>' . $string . '</p></div></div>';
            return $html;
        }
        
        /**
         * msgSingleError
         *
         * @param string $string
         * @return string
         */
        public static function msgSingleError(string $string): string
        {
            return '<div class="wojo negative icon small message"><i class="icon dash circle"></i> <div class="content">' . $string . '</div></div>';
        }
        
        /**
         * msgStatus
         *
         * @return string
         */
        public static function msgStatus(): string
        {
            $html = '<div class="wojo negative message"><i class="icon x alt"></i><div class="header">' . Language::$word->PROCESS_ERR . '</div>';
            $html .= '<div class="content"><ul class="wojo list">';
            foreach (self::$msgs as $msg) {
                $html .= '<li>' . $msg . "</li>\n";
            }
            $html .= '</ul></div></div>';
            
            return $html;
        }
        
        /**
         * msgSingleStatus
         *
         * @return void
         */
        public static function msgSingleStatus(): void
        {
            $html = '<div class="wojo small list">';
            $i = 1;
            foreach (self::$msgs as $msg) {
                $html .= '<div class="item align middle"><b>' . $i . '.</b> ' . $msg . "</div>\n";
                $i++;
            }
            $html .= '</div>';
            
            $json['type'] = 'error';
            $json['title'] = Language::$word->PROCESS_ERR;
            $json['message'] = $html;
            print json_encode($json);
        }
        
        /**
         * msgReply
         *
         * @param string $data
         * @param string $type
         * @param string $msg
         * @param bool $msg2
         * @return void
         */
        public static function msgReply(string $data, string $type, string $msg, bool $msg2 = false): void
        {
            $title = strtoupper($type);
            if ($data) {
                $json['type'] = $type;
                $json['title'] = Language::$word->$title;
                $json['message'] = $msg;
            } else {
                $json['type'] = 'alert';
                $json['title'] = Language::$word->ALERT;
                $json['message'] = ($msg2) ? : Language::$word->NOPROCESS;
            }
            
            print json_encode($json);
        }
        
        /**
         * Message::msgModalReply()
         *
         * @param mixed $data
         * @param mixed $type
         * @param mixed $msg
         * @param mixed $html
         * @return void
         */
        public static function msgModalReply($data, $type, $msg, $html): void
        {
            $title = strtoupper($type);
            if ($data) {
                $json['type'] = $type;
                $json['title'] = Language::$word->$title;
                $json['html'] = $html;
                $json['message'] = $msg;
            } else {
                $json['type'] = 'alert';
                $json['title'] = Language::$word->ALERT;
                $json['html'] = $html;
                $json['message'] = Language::$word->NOPROCESS;
            }
            
            print json_encode($json);
        }
        
        /**
         * Message::formatSuccessMessage()
         *
         * @param mixed $name
         * @param mixed $message
         * @return array|mixed|string|string[]
         */
        public static function formatSuccessMessage($name, $message): mixed
        {
            
            return str_replace('[NAME]', '<b>' . $name . '</b>', $message);
        }
        
        /**
         * Message::error()
         *
         * @param mixed $msg
         * @param mixed $source
         * @return void
         */
        public static function error($msg, $source): void
        {
            if (DEBUG) {
                $html = '<div class="wojo message negative">';
                $html .= '<span>System ERROR!</span><br />';
                $html .= 'DB Error: ' . $msg . ' <br /> More Information: <br />';
                $html .= '<ul class="error">';
                $html .= '<li> Date : ' . date('F j, Y, g:i a') . '</li>';
                $html .= '<li> Function: ' . $source . '</li>';
                $html .= '<li> Script: ' . $_SERVER['REQUEST_URI'] . '</li>';
                $html .= '<li>&lsaquo; <a href="javascript:history.go(-1)"><strong>Go Back to previous page</strong></a></li>';
                $html .= '</ul>';
            } else {
                $html = '<div class="msgError" style="color:#444;width:400px;margin-left:auto;margin-right:auto;border:1px solid #C3C3C3;font-family:Arial, Helvetica, sans-serif;font-size:13px;padding:10px;background:#f2f2f2;border-radius:5px;text-shadow:1px 1px 0 #fff">';
                $html .= '<h4 style="font-size:18px;margin:0;padding:0">Oops!!!</h4>';
                $html .= '<p>Something went wrong. Looks like the page you\'re looking for was moved or never existed. Make sure you typed the correct URL or followed a valid link.</p>';
            }
            $html .= '</div>';
            print $html;
            exit(1);
        }
        
        /**
         * Message::invalid()
         *
         * @param mixed $data
         * @param bool $print
         * @return string|void
         */
        public static function invalid($data, $print = true)
        {
            $html = '<div class="wojo negative message"><i class="icon slash circle"></i> ' . Language::$word->SYSTEM_ERR1 . " <em>$data</em></div>";
            
            if ($print) {
                print $html;
            } else {
                return $html;
            }
        }
        
        /**
         * Message::permission()
         *
         * @param mixed $data
         * @param bool $print
         * @return string|void
         */
        public static function permission($data, $print = true)
        {
            $html = '<div class="wojo negative message"><i class="icon slash circle"></i> ' . Language::$word->SYSTEM_ERR2 . " <em>$data</em></div>";
            
            if ($print) {
                print $html;
            } else {
                return $html;
            }
        }
        
        /**
         * Message::ooops()
         *
         * @return void
         */
        public static function ooops(): void
        {
            $the_error = '<div class="msgError" style="color:#444;width:400px;margin-left:auto;margin-right:auto;border:1px solid #C3C3C3;font-family:Arial, Helvetica, sans-serif;font-size:13px;padding:10px;background:#f2f2f2;border-radius:5px;text-shadow:1px 1px 0 #fff">';
            $the_error .= '<h4 style="font-size:18px;margin:0;padding:0">Oops!!!</h4>';
            $the_error .= '<p>Something went wrong. Looks like the page you\'re looking for was moved or never existed. Make sure you typed the correct URL or followed a valid link.</p>';
            $the_error .= '<p>&lsaquo; <a href="javascript:history.go(-1)" style="color:#0084FF;"><strong>Go Back to previous page</strong></a></p>';
            $the_error .= '</div>';
            print $the_error;
            die;
        }
    }