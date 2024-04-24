<?php
   /**
    * registerError
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: registerError.tpl.php, v1.00 6/11/2023 2:16 PM Gewa Exp $
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
         <div class="header" class=""><?php echo Language::$word->FRT_MERROR; ?></div>
         <p><?php echo Language::$word->FRT_MERROR_1; ?></p>
      </div>
   </div>
</div>