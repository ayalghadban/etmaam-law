<?php
   /**
    * mailer
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: mailer.tpl.php, v1.00 5/11/2023 6:31 PM Gewa Exp $
    *
    */

   use Wojo\Auth\Auth;
   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   if (!Auth::hasPrivileges('manage_newsletter')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<form method="post" id="wojo_form" name="wojo_form">
   <div class="wojo segment form">
      <div class="wojo fields">
         <div class="field disabled">
            <label><?php echo Language::$word->NL_FROM; ?></label>
            <input type="text" disabled placeholder="<?php echo Language::$word->NL_FROM; ?>"
                   value="<?php echo $this->core->site_email; ?>" name="site_email">
         </div>
         <div class="field">
            <label><?php echo Language::$word->NL_RCPT; ?>
               <i class="icon asterisk"></i>
            </label>
            <?php if (Validator::get('email')): ?>
               <input type="text" placeholder="<?php echo Language::$word->NL_RCPT; ?>"
                      value="<?php echo Validator::get('email'); ?>" readonly name="recipient">
            <?php else: ?>
               <select name="recipient">
                  <option value="all"><?php echo Language::$word->NL_UALL; ?></option>
                  <option value="free"><?php echo Language::$word->NL_UAREG; ?></option>
                  <option value="paid"><?php echo Language::$word->NL_UPAID; ?></option>
                  <option value="newsletter"><?php echo Language::$word->NL_UNLS; ?></option>
               </select>
            <?php endif; ?>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->NL_SUBJECT; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->NL_SUBJECT; ?>"
                   value="<?php echo $this->data->{'subject' . Language::$lang}; ?>" name="subject">
         </div>
         <div class="field">
            <label><?php echo Language::$word->NL_ATTACH; ?></label>
            <input type="file" name="attachment" id="attachment" class="filestyle" data-input="true">
         </div>
      </div>
      <div class="wojo fields">
         <div class="field basic">
            <label><?php echo Language::$word->NL_BODY; ?></label>
            <textarea name="body"
                      class="bodypost"><?php echo str_replace(array('[SITEURL]', '[LOGO]'), array(SITEURL, $this->core->plogo), $this->data->{'body' . Language::$lang}); ?></textarea>
            <p class="wojo small icon negative text">
               <i class="icon negative info sign"></i>
               <?php echo Language::$word->NOTEVAR; ?></p>
         </div>
      </div>
   </div>
   <div class="center-align margin-top">
      <button type="button" data-route="admin/mailer/action/" data-action="send" name="dosubmit"
              class="wojo primary button"><?php echo Language::$word->NL_SEND; ?></button>
   </div>
</form>