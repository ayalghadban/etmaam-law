<?php
    /**
     * master
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: master.php, v1.00 6/30/2023 8:53 AM Gewa Exp $
     *
     */
   
   use Wojo\Module\Timeline\Timeline;
   
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   
   if (isset($this->properties['module_id'])) {
      if ($settings = Timeline::render($this->properties['id'])) {
         switch ($settings->type) {
            case 'blog':
               include_once BASEPATH . 'view/front/modules/timeline/snippets/_blog.php';
               break;
            
            case 'event':
               include_once BASEPATH . 'view/front/modules/timeline/snippets/_event.php';
               break;
            
            case 'portfolio':
               include_once BASEPATH . 'view/front/modules/timeline/snippets/_portfolio.php';
               break;
            
            case 'rss':
               include_once BASEPATH . 'view/front/modules/timeline/snippets/_rss.php';
               break;
            
            default:
               include_once BASEPATH . 'view/front/modules/timeline/snippets/_custom.php';
               break;
         }
      }
   }