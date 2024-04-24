<?php
    /**
     * Event Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Event.php, v1.00 5/19/2023 9:35 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Module\Event;
    
    use Wojo\Database\Database;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Utility\Utility;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Event
    {
        
        const mTable = 'mod_events';
        
        /**
         * render
         *
         * @return array
         */
        public static function render(): array
        {
            $lg = Language::$lang;
            $sql = "
            SELECT color, date_start, date_end, contact_person, contact_email, contact_phone,
                   DATE_FORMAT(`time_start`, '%H:%i') as time_start,
                   DATE_FORMAT(`time_end`, '%H:%i') as time_end,
                   DATE_FORMAT(`date_start`, '%Y-%m') as month, venue$lg as venue, title$lg as title, body$lg as body
              FROM `" . self::mTable . '`
              WHERE active = ?
              ORDER BY date_start DESC
            ';
            
            $data = Database::Go()->rawQuery($sql, array(1))->run();
            return Utility::groupToLoop($data, 'month');
        }
        
        /**
         * getEvents
         *
         * @param int $year
         * @param int $month
         * @return int|mixed
         */
        public static function getEvents(int $year, int $month): mixed
        {
            $ld = date('t', strtotime('now'));
            $lg = Language::$lang;
            $sql = "
            SELECT id, color, date_start, date_end, contact_person, contact_email, contact_phone,
                   DATE_FORMAT(`time_start`, '%H:%i') as time_start,
                   DATE_FORMAT(`time_end`, '%H:%i') as time_end, venue$lg as venue, title$lg as title, body$lg as body
              FROM `" . self::mTable . "`
              WHERE date_start <= '$year-$month-01' date_end >= '$year-$month-$ld)'
              AND active = ?
              ORDER BY time_start ASC
            ";
            
            return Database::Go()->rawQuery($sql, array(1))->run();
        }
        
        /**
         * renderEvent
         *
         * @return mixed
         */
        public static function renderEvent(): mixed
        {
            $lg = Language::$lang;
            $config = json_decode(File::loadFile(APLUGPATH . 'event/config.json'));
            
            $sql = "
            SELECT id, date_start, date_end, time_start, time_end, color, venue$lg as venue, title$lg as title, body$lg as body
            FROM `" . self::mTable . '`
            WHERE id IN (' . $config->event->event_id . ')
            ';
            
            return Database::Go()->rawQuery($sql)->run();
        }
    }