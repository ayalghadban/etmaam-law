<?php
    /**
     * _custom
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @var object $settings
     * @version 6.20: _custom.php, v1.00 12/5/2023 2:29 PM Gewa Exp $
     *
     */
    
    use Wojo\Database\Database;
    use Wojo\Date\Date;
    use Wojo\Language\Language;
    use Wojo\Module\Timeline\Timeline;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    $lg = Language::$lang;
    
    $sql = "
    SELECT *, YEAR(created) AS year, MONTH(created) AS month, created AS timedate, title$lg AS title, body$lg AS content
    FROM `" . Timeline::dTable . '`
    WHERE timeline_id = ?
    ORDER BY created DESC
    LIMIT 0, ' . $settings->maxitems;
    
    $data = Database::Go()->rawQuery($sql, array($settings->id))->run();
    $temp = array();
    
    if ($data) {
        foreach ($data as $k => $row) {
            $imagedata = Utility::jSonToArray($row->images);
            $temp[$k]['year'] = $row->year;
            $temp[$k]['month'] = $row->month;
            $temp[$k]['created'] = $row->timedate;
            $temp[$k]['expire'] = Date::doDate('long_date', $row->timedate);
            $temp[$k]['title'] = $row->title;
            $temp[$k]['content'] = Url::out_url($row->content);
            $temp[$k]['more'] = $row->readmore;
            $temp[$k]['dataurl'] = $row->dataurl;
            $temp[$k]['height'] = $row->height;
            $temp[$k]['display_mode'] = $row->type;
            
            if ($imagedata) {
                $images = array();
                foreach ($imagedata as $img) {
                    $images[] = UPLOADURL . $img;
                    $temp[$k]['thumb'] = $images;
                }
            }
            $result = json_decode(json_encode((object) $temp), false);
        }
        include_once BASEPATH . 'view/front/modules/timeline/snippets/_custom_index.tpl.php';
    }