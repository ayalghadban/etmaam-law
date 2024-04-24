<?php
   /**
    * _search
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _search.tpl.php, v1.00 6/21/2023 8:32 PM Gewa Exp $
    *
    */
   
   use Wojo\Module\Blog\Blog;
   
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   
   $this->blogdata = Blog::searchResults($this->keyword);