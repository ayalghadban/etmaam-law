<?php
   /**
    * _layout_archive
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _layout_archive.tpl.php, v1.00 10/20/2023 2:26 PM Gewa Exp $
    *
    */

   use Wojo\Date\Date;
   use Wojo\Language\Language;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
   <h3 class="margin-bottom"><?php echo Language::$word->_MOD_AM_SUB42; ?>
      <small class="text-size-small"><?php echo Date::doDate('yyyy, MMMM', $this->segments[2]); ?></small>
   </h3>
<?php if (!$this->rows): ?>
   <div class="center-align">
      <img src="<?php echo ADMINVIEW; ?>images/notfound.svg" alt="" class="wojo big inline image">
      <div class="margin-small-top">
         <p class="wojo small icon alert inverted attached compact message">
            <i class="icon exclamation triangle"></i>
            <?php echo Language::$word->_MOD_AM_SUB48; ?></p>
      </div>
   </div>
<?php else: ?>
   <div class="wojo celled relaxed list">
      <?php foreach ($this->rows as $row): ?>
         <div class="item align-middle">
            <div class="content">
               <a href="<?php echo Url::url($this->core->modname['blog'], $row->slug); ?>"><?php echo $row->title; ?></a>
            </div>
            <div class="content auto">
               <span class="wojo small primary label"><?php echo Date::doDate('dd', $row->created); ?></span>
            </div>
         </div>
      <?php endforeach; ?>
   </div>
<?php endif; ?>