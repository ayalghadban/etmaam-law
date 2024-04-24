<?php
   /**
    * _sitemap
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _sitemap.tpl.php, v1.00 6/21/2023 9:04 PM Gewa Exp $
    *
    */
   
   use Wojo\Module\Blog\Blog;
   
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   
   $this->blogdata = Blog::sitemap();