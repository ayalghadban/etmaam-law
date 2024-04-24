<?php
   /**
    * _blog
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @var object $settings
    * @copyright 2023
    * @version 6.20: _blog.tpl.php, v1.00 6/30/2023 9:14 AM Gewa Exp $
    *
    */
   
   use Wojo\Database\Database;
   use Wojo\Date\Date;
   use Wojo\Language\Language;
   use Wojo\Module\Blog\Blog;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;
   use Wojo\Validator\Validator;
   
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   
   if (class_exists('Wojo\Module\Blog\Blog')) {
      $lg = Language::$lang;
      
      $sql = "
      SELECT id, images, thumb, YEAR(created) as year, MONTH(created) as month, created as timedate, slug$lg as slug, title$lg as title, body$lg as content
        FROM `" . Blog::mTable . '`
        WHERE expire <= NOW() AND active = ?
        ORDER BY created DESC
        LIMIT 0, ' . $settings->maxitems . '
      ';
      
      $data = Database::Go()->rawQuery($sql, array(1))->run();
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
            $temp[$k]['link'] = Url::url($this->properties['core']->modname['blog'], $row->slug);
            $temp[$k]['display_mode'] = 'blog_post';
    
            if ($imagedata) {
               $images = array();
               foreach ($imagedata as $img) {
                  $images[] = FMODULEURL . Blog::BLOGDATA . $row->id . '/thumbs/' . $img->name;
                  $temp[$k]['thumb'] = $images;
               }
            } else {
               $temp[$k]['thumb'] = array(Blog::hasThumb($row->thumb, $row->id));
            }
            $result = json_decode(json_encode((object) $temp), false);
         }
         include_once BASEPATH . 'view/front/modules/timeline/snippets/_blog_index.tpl.php';
      }
   }