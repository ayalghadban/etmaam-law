<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 6/11/2023 8:11 AM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   
   use Wojo\Date\Date;
   use Wojo\Language\Language;
   use Wojo\Module\Blog\Blog;
   use Wojo\Url\Url;

?>
<?php if (class_exists('Wojo\Module\Blog\Blog')): ?>
   <?php $data = (new Blog())->blogFooter(); ?>
   <div class="columns mobile-100 phone-100">
      <h6 class="text-color-primary-inverted"><?php echo Language::$word->_MOD_AM_SUB41; ?></h6>
      <?php if ($data['popular']): ?>
         <div class="wojo small list">
            <?php foreach ($data['popular'] as $poprow): ?>
               <div class="item">
                  <div class="content">
                     <a href="<?php echo Url::url($this->core->modname['blog'], $poprow->slug); ?>" class="inverted"><?php echo $poprow->title; ?></a>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>
      <?php endif; ?>
   </div>
   <div class="columns mobile-100 phone-100">
      <h6 class="text-color-primary-inverted"><?php echo Language::$word->_MOD_AM_SUB42; ?></h6>
      <?php if ($data['archive']): ?>
         <div class="wojo small list">
            <?php foreach ($data['archive'] as $arow): ?>
               <div class="item align-middle">
                  <i class="icon small white chevron right"></i>
                  <div class="content">
                     <a href="<?php echo Url::url($this->core->modname['blog'] . '/' . $this->core->modname['blog-archive'], $arow->year . '-' . $arow->month); ?>" class="inverted"><?php echo Date::doDate('MMMM yyyy', $arow->year . '-' . $arow->month); ?></a>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>
      <?php endif; ?>
   </div>
<?php endif; ?>