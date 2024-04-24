<?php
   /**
    * _maps_grid
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _maps_grid.tpl.php, v1.00 5/20/2023 10:52 PM Gewa Exp $
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
            <i class="icon plus alt"></i><?php echo Language::$word->_MOD_GM_NEW; ?></a>
      </div>
   </div>
<?php if (!$this->data): ?>
   <div class="center-align">
      <img src="<?php echo ADMINVIEW; ?>images/notfound.svg" alt="" class="wojo big inline image">
      <div class="margin-small-top">
         <p class="wojo small icon alert inverted attached compact message">
            <i class="icon exclamation triangle"></i><?php echo Language::$word->_MOD_GM_NOMAPS; ?></p>
      </div>
   </div>
<?php else: ?>
   <div class="row grid phone-1 mobile-1 tablet-2 screen-3 gutters justify-center">
      <?php foreach ($this->data as $row): ?>
         <div class="columns" id="item_<?php echo $row->id; ?>">
            <div class="wojo framed card">
               <div class="header">
                  <h4>
                     <a href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>"><?php echo $row->name; ?></a>
                  </h4>
                  <p><?php echo $row->body; ?></p>
               </div>
               <div class="content center-align">
                  <a href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>"
                     class="wojo primary inverted icon button">
                     <i class="icon pencil"></i>
                  </a>
                  <a data-set='{"option":[{"action": "delete","title": "<?php echo Validator::sanitize($row->name, 'chars'); ?>","id":<?php echo $row->id; ?>}],"action":"delete","parent":"#item_<?php echo $row->id; ?>","url":"modules/maps/action/"}'
                    class="wojo negative inverted icon button data">
                     <i class="icon trash"></i>
                  </a>
               </div>
            </div>
         </div>
      <?php endforeach; ?>
   </div>
<?php endif; ?>