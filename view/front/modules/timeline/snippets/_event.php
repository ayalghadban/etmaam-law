<?php
    /**
     * _event
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @var object $settings
     * @version 6.20: _event.php, v1.00 12/5/2023 12:56 PM Gewa Exp $
     *
     */
    
    use Wojo\Database\Database;
    use Wojo\Date\Date;
    use Wojo\Language\Language;
    use Wojo\Module\Event\Event;
    use Wojo\Url\Url;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    $lg = Language::$lang;
    
    $sql = "
    SELECT YEAR(date_start) as year, MONTH(date_start) as month,
          CONCAT(date_start,' ',time_start) as timedate, title$lg as title, body$lg as content, venue$lg as venue , contact_person, contact_email, contact_phone, color
      FROM `" . Event::mTable . '`
      WHERE active = ?
      ORDER BY date_start
      DESC LIMIT ' . $settings->maxitems;
    
    $data = Database::Go()->rawQuery($sql, array(1))->run();
    $temp = array();
    
    if ($data) {
        foreach ($data as $k => $row) {
            $temp[$k]['year'] = $row->year;
            $temp[$k]['month'] = $row->month;
            $temp[$k]['created'] = $row->timedate;
            $temp[$k]['expire'] = Date::doDate('long_date', $row->timedate);
            $temp[$k]['title'] = $row->title;
            $temp[$k]['content'] = Url::out_url($row->content);
            $temp[$k]['venue'] = $row->venue;
            $temp[$k]['contact'] = $row->contact_person;
            $temp[$k]['phone'] = $row->contact_phone;
            $temp[$k]['color'] = $row->color;
            $temp[$k]['display_mode'] = 'event';
            
            $result = json_decode(json_encode((object) $temp), false);
        }
        include_once BASEPATH . 'view/front/modules/timeline/snippets/_event_index.tpl.php';
    }