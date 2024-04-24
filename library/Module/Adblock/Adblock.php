<?php
    /**
     * Adblock Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Adblock.php, v1.00 5/19/2023 10:09 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Module\Adblock;
    
    use Wojo\Database\Database;
    use Wojo\Date\Date;
    use Wojo\Language\Language;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Adblock
    {
        const mTable = 'mod_adblock';
        const ADATA = 'adblock/';
        const MAXSIZE = 204800;
        
        
        /**
         * render
         *
         * @param int $id
         * @return mixed
         */
        public static function render(int $id): mixed
        {
            $lg = Language::$lang;
            $sql = "SELECT *, title$lg as title FROM `" . self::mTable . '` WHERE id = ?';
            
            return Database::Go()->rawQuery($sql, array($id))->first()->run();
        }
        
        /**
         * updateView
         *
         * @param int $id
         * @return void
         */
        public static function updateView(int $id): void
        {
            $sql = 'UPDATE `' . self::mTable . '` SET total_views = total_views + 1 WHERE id = ?';
            Database::Go()->rawQuery($sql, array($id));
        }
        
        /**
         * isOnlineStr
         *
         * @param object $row
         * @return mixed
         */
        public static function isOnlineStr(object $row): mixed
        {
            return (self::isOnline($row)) ? Language::$word->_MOD_AB_ONLINE : Language::$word->_MOD_AB_OFFLINE;
        }
        
        /**
         * isOnline
         *
         * @param object $row
         * @return bool
         */
        public static function isOnline(object $row): bool
        {
            $now = strtotime(Date::today());
            
            //time-period checking
            if (strtotime($row->start_date) > $now) {
                return false;
            }
            if ($row->end_date > 0 && strtotime($row->end_date) <= $now) {
                return false;
            }
            
            $total_views_allowed = $row->total_views_allowed;
            $total_views = $row->total_views;
            $total_clicks_allowed = $row->total_clicks_allowed;
            $total_clicks = $row->total_clicks;
            $min_ctr = $row->minimum_ctr;
            $ctr = ($total_views) ? round($total_clicks / $total_views) : 0;
            
            
            //conditions checking
            if ($total_views_allowed > 0 && $total_views > 0 && $total_views_allowed <= $total_views) {
                return false;
            }
            if ($total_clicks_allowed > 0 && $total_clicks > 0 && $total_clicks_allowed <= $total_clicks) {
                return false;
            }
            if ($min_ctr > 0 && $total_views > 0 && $ctr < $min_ctr) {
                return false;
            }
            
            return true;
        }
    }