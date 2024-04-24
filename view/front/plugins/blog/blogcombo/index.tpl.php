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

   use Wojo\Date\Date;
   use Wojo\Language\Language;
   use Wojo\Module\Blog\Blog;
   use Wojo\Url\Url;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   $data = (new Blog())->blogCombo();
?>
<div class="wojo simple small fluid tabs">
   <ul class="nav">
      <li class="active">
         <a data-tab="tab_popular"><?php echo Language::$word->_MOD_AM_SUB41; ?></a>
      </li>
      <li>
         <a data-tab="tab_archive"><?php echo Language::$word->_MOD_AM_SUB42; ?></a>
      </li>
      <li>
         <a data-tab="tab_comments">
            <i class="icon chat"></i>
         </a>
      </li>
   </ul>

   <div class="wojo segment tab">
      <!-- Start Popular Article -->
      <div class="item" data-tab="tab_popular">
         <?php if ($data['popular']): ?>
            <div class="wojo very relaxed divided list">
               <?php foreach ($data['popular'] as $poprow): ?>
                  <div class="item">
                     <img src="<?php echo Blog::hasThumb($poprow->thumb, $poprow->id); ?>" alt="<?php echo $poprow->title; ?>" class="wojo small basic rounded image">
                     <div class="content">
                        <h6 class="basic">
                           <a href="<?php echo Url::url($this->core->modname['blog'], $poprow->slug); ?>" class="secondary"><?php echo $poprow->title; ?></a>
                        </h6>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         <?php endif; ?>
      </div>

      <!-- Start Blog Archive -->
      <div class="item" data-tab="tab_archive">
         <?php if ($data['archive']): ?>
            <div class="wojo divided list">
               <?php foreach ($data['archive'] as $arow): ?>
                  <div class="item align-middle">
                     <div class="content">
                        <span class="wojo secondary label"><?php echo $arow->total; ?></span>
                        <a href="<?php echo Url::url($this->core->modname['blog'] . '/' . $this->core->modname['blog-archive'], $arow->year . '-' . $arow->month); ?>" class="dark"><?php echo Date::doDate('MMMM yyyy', $arow->year . '-' . $arow->month); ?></a>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         <?php endif; ?>
      </div>

      <!-- Start Latest Comments -->
      <div class="item" data-tab="tab_comments">
         <?php if ($data['comments']): ?>
            <div class="wojo relaxed divided list">
               <?php foreach ($data['comments'] as $comrow): ?>
                  <div class="item">
                     <div class="content">
                        <h6>
                           <a href="<?php echo Url::url($this->core->modname['blog'], $comrow->slug); ?>"><?php echo $comrow->title; ?></a>
                        </h6>
                        <p class="text-size-small"><?php echo Validator::truncate($comrow->body, 50); ?></p>
                        <span class="wojo secondary small label"><?php echo Date::doDate('short_date', $comrow->created); ?></span>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         <?php endif; ?>
      </div>
   </div>
</div>
