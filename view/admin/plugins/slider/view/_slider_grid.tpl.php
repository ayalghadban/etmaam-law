<?php
   /**
    * _slider_grid
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _slider_grid.tpl.php, v1.00 5/18/2023 8:21 PM Gewa Exp $
    *
    */

   use Wojo\Core\Router;
   use Wojo\Language\Language;
   use Wojo\Url\Url;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
   <div class="row gutters justify-end">
      <div class="columns auto mobile-100 phone-100">
         <a href="<?php echo Url::url(Router::$path, 'new/'); ?>" class="wojo small secondary fluid button">
            <i class="icon plus alt"></i><?php echo Language::$word->_PLG_SL_SUB7; ?></a>
      </div>
   </div>
<?php if (!$this->data): ?>
   <div class="center-align">
      <img src="<?php echo ADMINVIEW; ?>images/notfound.svg" alt="" class="wojo big inline image">
      <div class="margin-small-top">
         <p class="wojo small icon alert inverted attached compact message">
            <i class="icon exclamation triangle"></i><?php echo Language::$word->_PLG_SL_NOSLIDER; ?></p>
      </div>
   </div>
<?php else: ?>
   <div class="row grid phone-1 mobile-1 tablet-2 screen-2 gutters justify-center">
      <?php foreach ($this->data as $row): ?>
         <div class="columns" id="item_<?php echo $row->id; ?>">
            <div class="wojo attached framed card">
               <div class="content center-align">
                  <div class="margin-small-bottom">
                     <img src="<?php echo APLUGINURL . 'slider/view/images/' . $row->layout; ?>.png"
                          class="wojo inline image" alt="">
                  </div>
                  <h5 class="basic"><?php echo $row->title; ?></h5>
               </div>
               <div class="divided footer center-align">
                  <a href="<?php echo Url::url(Router::$path . '/edit', $row->id); ?>"
                     class="wojo icon primary inverted button">
                     <i class="icon pencil"></i>
                  </a>
                  <a data-set='{"option":[{"action": "delete","title": "<?php echo Validator::sanitize($row->title, 'chars'); ?>","id":<?php echo $row->id; ?>, "type":"slider"}],"action":"delete","parent":"#item_<?php echo $row->id; ?>", "url":"plugins/slider/action/"}'
                     class="wojo icon negative inverted button data">
                     <i class="icon trash"></i>
                  </a>
               </div>
            </div>
         </div>
      <?php endforeach; ?>
   </div>
<?php endif; ?>