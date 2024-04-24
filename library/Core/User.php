<?php
    /**
     * User Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: User.php, v1.00 4/29/2023 9:12 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Core;
    
    use Wojo\Database\Database;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class User
    {
        const mTable = 'users';
        const rTable = 'roles';
        const rpTable = 'role_privileges';
        const pTable = 'privileges';
        const blTable = 'banlist';
        const aTable = 'activity';
        
        /**
         * deleteInactiveUsers
         *
         * @return void
         */
        public function deleteInactiveUsers(): void
        {
            $sql = '
            DELETE FROM `' . User::mTable . '`
              WHERE DATE(lastlogin) < DATE_SUB(CURDATE(),
              INTERVAL ' . intval($_POST['days']) . ' DAY)
              AND type = ?
              AND active = ?
            ';
            
            Database::Go()->rawQuery($sql, array('member', 'y'))->run();
            $total = Database::Go()->affected();
            
            Message::msgReply($total, 'success', Message::formatSuccessMessage($total, Language::$word->UTL_DELINCT_OK));
        }
        
        /**
         * deleteBannedUsers
         *
         * @return void
         */
        public function deletePendingUsers(): void
        {
            Database::Go()->delete(User::mTable)->where('active', 't', '=')->run();
            $total = Database::Go()->affected();
            
            Message::msgReply($total, 'success', Message::formatSuccessMessage($total, Language::$word->UTL_DELPEND_OK));
        }
        
        /**
         * deleteBannedUsers
         *
         * @return void
         */
        public function deleteBannedUsers(): void
        {
            Database::Go()->delete(User::mTable)->where('active', 'b', '=')->run();
            $total = Database::Go()->affected();
            
            Message::msgReply($total, 'success', Message::formatSuccessMessage($total, Language::$word->UTL_DELBND_OK));
        }
        
        /**
         * getPrivileges
         *
         * @param int $id
         * @return array|false|mixed
         */
        public function getPrivileges(int $id): mixed
        {
            $sql = '
            SELECT rp.id, rp.active, p.id as prid, p.name, p.type, p.description, p.mode
              FROM `' . self::rpTable . '` as rp
              INNER JOIN `' . self::rTable . '` as r ON rp.rid = r.id
              INNER JOIN `' . self::pTable . '` as p ON rp.pid = p.id
              WHERE rp.rid = ?
              ORDER BY p.type;
            ';
            
            return Database::Go()->rawQuery($sql, array($id))->run();
        }
        
        /**
         * userInvoice
         *
         * @param int $id
         * @param int $user_id
         * @return mixed
         */
        public static function userInvoice(int $id, int $user_id): mixed
        {
            $lg = Language::$lang;
            
            $sql = "
            SELECT p.*, m.title$lg as title, m.description$lg as description, DATE_FORMAT(p.created, '%Y%m%d - %H%m') as invid
              FROM `" . Membership::pTable . '` as p
              LEFT JOIN ' . Membership::mTable . ' as m ON m.id = p.membership_id
              WHERE p.id = ?
              AND p.user_id = ?
              AND p.status = ?
            ';
            
            return Database::Go()->rawQuery($sql, array($id, $user_id, 1))->first()->run();
        }
    }