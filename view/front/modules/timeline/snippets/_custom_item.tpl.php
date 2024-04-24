<?php
   /**
    * _custom_item
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _custom_item.tpl.php, v1.00 12/5/2023 2:29 PM Gewa Exp $
    *
    */

   use Wojo\Date\Date;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<?php foreach ($this->custom as $row): ?>
   <div class="items">
      <div class="wojo basic card">
         <div class="header divided">
            <div class="margin-small-bottom">
               <span class="wojo primary inverted label"><?php echo $row->year; ?></span>
               <span class="wojo primary inverted label"><?php echo Date::doDate('MMMM', $row->created); ?></span>
            </div>
            <h5 class="basic">
               <?php echo $row->title; ?>
            </h5>
         </div>
         <?php if (isset($row->thumb)): ?>
            <?php if (count($row->thumb) > 1): ?>
               <div class="wojo carousel" data-wcarousel='{"autoplay":false,"dots":false,"loop":true, "arrows": true}'>
                  <?php foreach ($row->thumb as $img): ?>
                     <img src="<?php echo $img; ?>" alt="<?php echo $row->title; ?>" class="wojo rounded image">
                  <?php endforeach; ?>
               </div>
            <?php else: ?>
               <img src="<?php echo $row->thumb[0]; ?>" alt="<?php echo $row->title; ?>" class="wojo rounded image">
            <?php endif; ?>
         <?php endif; ?>
         <?php if(strlen($row->dataurl ?? '') !== 0):?>
            <iframe src="<?php echo $row->dataurl; ?>" class="border-1 border-color-info-inverted rounded height<?php echo $row->height; ?>"></iframe>
         <?php endif;?>
         <?php if (strlen($row->content ?? '') !== 0): ?>
            <div class="content shadow-hard"><?php echo $row->content; ?></div>
         <?php endif; ?>
      </div>
   </div>
<?php endforeach; ?>