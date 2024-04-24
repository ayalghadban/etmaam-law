<?php
   /**
    * _user_edit
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _user_edit.tpl.php, v1.00 5/10/2023 9:19 AM Gewa Exp $
    *
    */

   use Wojo\Auth\Auth;
   use Wojo\Date\Date;
   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   if (!Auth::hasPrivileges('edit_user')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<form method="post" id="wojo_form" name="wojo_form">
   <div class="wojo segment form">
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->M_FNAME; ?>
               <i class="icon asterisk"></i>
            </label>
            <div class="wojo large basic input">
               <input type="text" placeholder="<?php echo Language::$word->M_FNAME; ?>" value="<?php echo $this->data->fname; ?>"
                      name="fname">
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->M_LNAME; ?>
               <i class="icon asterisk"></i>
            </label>
            <div class="wojo large basic input">
               <input type="text" placeholder="<?php echo Language::$word->M_LNAME; ?>" value="<?php echo $this->data->lname; ?>"
                      name="lname">
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->M_EMAIL; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->M_EMAIL; ?>" value="<?php echo $this->data->email; ?>"
                   name="email">
         </div>
         <div class="field">
            <label><?php echo Language::$word->NEWPASS; ?></label>
            <input type="text" name="password">
         </div>
      </div>
      <div class="wojo fields align-middle">
         <div class="field">
            <label><?php echo Language::$word->M_SUB8; ?></label>
            <select name="membership_id">
               <option value="0">-/-</option>
               <?php echo Utility::loopOptions($this->mlist, 'id', 'title' . Language::$lang, $this->data->membership_id); ?>
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
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->M_SUB17; ?></label>
            <a data-wdropdown="#modaccess" class="wojo secondary right button"><?php echo Language::$word->MODULES; ?>
               <i class="icon chevron down"></i>
            </a>
            <div class="wojo static dropdown small pointing top-left" id="modaccess">
               <div class="max-width400">
                  <div class="row grid phone-1 mobile-1 tablet-2 screen-2">
                     <?php echo Utility::loopOptionsMultiple($this->modlist, 'modalias', 'title' . Language::$lang, $this->data->modaccess, 'modaccess'); ?>
                  </div>
               </div>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->M_SUB18; ?></label>
            <a data-wdropdown="#plugaccess" class="wojo secondary right button"><?php echo Language::$word->PLUGINS; ?>
               <i class="icon chevron down"></i>
            </a>
            <div class="wojo static dropdown small pointing top-left" id="plugaccess">
               <div class="max-width400">
                  <div class="row grid phone-1 mobile-1 tablet-2 screen-2">
                     <?php echo Utility::loopOptionsMultiple($this->pluglist, 'plugalias', 'title' . Language::$lang, $this->data->plugaccess, 'plugaccess'); ?>
                  </div>
               </div>
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
            <input type="text" placeholder="<?php echo Language::$word->M_ADDRESS; ?>"
                   value="<?php echo $this->data->address; ?>" name="address">
         </div>
      </div>
      <div class="wojo fields align-middle">
         <div class="field four wide labeled">
            <label><?php echo Language::$word->M_CITY; ?></label>
         </div>
         <div class="field">
            <input type="text" placeholder="<?php echo Language::$word->M_CITY; ?>" value="<?php echo $this->data->city; ?>"
                   name="city">
         </div>
      </div>
      <div class="wojo fields align-middle">
         <div class="field four wide labeled">
            <label><?php echo Language::$word->M_STATE; ?></label>
         </div>
         <div class="field">
            <input type="text" placeholder="<?php echo Language::$word->M_STATE; ?>" value="<?php echo $this->data->state; ?>"
                   name="state">
         </div>
      </div>
      <div class="wojo fields align-middle">
         <div class="field four wide labeled">
            <label><?php echo Language::$word->M_COUNTRY; ?>/<?php echo Language::$word->M_ZIP; ?></label>
         </div>
         <div class="field">
            <input type="text" placeholder="<?php echo Language::$word->M_ZIP; ?>" value="<?php echo $this->data->zip; ?>"
                   name="zip">
         </div>
         <div class="field">
            <select name="country">
               <option value="">-/-</option>
               <?php echo Utility::loopOptions($this->clist, 'abbr', 'name', $this->data->country); ?>
            </select>
         </div>
      </div>
      <div class="wojo auto very wide divider"></div>
      <div class="wojo fields">
         <div class="field four wide">
            <div class="field">
               <label><?php echo Language::$word->CREATED; ?></label>
               <?php echo Date::doDate('long_date', $this->data->created); ?>
            </div>
            <div class="field">
               <label><?php echo Language::$word->M_LASTLOGIN; ?></label>
               <?php echo $this->data->lastlogin? Date::doDate('long_date', $this->data->lastlogin) : Language::$word->NEVER; ?>
            </div>
            <div class="field">
               <label><?php echo Language::$word->M_LASTIP; ?></label>
               <?php echo $this->data->lastip; ?>
            </div>
         </div>
         <div class="field six wide">
            <div class="fitted field">
               <label><?php echo Language::$word->STATUS; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="active" type="radio" value="y"
                         id="active_y" <?php echo Validator::getChecked($this->data->active, 'y'); ?>>
                  <label for="active_y"><?php echo Language::$word->ACTIVE; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="active" type="radio" value="n"
                         id="active_n" <?php echo Validator::getChecked($this->data->active, 'n'); ?>>
                  <label for="active_n"><?php echo Language::$word->INACTIVE; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="active" type="radio" value="t"
                         id="active_t" <?php echo Validator::getChecked($this->data->active, 't'); ?>>
                  <label for="active_t"><?php echo Language::$word->PENDING; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="active" type="radio" value="b"
                         id="active_b" <?php echo Validator::getChecked($this->data->active, 'b'); ?>>
                  <label for="active_b"><?php echo Language::$word->BANNED; ?></label>
               </div>
            </div>
            <div class="fitted field">
               <label><?php echo Language::$word->M_SUB9; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="type" type="radio" value="staff"
                         id="type_staff" <?php echo Validator::getChecked($this->data->type, 'staff'); ?>>
                  <label for="type_staff"><?php echo Language::$word->STAFF; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="type" type="radio" value="editor"
                         id="type_editor" <?php echo Validator::getChecked($this->data->type, 'editor'); ?>>
                  <label for="type_editor"><?php echo Language::$word->EDITOR; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="type" type="radio" value="member"
                         id="type_member" <?php echo Validator::getChecked($this->data->type, 'member'); ?>>
                  <label for="type_member"><?php echo Language::$word->MEMBER; ?></label>
               </div>
            </div>
            <div class="fitted field">
               <label><?php echo Language::$word->M_SUB10; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="newsletter" type="radio" value="1"
                         id="newsletter_1" <?php echo Validator::getChecked($this->data->newsletter, 1); ?>>
                  <label for="newsletter_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="newsletter" type="radio" value="0"
                         id="newsletter_0" <?php echo Validator::getChecked($this->data->newsletter, 0); ?>>
                  <label for="newsletter_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
         </div>
      </div>
      <textarea placeholder="<?php echo Language::$word->M_SUB11; ?>"
                name="notes"><?php echo $this->data->notes; ?></textarea>
   </div>
   <div class="center-align margin-top">
      <a href="<?php echo Url::url('admin/users'); ?>"
         class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
      <button type="button" data-route="admin/users/action/" data-action="update" name="dosubmit"
              class="wojo primary button"><?php echo Language::$word->M_UPDATE; ?></button>
   </div>
   <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
</form>