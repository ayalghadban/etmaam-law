<?php
   /**
    * membershipError
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: membershipError.tpl.php, v1.00 6/11/2023 2:35 PM Gewa Exp $
    *
    */

   use Wojo\Language\Language;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="flex justify-center align-middle flex-column width-full min-height-full padding-vertical">
   <div class="wojo negative relaxed icon message">
      <i class="icon large lock"></i>
      <div class="content">
         <div class="header"><?php echo Language::$word->FRT_MERROR; ?></div>
         <p><?php echo Language::$word->FRT_MERROR_2; ?></p>
      </div>
   </div>

   <?php if ($this->data): ?>
      <div class="wojo segment">
         <ul class="wojo styled chevron primary list">
            <?php foreach ($this->data as $row): ?>
               <li><?php echo $row->title; ?></li>
            <?php endforeach; ?>
         </ul>
      </div>
   <?php endif; ?>
</div>
