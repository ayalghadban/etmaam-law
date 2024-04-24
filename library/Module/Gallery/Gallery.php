<?php
    /**
     * Gallery Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Gallery.php, v1.00 5/12/2023 8:27 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Module\Gallery;
    
    use Wojo\Database\Database;
    use Wojo\Language\Language;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Gallery
    {
        const mTable = 'mod_gallery';
        const dTable = 'mod_gallery_data';
        const GALDATA = 'gallery/data/';
        
        /**
         * getAllGalleries
         *
         * @return array|false|int|mixed
         */
        public static function getAllGalleries(): mixed
        {
            $sql = '
              SELECT
                m.*,
                COUNT(d.parent_id) as pics,
                SUM(IFNULL(d.likes, 0)) as likes
              FROM
                `' . self::mTable . '` as m
                LEFT JOIN `' . self::dTable . '` as d
                  ON m.id = d.parent_id
              GROUP BY m.id, m.sorting
              ORDER BY m.sorting
            ';
            $row = Database::Go()->rawQuery($sql)->run();
            
            return $row ?: 0;
        }
        
        /**
         * renderSingle
         *
         * @param int $parent_id
         * @return mixed
         */
        public static function renderSingle(int $parent_id): mixed
        {
            return Database::Go()->select(self::dTable)->where('parent_id', $parent_id, '=')->orderBy('sorting', 'ASC')->run();
        }
        
        /**
         * getGallery
         *
         * @param int $id
         * @return mixed
         */
        public static function getGallery(int $id): mixed
        {
            $lg = Language::$lang;
            return Database::Go()->select(self::mTable, array('id', "title$lg as title", "description$lg as description", 'cols', 'dir', 'watermark', 'likes'))->where('id', $id, '=')->first()->run();
        }
        
        /**
         * getGalleryList
         *
         * @return array|false|int|mixed
         */
        public static function getGalleryList(): mixed
        {
            
            $row = Database::Go()->select(self::mTable, array('id', 'title' . Language::$lang))->orderBy('sorting', 'ASC')->run();
            
            return $row ?: 0;
        }
    }