<?php
   /**
    * _front_layout_modern
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _front_layout_modern.tpl.php, v1.00 10/20/2023 2:26 PM Gewa Exp $
    *
    */

   use Wojo\Date\Date;
   use Wojo\Language\Language;
   use Wojo\Module\Blog\Blog;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div id="blog" data-url="<?php echo FMODULEURL; ?>">
   <?php if ($this->rows): ?>
      <div class="row gutters">
         <?php foreach ($this->rows as $row): ?>
            <?php $size = Utility::getColumnSize(); ?>
            <?php $style = ($row->thumb)? ' style="background-image:url(' . FMODULEURL . Blog::BLOGDATA . $row->id . '/thumbs/' . $row->thumb . ')"' : null; ?>
            <div class="columns screen-<?php echo $size; ?> tablet-100 mobile-50 phone-100">
               <a href="<?php echo Url::url($this->core->modname['blog'], $row->slug); ?>" class="wojo hero rounded image align-bottom<?php echo (!$row->thumb)? ' bg-color-primary' : null; ?>"<?php echo $style; ?>>
                  <article class="padding center-align">
                     <h4 class="text-color-white"><?php echo $row->title; ?></h4>
                     <small class="text-color-white dimmed-text"><?php echo Date::doDate('long_date', $row->created); ?></small>
                  </article>
               </a>
            </div>
         <?php endforeach; ?>
      </div>
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