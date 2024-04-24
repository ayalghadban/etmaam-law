<?php
    /**
     * _portfolio
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @var object $settings
     * @version 6.20: _portfolio.php, v1.00 12/5/2023 2:05 PM Gewa Exp $
     *
     */
    
    use Wojo\Database\Database;
    use Wojo\Date\Date;
    use Wojo\Language\Language;
    use Wojo\Module\Portfolio\Portfolio;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    if (class_exists('Wojo\Module\Portfolio\Portfolio')) {
        $lg = Language::$lang;
        
        $sql = "
        SELECT id, images, thumb, YEAR(created) as year, MONTH(created) as month, created as timedate, slug$lg as slug, title$lg as title, body$lg as content
          FROM `" . Portfolio::mTable . '`
          ORDER BY created DESC
          LIMIT 0, ' . $settings->maxitems;
        
        $data = Database::Go()->rawQuery($sql)->run();
        $temp = array();
        
        if ($data) {
            foreach ($data as $k => $row) {
                $imagedata = Utility::jSonToArray($row->images);
                $temp[$k]['year'] = $row->year;
                $temp[$k]['month'] = $row->month;
                $temp[$k]['created'] = $row->timedate;
                $temp[$k]['expire'] = Date::doDate('long_date', $row->timedate);
                $temp[$k]['title'] = $row->title;
                $temp[$k]['content'] = Validator::sanitize($row->content, 'text', 250);
                $temp[$k]['link'] = Url::url($this->properties['core']->modname['portfolio'], $row->slug);
                $temp[$k]['display_mode'] = 'blog_post';
                
                if ($imagedata) {
                    $images = array();
                    foreach ($imagedata as $img) {
                        $images[] = FMODULEURL . Portfolio::PORTDATA . $row->id . '/thumbs/' . $img->name;
                        $temp[$k]['thumb'] = $images;
                    }
                } else {
                    $temp[$k]['thumb'] = array(Portfolio::hasThumb($row->thumb, $row->id));
                }
                $result = json_decode(json_encode((object) $temp), false);
            }
            include_once BASEPATH . 'view/front/modules/timeline/snippets/_portfolio_index.tpl.php';
        }
    }