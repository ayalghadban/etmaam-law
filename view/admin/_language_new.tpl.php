<?php
   /**
    * _language_new
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _language_new.tpl.php, v1.00 5/8/2023 9:18 AM Gewa Exp $
    *
    */

   use Wojo\Language\Language;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<form method="post" id="wojo_form" name="wojo_form">
   <div class="wojo form">
      <div class="wojo fields align-middle">
         <div class="field four wide labeled">
            <label><?php echo Language::$word->LG_NAME; ?>
               <i class="icon asterisk"></i></label>
         </div>
         <div class="field">
            <input type="text" placeholder="<?php echo Language::$word->LG_NAME; ?>" name="name">
         </div>
      </div>
      <div class="wojo fields align-middle">
         <div class="field four wide labeled">
            <label><?php echo Language::$word->LG_AUTHOR; ?></label>
         </div>
         <div class="field">
            <input type="text" placeholder="<?php echo Language::$word->LG_AUTHOR; ?>" name="author">
         </div>
      </div>
      <div class="wojo fields align-middle">
         <div class="field four wide labeled">
            <label><?php echo Language::$word->LG_COLOR; ?>
               <i class="icon asterisk"></i></label>
         </div>
         <div class="field">
            <input type="text" data-wcolor="simple" name="color" data-color='{"format":"hex","color": "#7f8c8d"}' readonly>
         </div>
      </div>
      <div class="wojo fields align-middle">
         <div class="field four wide labeled">
            <label><?php echo Language::$word->LG_ABBR; ?>
               <i class="icon asterisk"></i></label>
         </div>
         <div class="field">
            <input type="text" placeholder="<?php echo Language::$word->LG_ABBR; ?>" name="abbr">
         </div>
      </div>
   </div>
   <div class="center-align">
      <a href="<?php echo Url::url('admin/languages'); ?>"
         class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
      <button type="button" data-route="admin/languages/action/" data-action="new" name="dosubmit"
              class="wojo primary button"><?php echo Language::$word->LG_SUB5; ?></button>
   </div>
</form>
