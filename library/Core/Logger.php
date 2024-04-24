<?php
    /**
     * Logger.php
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Logger.php, v1.00 4/26/2023 3:20 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Core;
    
    use Wojo\Container\Container;
    use Wojo\Database\Database;
    use Wojo\Url\Url;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Logger
    {
        const lTable = 'activity';
        
        /**
         * loginAgain
         *
         * @param int|null $remain
         * @return bool
         */
        public static function loginAgain(int|null &$remain): bool
        {
            $core = Container::instance()->get('core');
            
            $remain = 0;
            $time = self::getTime();
            $var = self::getRecord();
            if (!$var) {
                return true;
            }
            if ($var->failed < $core->attempt) {
                return true;
            }
            if (($time - $var->failed_last) > $core->flood) {
                self::deleteRecord();
                return true;
            }
            $remain = $core->flood - ($time - $var->failed_last);
            return false;
        }
        
        /**
         * getTime
         *
         * @return int
         */
        private static function getTime(): int
        {
            return time();
        }
        
        /**
         * getRecord
         *
         * @return mixed
         */
        public static function getRecord(): mixed
        {
            return Database::Go()->select(self::lTable)->where('ip', Url::getIP(), '=')->where('type', 'user', '=')->first()->run();
        }
        
        private static function deleteRecord(): void
        {
            Database::Go()->delete(self::lTable)->where('ip', Url::getIP(), '=')->where('type', 'user', '=')->run();
        }
        
        /**
         * setFailedLogin
         *
         * @return void
         */
        public static function setFailedLogin(): void
        {
            self::setRecord(self::getTime());
        }
        
        /**
         * setRecord
         *
         * @param string $failed_last
         * @return void
         */
        private static function setRecord(string $failed_last): void
        {
            if ($row = self::getRecord()) {
                Database::Go()->rawQuery('UPDATE `' . self::lTable . '` SET failed_last = ' . $failed_last . ', failed = failed + 1 WHERE id = ?',
                    array($row->id))->run();
            } else {
                $data = array(
                    'ip' => Url::getIP(),
                    'type' => 'user',
                    'failed' => 1,
                    'failed_last' => $failed_last,
                    'importance' => 1,
                    'username' => 'Guest',
                    'message' => 'Possible Brute force attack',
                );
                
                Database::Go()->insert(self::lTable, $data)->run();
            }
        }
        
        /**
         * writeLog
         *
         * @param string $message
         * @param string $type
         * @param int $imp
         * @return void
         */
        public static function writeLog(string $message, string $type = 'content', int $imp = 0): void
        {
            $core = Container::instance()->get('core');
            $auth = Container::instance()->get('auth');
            
            if ($core->logging) {
                $data = array(
                    'user_id' => $auth->uid,
                    'username' => $auth->name,
                    'ip' => Url::getIP(),
                    'type' => $type,
                    'message' => $message,
                    'importance' => $imp
                );
                
                Database::Go()->insert(self::lTable, $data)->run();
            }
        }
    }