<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 6/14/2023 11:50 AM Gewa Exp $
    *
    */

   use Wojo\Exception\NotFoundException;
   use Wojo\Module\Blog\Blog;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="wojo segment margin-vertical">
	<?php echo Blog::renderCategories(Blog::catList()); ?>
</div>