<?php
    /**
     * Timeline Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Timeline.php, v1.00 5/29/2023 8:20 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Module\Timeline;
    
    use Wojo\Core\Module;
    use Wojo\Database\Database;
    use Wojo\Language\Language;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Timeline
    {
        const mTable = 'mod_timeline';
        const dTable = 'mod_timeline_data';
        const SPATH = 'timeline/snippets/';
        
        
        /**
         * render
         *
         * @param int $id
         * @return mixed
         */
        public static function render(int $id): mixed
        {
            return Database::Go()->select(self::mTable)->where('id', $id, '=')->first()->run();
        }
        
        /**
         * layoutMode
         *
         * @return string[]
         */
        public static function layoutMode(): array
        {
            return array(
                'dual' => 'Dual Column',
                'center' => 'Center Column',
            );
        }
        
        /**
         * typeList
         *
         * @return string[]
         */
        public static function typeList(): array
        {
            $array = array(
                'event' => 'Event Module',
                'rss' => 'Rss Feed',
                'custom' => 'Custom Timeline',
            );
            
            $data = Database::Go()->select(Module::mTable, array('modalias', 'title' . Language::$lang . ' as title'))->where('system', 1, '=')->run();
            if ($data) {
                foreach ($data as $row) {
                    if ($row->modalias == 'blog') {
                        $array['blog'] = $row->title;
                    }
                    if ($row->modalias == 'portfolio') {
                        $array['portfolio'] = $row->title;
                    }
                }
            }
            return $array;
        }
    }