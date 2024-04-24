<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 6/13/2023 10:13 PM Gewa Exp $
    *
    */
   
   use Wojo\Language\Language;
   
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="wojo segment form">
   <form id="wojo_form_newsletter" name="wojo_form_newsletter" method="post">
      <div class="wojo block fields">
         <div class="field">
            <input type="text" placeholder="<?php echo Language::$word->M_EMAIL; ?>" name="email">
         </div>
      </div>
      <div class="field">
         <button type="button" data-url="plugin/newsletter/action/" data-hide="true" data-action="process" name="dosubmit" class="wojo primary button"><?php echo Language::$word->SUBMIT; ?></button>
      </div>
   </form>
</div>