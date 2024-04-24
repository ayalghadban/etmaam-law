<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 5/18/2023 10:00 AM Gewa Exp $
    *
    */

   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   if (!$this->auth->checkPlugAcl('twitter')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<form method="post" id="wojo_form" name="wojo_form">
   <div class="wojo form segment margin-bottom">
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->_PLG_TW_KEY; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->_PLG_TW_KEY; ?>" value="<?php echo Utility::decode($this->row->consumer_key); ?>" name="consumer_key">
         </div>
         <div class="field">
            <label><?php echo Language::$word->_PLG_TW_SECRET; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->_PLG_TW_SECRET; ?>" value="<?php echo Utility::decode($this->row->consumer_secret); ?>" name="consumer_secret">
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->_PLG_TW_TOKEN; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->_PLG_TW_TOKEN; ?>" value="<?php echo Utility::decode($this->row->access_token); ?>" name="access_token">
         </div>
         <div class="field">
            <label><?php echo Language::$word->_PLG_TW_TSECRET; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->_PLG_TW_TSECRET; ?>" value="<?php echo Utility::decode($this->row->access_secret); ?>" name="access_secret">
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->_PLG_TW_USER; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->_PLG_TW_USER; ?>" value="<?php echo Utility::decode($this->row->username); ?>" name="username">
         </div>
         <div class="field">
            <label><?php echo Language::$word->_PLG_TW_SHOW_IMG; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="show_image" type="radio" value="1" id="show_image_1" <?php echo Validator::getChecked($this->row->show_image, 1); ?>>
               <label for="show_image_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="show_image" type="radio" value="0" id="show_image_0" <?php echo Validator::getChecked($this->row->show_image, 0); ?>>
               <label for="show_image_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->_PLG_TW_COUNT; ?></label>
            <input name="counter" type="range" min="1" max="20" step="1" value="<?php echo $this->row->counter; ?>" hidden data-suffix=" itm">
         </div>
         <div class="field">
            <label><?php echo Language::$word->_PLG_TW_TRANS; ?></label>
            <input name="speed" type="range" min="100" max="1200" step="100" value="<?php echo $this->row->speed; ?>" hidden data-suffix=" ms">
         </div>
         <div class="field">
            <label><?php echo Language::$word->_PLG_TW_TRANS_T; ?></label>
            <input name="timeout" type="range" min="1000" max="15000" step="1000" value="<?php echo $this->row->timeout; ?>" hidden data-suffix=" ms">
         </div>
      </div>
   </div>
   <div class="center-align">
      <a href="<?php echo Url::url('admin/plugins'); ?>" class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
      <button type="button" data-route="admin/plugins/twitter/action/" data-action="update" name="dosubmit" class="wojo primary button"><?php echo Language::$word->SAVECONFIG; ?></button>
   </div>
</form>