<?php
    /**
     * Donate CLass
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Donate.php, v1.00 5/15/2023 8:48 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Plugin\Donate;
    
    use Wojo\Core\Content;
    use Wojo\Database\Database;
    use Wojo\Language\Language;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Donate
    {
        const mTable = 'plug_donation';
        const dTable = 'plug_donation_data';
        
        /**
         * render
         *
         * @param int $id
         * @return int|mixed
         */
        public static function render(int $id): mixed
        {
            
            $sql = '
            SELECT m.id, m.title, m.target_amount, m.redirect_page, m.pp_email,
                   (SELECT COALESCE(SUM(amount), 0)
                    FROM `' . self::dTable . '`
                    WHERE parent_id = m.id) as total
              FROM `' . self::mTable . '` as m
              WHERE m.id = ?
            ';
            
            $row = Database::Go()->rawQuery($sql, array($id))->first()->run();
            
            if ($row) {
                $page = Database::Go()->select(Content::pTable, array('slug' . Language::$lang . ' as slug'))->where('id', $row->redirect_page, '=')->first()->run();
                $row->page = $page->slug;
                return $row;
            } else {
                return 0;
            }
        }
        
        /**
         * getAllDonations
         *
         * @return int|mixed
         */
        public static function getAllDonations(): mixed
        {
            $sql = '
            SELECT m.id,m.title,m.target_amount, IFNULL(SUM(d.amount),0) as total
              FROM `' . self::mTable . '` as m
              LEFT JOIN `' . self::dTable . '` as d ON d.parent_id = m.id
              GROUP BY m.id
            ';
            
            $data = Database::Go()->rawQuery($sql)->run();
            return $data ?: 0;
        }
        
        /**
         * exportDonations
         *
         * @param int $id
         * @return int|mixed
         */
        public static function exportDonations(int $id): mixed
        {
            $sql = '
            SELECT d.name, d.email, d.amount, d.pp, d.created
              FROM `' . self::dTable . '` as d
              WHERE parent_id = ?
              ORDER BY d.created
            ';
            
            $data = Database::Go()->rawQuery($sql, array($id))->run();
            return $data ?: 0;
        }
    }