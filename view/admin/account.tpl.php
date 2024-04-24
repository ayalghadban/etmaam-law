<?php
   /**
    * account
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: account.tpl.php, v1.00 4/29/2023 9:05 AM Gewa Exp $
    *
    */

   use Wojo\Date\Date;
   use Wojo\Language\Language;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<form method="post" id="wojo_form" name="wojo_form">
   <div class="row gutters">
      <div class="columns mobile-100 mobile-order-2 phone-order-2">
         <div class="wojo simple segment form">
            <div class="wojo fields align-middle">
               <div class="field four wide labeled">
                  <label><?php echo Language::$word->M_FNAME; ?>
                     <i class="icon asterisk"></i></label>
               </div>
               <div class="field">
                  <input type="text" value="<?php echo $this->data->fname; ?>" name="fname">
               </div>
            </div>
            <div class="wojo fields align-middle">
               <div class="field four wide labeled">
                  <label><?php echo Language::$word->M_LNAME; ?>
                     <i class="icon asterisk"></i></label>
               </div>
               <div class="field">
                  <input type="text" value="<?php echo $this->data->lname; ?>" name="lname">
               </div>
            </div>
            <div class="wojo fields align-middle">
               <div class="field four wide labeled">
                  <label><?php echo Language::$word->M_EMAIL; ?>
                     <i class="icon asterisk"></i></label>
               </div>
               <div class="field">
                  <input type="text" value="<?php echo $this->data->email; ?>" name="email">
               </div>
            </div>
            <?php echo $this->custom_fields; ?>
            <div class="wojo fields disabled align-middle">
               <div class="field four wide labeled">
                  <label><?php echo Language::$word->CREATED; ?></label>
               </div>
               <div class="field">
                  <input type="text" value="<?php echo Date::doDate('short_date', $this->data->created); ?>" readonly>
               </div>
            </div>
            <div class="wojo fields disabled align-middle">
               <div class="field four wide labeled">
                  <label><?php echo Language::$word->M_LASTLOGIN; ?></label>
               </div>
               <div class="field">
                  <input type="text" value="<?php echo Date::doDate('short_date', $this->data->lastlogin); ?>">
               </div>
            </div>
            <div class="wojo fields disabled align-middle">
               <div class="field four wide labeled">
                  <label><?php echo Language::$word->M_LASTIP; ?></label>
               </div>
               <div class="field">
                  <input type="text" value="<?php echo $this->data->lastip; ?>">
               </div>
            </div>
         </div>
      </div>
      <div class="columns auto mobile-100 phone-100 mobile-order-1 phone-order-1">
         <div class="wojo simple segment form">
            <div class="basic field">
               <label class="justify-center"><?php echo Language::$word->AVATAR; ?></label>
               <input type="file" name="avatar" data-type="image"
                      data-exist="<?php echo ($this->data->avatar)? UPLOADURL . 'avatars/' . $this->data->avatar : UPLOADURL . 'avatars/default.svg'; ?>"
                      accept="image/png, image/jpeg">
            </div>
         </div>
      </div>
   </div>
   <div class="center-align">
      <button type="button" data-route="admin/account/" data-action="update" name="dosubmit"
              class="wojo primary button"><?php echo Language::$word->M_UPDATE; ?></button>
   </div>
</form>