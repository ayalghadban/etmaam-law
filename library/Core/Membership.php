<?php
    /**
     * Membership Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Membership.php, v1.00 4/29/2023 9:23 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Core;
    
    use Wojo\Container\Container;
    use Wojo\Database\Database;
    use Wojo\Date\Date;
    use Wojo\Language\Language;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Membership
    {
        const mTable = 'memberships';
        const umTable = 'user_memberships';
        const pTable = 'payments';
        const cTable = 'cart';
        
        /**
         * calculateTax
         *
         * @return float|int
         */
        public static function calculateTax(): float|int
        {
            $core = Container::instance()->get('core');
            return ($core->enable_tax and $core->tax_rate > 0) ? $core->tax_rate / 100 : 0;
        }
        
        /**
         * calculateDays
         *
         * @param int $membership_id
         * @return string
         */
        public static function calculateDays(int $membership_id): string
        {
            $row = Database::Go()->select(self::mTable, array('days', 'period'))->where('id', $membership_id, '=')->first()->run();
            if ($row) {
                $diff = match ($row->period) {
                    'D' => ' day',
                    'W' => ' week',
                    'M' => ' month',
                    'Y' => ' year',
                    default => '',
                };
                $expire = Date::numberOfDays('+' . $row->days . $diff);
            } else {
                $expire = '';
            }
            return $expire;
        }
        
        /**
         * getCart
         *
         * @param int $user_id
         * @return mixed
         */
        public static function getCart(int $user_id): mixed
        {
            return Database::Go()->select(self::cTable)->where('user_id', $user_id, '=')->first()->run();
        }
        
        /**
         * getMembershipList
         *
         * @return array|false|mixed
         */
        public static function getMembershipList(): mixed
        {
            $lg = Language::$lang;
            return Database::Go()->select(self::mTable, array('id', "title$lg"))->orderBy("title$lg", 'ASC')->run();
        }
        
        /**
         * is_valid
         *
         * @param int $membership_id
         * @param array $memberships
         * @return bool
         */
        public static function is_valid(int $membership_id, array $memberships): bool
        {
            return in_array($membership_id, $memberships);
        }
        
        /**
         * getAccessList
         *
         * @return array
         */
        public static function getAccessList(): array
        {
            return array(
                'Public' => Language::$word->PUBLIC,
                'Registered' => Language::$word->REGISTERED,
                'Membership' => Language::$word->MEMBERSHIP
            );
        }
    }