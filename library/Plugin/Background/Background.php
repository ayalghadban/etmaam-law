<?php
    
    /**
     * Background Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2024
     * @version 6.20: Background.php, v1.00 1/19/2024 8:41 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Plugin\Background;
    
    use Wojo\Database\Database;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Background
    {
        const mTable = 'plug_background';
        
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