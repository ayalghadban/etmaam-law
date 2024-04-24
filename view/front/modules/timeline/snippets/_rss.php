<?php
    /**
     * _rss
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @var object $settings
     * @version 6.20: _rss.tpl.php, v1.00 12/5/2023 1:41 PM Gewa Exp $
     *
     */
    
    use Wojo\Date\Date;
    use Wojo\Plugin\Rss\Rss;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    $items = Rss::render($settings->rssurl, $settings->maxitems);
    $array = array();
    $json_response = array();
    
    if ($items) {
        for ($x = 0; $x < $items[1]; $x++) {
            $array['title'] = str_replace(' & ', ' &amp; ', $items[0][$x]['title']);
            $array['content'] = $items[0][$x]['desc'];
            $date = date('Y-m-d H:i:s', strtotime($items[0][$x]['date']));
            $array['year'] = Date::doDate('yyyy', $date);
            $array['month'] = Date::doDate('MM', $date);
            $array['timedate'] = Date::doDate('yyyy-MM-dd', $date);
            $array['edate'] = Date::doDate('long_date', $date);
            
            $array['more'] = $items[0][$x]['link'];
            $array['display_mode'] = 'rss_post';
            
            $json_response[] = $array;
        }
        $result = json_decode(json_encode((object) $json_response), false);
        
        include_once BASEPATH . 'view/front/modules/timeline/snippets/_rss_index.tpl.php';
    }
