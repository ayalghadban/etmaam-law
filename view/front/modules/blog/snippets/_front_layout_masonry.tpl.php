<?php
   /**
    * _front_layout_masonry
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _front_layout_masonry.tpl.php, v1.00 10/20/2023 2:26 PM Gewa Exp $
    *
    */

   use Wojo\Date\Date;
   use Wojo\Language\Language;
   use Wojo\Module\Blog\Blog;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div id="blog" class="wojo mason two" data-url="<?php echo FMODULEURL; ?>">
   <?php if ($this->rows): ?>
      <?php foreach ($this->rows as $row): ?>
         <div class="item">
            <figure class="wojo rounded image">
               <?php if ($row->thumb): ?>
                  <img src="<?php echo Blog::hasThumb($row->thumb, $row->id); ?>" alt="<?php echo $row->title; ?>">
               <?php endif; ?>
            </figure>
            <div class="rounded padding-vertical<?php echo (!$row->thumb)? ' bg-color-primary padding-small' : null; ?>">
               <small class="<?php echo ($row->thumb)? 'text-color-secondary' : 'text-color-white dimmed-text'; ?> thin text"><?php echo Date::doDate('long_date', $row->created); ?></small>
               <h6>
                  <a href="<?php echo Url::url($this->core->modname['blog'], $row->slug); ?>" class="<?php echo ($row->thumb)? 'black' : 'white'; ?>"><?php echo $row->title; ?></a>
               </h6>
            </div>
         </div>
      <?php endforeach; ?>
   <?php endif; ?>
</div>
<div class="padding-small-horizontal">
   <div class="row gutters align-middle">
      <div class="columns auto mobile-100 phone-100">
         <div class="text-size-small text-weight-500"><?php echo Language::$word->TOTAL . ': ' . $this->pager->items_total; ?>
            / <?php echo Language::$word->CURPAGE . ': ' . $this->pager->current_page . ' ' . Language::$word->OF . ' ' . $this->pager->num_pages; ?></div>
      </div>
      <div class="columns mobile-100 right-align"><?php echo $this->pager->display(); ?></div>
   </div>
</div>