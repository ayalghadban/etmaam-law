<?php
   /**
    * replyForm
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: replyForm.tpl.php, v1.00 4/29/2023 9:05 AM Gewa Exp $
    *
    */

   use Wojo\Language\Language;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

?>
<?php if ($this->settings->public_access or $this->auth->logged_in): ?>
   <div class="wojo small form segment form hide-all" id="replyform">
      <div class="wojo small block fields">
         <div class="field">
            <?php if ($this->auth->logged_in): ?>
               <input type="hidden" name="replayname" value="<?php echo $this->auth->uid; ?>">
            <?php else: ?>
               <input name="replayname" placeholder="<?php echo Language::$word->NAME; ?>" type="text">
            <?php endif; ?>
         </div>
         <div class="field">
            <textarea data-counter="<?php echo $this->settings->char_limit; ?>" id="replybody" placeholder="<?php echo Language::$word->MESSAGE; ?>" name="replybody"></textarea>
         </div>
      </div>
      <p class="wojo mini text replybody_counter"><?php echo Language::$word->_MOD_CM_CHAR . ' <span class="wojo positive text">' . $this->settings->char_limit . '</span>'; ?></p>
      <button type="button" name="doReply" class="wojo small primary button"><?php echo Language::$word->SUBMIT; ?></button>
   </div>
<?php else: ?>
   <p id="pError" class="wojo small negative text"><?php echo Language::$word->_MOD_CM_SUB1; ?></p>
<?php endif; ?>
