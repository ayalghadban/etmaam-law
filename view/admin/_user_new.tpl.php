<?php
   /**
    * _user_new
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _user_new.tpl.php, v1.00 5/9/2023 10:38 PM Gewa Exp $
    *
    */

   use Wojo\Date\Date;
   use Wojo\Language\Language;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<form method="post" id="wojo_form" name="wojo_form">
   <div class="wojo simple segment form">
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->M_FNAME; ?>
               <i class="icon asterisk"></i>
            </label>
            <div class="wojo large basic input">
               <input type="text" placeholder="<?php echo Language::$word->M_FNAME; ?>" name="fname">
            </div>
         </div>
         <div class="field five wide">
            <label><?php echo Language::$word->M_LNAME; ?>
               <i class="icon asterisk"></i>
            </label>
            <div class="wojo large basic input">
               <input type="text" placeholder="<?php echo Language::$word->M_LNAME; ?>" name="lname">
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->M_EMAIL; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->M_EMAIL; ?>" name="email">
         </div>
         <div class="field">
            <label><?php echo Language::$word->NEWPASS; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" name="password">
         </div>
      </div>
      <div class="wojo fields align-middle">
         <div class="field">
            <label><?php echo Language::$word->M_SUB8; ?></label>
            <select name="membership_id">
               <option value="0">-/-</option>
               <?php echo Utility::loopOptions($this->mlist, 'id', 'title' . Language::$lang); ?>
            </select>
         </div>
         <div class="field auto">
            <label>&nbsp;</label>
            <div class="wojo checkbox toggle fitted inline">
               <input name="update_membership" type="checkbox" value="1" id="update_membership">
               <label for="update_membership"><?php echo Language::$word->YES; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->M_SUB15; ?></label>
            <div class="wojo icon input" data-datepicker="true">
               <input placeholder="<?php echo Language::$word->TO; ?>" name="mem_expire" type="text"
                      value="<?php echo Date::doDate('calendar', Date::numberOfDays('+ 30 day')); ?>" readonly
                      class="datepick">
               <i class="icon calendar alt"></i>
            </div>
         </div>
         <div class="field auto">
            <label>&nbsp;</label>
            <div class="wojo checkbox toggle fitted inline">
               <input name="extend_membership" type="checkbox" value="1" id="extend_membership">
               <label for="extend_membership"><?php echo Language::$word->YES; ?></label>
            </div>
         </div>
      </div>
      <div class="padding-top">
         <h5><?php echo Language::$word->CF_TITLE; ?></h5>
         <?php echo $this->custom_fields; ?></div>
      <div class="wojo auto very wide divider"></div>
      <div class="wojo fields align-middle">
         <div class="field four wide labeled">
            <label><?php echo Language::$word->M_ADDRESS; ?></label>
         </div>
         <div class="field">
            <input type="text" placeholder="<?php echo Language::$word->M_ADDRESS; ?>" name="address">
         </div>
      </div>
      <div class="wojo fields align-middle">
         <div class="field four wide labeled">
            <label><?php echo Language::$word->M_CITY; ?></label>
         </div>
         <div class="field">
            <input type="text" placeholder="<?php echo Language::$word->M_CITY; ?>" name="city">
         </div>
      </div>
      <div class="wojo fields align-middle">
         <div class="field four wide labeled">
            <label><?php echo Language::$word->M_STATE; ?></label>
         </div>
         <div class="field">
            <input type="text" placeholder="<?php echo Language::$word->M_STATE; ?>" name="state">
         </div>
      </div>
      <div class="wojo fields align-middle">
         <div class="field four wide labeled">
            <label><?php echo Language::$word->M_COUNTRY; ?>/<?php echo Language::$word->M_ZIP; ?></label>
         </div>
         <div class="field">
            <input type="text" placeholder="<?php echo Language::$word->M_ZIP; ?>" name="zip">
         </div>
         <div class="field">
            <select name="country">
               <option value="">-/-</option>
               <?php echo Utility::loopOptions($this->clist, 'abbr', 'name'); ?>
            </select>
         </div>
      </div>
      <div class="wojo auto very wide divider"></div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->STATUS; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="active" type="radio" value="y" id="active_y" checked="checked">
               <label for="active_y"><?php echo Language::$word->ACTIVE; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="active" type="radio" value="n" id="active_n">
               <label for="active_n"><?php echo Language::$word->INACTIVE; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="active" type="radio" value="t" id="active_t">
               <label for="active_t"><?php echo Language::$word->PENDING; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="active" type="radio" value="b" id="active_b">
               <label for="active_b"><?php echo Language::$word->BANNED; ?></label>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->M_SUB9; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="type" type="radio" value="staff" id="type_staff">
               <label for="type_staff"><?php echo Language::$word->STAFF; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="type" type="radio" value="editor" id="type_editor">
               <label for="type_editor"><?php echo Language::$word->EDITOR; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="type" type="radio" value="member" id="type_member" checked="checked">
               <label for="type_member"><?php echo Language::$word->MEMBER; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->M_SUB10; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="newsletter" type="radio" value="1" id="newsletter_1">
               <label for="newsletter_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="newsletter" type="radio" value="0" id="newsletter_0" checked="checked">
               <label for="newsletter_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->M_SUB13; ?></label>
            <div class="wojo checkbox toggle inline">
               <input name="notify" type="checkbox" value="1" id="notify_0">
               <label for="notify_0"><?php echo Language::$word->YES; ?></label>
            </div>
         </div>
      </div>
      <textarea placeholder="<?php echo Language::$word->M_SUB11; ?>" name="notes"></textarea>
   </div>
   <div class="center-align margin-top">
      <a href="<?php echo Url::url('admin/users'); ?>"
         class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
      <button type="button" data-route="admin/users/action/" data-action="add" name="dosubmit"
              class="wojo primary button"><?php echo Language::$word->M_TITLE5; ?></button>
   </div>
</form>