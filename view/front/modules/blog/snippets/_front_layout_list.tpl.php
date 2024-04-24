<?php
   /**
    * _front_layout_list
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _front_layout_list.tpl.php, v1.00 10/20/2023 2:26 PM Gewa Exp $
    *
    */

   use Wojo\Date\Date;
   use Wojo\Language\Language;
   use Wojo\Module\Blog\Blog;
   use Wojo\Url\Url;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div id="blog" class="row gutters">
   <?php if ($this->rows): ?>
      <?php foreach ($this->rows as $row): ?>
         <div class="columns screen-40 tablet-40 mobile-50 phone-100">
            <figure class="wojo rounded image">
               <img src="<?php echo Blog::hasThumb($row->thumb, $row->id); ?>" alt="<?php echo $row->title; ?>">
            </figure>
         </div>
         <div class="columns screen-60 tablet-60 mobile-50 phone-100">
            <small class="text-weight-400"><?php echo Date::doDate('long_date', $row->created); ?></small>
            <h5 class="basic">
               <a href="<?php echo Url::url($this->core->modname['blog'], $row->slug); ?>" class="black"><?php echo $row->title; ?></a>
            </h5>
            <p class="text-size-small">
               <?php echo Language::$word->IN; ?>:
               <a class="grey" href="<?php echo Url::url($this->core->modname['blog'] . '/' . $this->core->modname['blog-cat'], $row->cslug); ?>">
                  <?php echo $row->ctitle; ?>
               </a>
            </p>
            <div class="text-color-secondary"><?php echo Validator::sanitize($row->body, 'string', 100); ?></div>
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