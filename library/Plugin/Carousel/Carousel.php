<?php
    /**
     * Carousel Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Carousel.php, v1.00 5/18/2023 3:54 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Plugin\Carousel;
    
    use Wojo\Database\Database;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Carousel
    {
        
        const mTable = 'plug_carousel';
        
        /**
         * getAllPlayers
         *
         * @return int|mixed
         */
        public static function getAllPlayers(): mixed
        {
            return Database::Go()->select(self::mTable)->run();
        }
        
        /**
         * render
         *
         * @param int $id
         * @return int|mixed
         */
        public static function render(int $id): mixed
        {
            return Database::Go()->select(self::mTable, null)->where('id', $id, '=')->first()->run();
        }
    }