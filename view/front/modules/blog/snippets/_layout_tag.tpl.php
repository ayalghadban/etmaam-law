<?php
   /**
    * _layout_tag
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _layout_tag.tpl.php, v1.00 10/20/2023 2:26 PM Gewa Exp $
    *
    */

   use Wojo\Language\Language;
   use Wojo\Module\Blog\Blog;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
   <h3 class="margin-bottom"><?php echo Language::$word->_MOD_AM_SUB79; ?>
      <small class="text-size-small"><?php echo $this->segments[2]; ?></small>
   </h3>
<?php if ($this->rows): ?>
   <div class="wojo relaxed divided list">
      <?php foreach ($this->rows as $row): ?>
         <div class="item align-middle">
            <div class="content auto">
               <figure class="wojo small rounded image">
                  <img src="<?php echo Blog::hasThumb($row->thumb, $row->id); ?>" alt="<?php echo $row->title; ?>">
               </figure>
            </div>
            <div class="content margin-left">
               <a href="<?php echo Url::url($this->core->modname['blog'], $row->slug); ?>"><?php echo $row->title; ?></a>
            </div>
         </div>
      <?php endforeach; ?>
   </div>
<?php endif; ?>