<?php
   /**
    * _event_item
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _event_item.tpl.php, v1.00 12/5/2023 1:24 PM Gewa Exp $
    *
    */

   use Wojo\Date\Date;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<?php foreach ($this->event as $row): ?>
   <div class="items">
      <div class="wojo basic card">
         <div class="margin-bottom">
            <div class="margin-small-bottom">
               <span class="wojo primary inverted label"><?php echo $row->year; ?></span>
               <span class="wojo primary inverted label"><?php echo Date::doDate('MMMM', $row->created); ?></span>
               <span class="wojo primary inverted label"><?php echo $row->venue; ?></span>
            </div>
            <h5><?php echo $row->title; ?></h5>
            <p class="text-size-small">
               <span><?php echo $row->contact; ?></span>
               |
               <span><?php echo $row->phone; ?></span>
            </p>
         </div>
         <div class="content shadow-hard"><?php echo $row->content; ?></div>
      </div>
   </div>
<?php endforeach; ?>