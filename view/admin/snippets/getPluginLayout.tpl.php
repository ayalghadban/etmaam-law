<?php
   /**
    * getPluginLayout
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: getPluginLayout.tpl.php, v1.00 5/12/2023 3:13 PM Gewa Exp $
    *
    */

   use Wojo\Language\Language;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="poplayout min-width300">
   <form class="layform" name="layform">
      <?php if ($this->data): ?>
         <div data-section="<?php echo $this->section; ?>" class="wojo very relaxed list">
            <?php foreach ($this->data as $row): ?>
               <div class="item">
                  <div class="content">
                     <div class="text-size-small header" data-id="<?php echo $row->id; ?>"><?php echo $row->title; ?></div>
                     <div class="description margin-top">
                        <input type="range" min="1" max="10" step="1" name="space[<?php echo $row->id; ?>]"
                               value="<?php echo $row->space; ?>" hidden data-suffix=" sp" class="rangeslider"
                               data-type="labels" data-labels="1,2,3,4,5,6,7,8,9,10">
                     </div>
                  </div>
               </div>
               <div class="wojo space divider"></div>
            <?php endforeach; ?>
         </div>
      <?php endif; ?>
   </form>
   <div class="wojo double divider"></div>
   <div class="center-align">
      <button class="wojo small primary button update"><?php echo Language::$word->UPDATE; ?></button>
   </div>
</div>