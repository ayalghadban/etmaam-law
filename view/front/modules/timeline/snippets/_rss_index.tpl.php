<?php
   /**
    * _rss_index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @var object $settings
    * @var object $result
    * @version 6.20: _rss_index.tpl.php, v1.00 12/5/2023 1:50 PM Gewa Exp $
    *
    */

   use Wojo\Date\Date;
   use Wojo\Language\Language;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<h3><?php echo $settings->name; ?></h3>
<div id="timeline" class="mason big <?php echo ($settings->colmode == 'dual')? 'two' : 'one'; ?>">
   <?php foreach ($result as $row): ?>
      <div class="items">
         <div class="wojo basic card">
            <div class="margin-bottom">
               <div class="margin-small-bottom">
                  <span class="wojo primary inverted label"><?php echo $row->year; ?></span>
                  <span class="wojo primary inverted label"><?php echo Date::doDate('MMMM', $row->timedate); ?></span>
               </div>
               <h5 class="basic"><?php echo $row->title; ?></h5>
            </div>
            <div class="content shadow-hard"><?php echo $row->content; ?>
               <div class="text-size-small text-weight-500 margin-small-top">
                  <a href="<?php echo $row->more; ?>" class="icon-text right"><?php echo Language::$word->CONTINUE_R; ?>
                     <i class="icon three dots"></i>
                  </a>
               </div>
            </div>

         </div>
      </div>
   <?php endforeach; ?>