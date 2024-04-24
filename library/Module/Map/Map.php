<?php
    /**
     * Map CLass
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Map.php, v1.00 5/22/2023 9:06 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Module\Map;
    
    use Wojo\Database\Database;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Map
    {
        const mTable = 'mod_gmaps';
        const pTable = 'mod_gmaps_pins';
        
        /**
         * render
         *
         * @param $id
         * @return mixed
         */
        public static function render($id): mixed
        {
            return Database::Go()->select(self::mTable)->where('id', $id, '=')->first()->run();
        }
        
        /**
         * mapType
         *
         * @return string[]
         */
        public static function mapType(): array
        {
            return array(
                'roadmap' => 'Road Map',
                'satellite' => 'Satellite Map',
                'hybrid' => 'Hybrid Map',
                'terrain' => 'Terrain Map',
            );
        }
    }