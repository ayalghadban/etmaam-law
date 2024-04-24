<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 6/14/2023 11:50 AM Gewa Exp $
    *
    */
   
   use Wojo\Core\Session;
   use Wojo\Language\Language;
   use Wojo\Url\Url;
   
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="wojo form">
   <form id="wojo_form" name="wojo_form" method="post">
      <div class="wojo block fields">
         <div class="field">
            <label><?php echo Language::$word->NAME; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->NAME; ?>" value="<?php if ($this->auth->is_User()) {
               echo $this->auth->name;
            } ?>" name="name">
         </div>
         <div class="field">
            <label><?php echo Language::$word->M_EMAIL; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->M_EMAIL; ?>" value="<?php if ($this->auth->is_User()) {
               echo $this->auth->email;
            } ?>" name="email">
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->M_PHONE; ?>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->M_PHONE; ?>" name="phone">
         </div>
         <div class="field">
            <label><?php echo Language::$word->ET_SUBJECT; ?>
            </label>
            <select name="subject">
               <option value=""><?php echo Language::$word->CF_SUBJECT_1; ?></option>
               <option value="<?php echo Language::$word->CF_SUBJECT_2; ?>"><?php echo Language::$word->CF_SUBJECT_2; ?></option>
               <option value="<?php echo Language::$word->CF_SUBJECT_3; ?>"><?php echo Language::$word->CF_SUBJECT_3; ?></option>
               <option value="<?php echo Language::$word->CF_SUBJECT_4; ?>"><?php echo Language::$word->CF_SUBJECT_4; ?></option>
               <option value="<?php echo Language::$word->CF_SUBJECT_5; ?>"><?php echo Language::$word->CF_SUBJECT_5; ?></option>
               <option value="<?php echo Language::$word->CF_SUBJECT_6; ?>"><?php echo Language::$word->CF_SUBJECT_6; ?></option>
               <option value="<?php echo Language::$word->CF_SUBJECT_7; ?>"><?php echo Language::$word->CF_SUBJECT_7; ?></option>
                <option value="<?php echo Language::$word->CF_SUBJECT_7; ?>"><?php echo Language::$word->CF_SUBJECT_8; ?></option>
               <option value="<?php echo Language::$word->CF_SUBJECT_7; ?>"><?php echo Language::$word->CF_SUBJECT_9; ?></option>
               <option value="<?php echo Language::$word->CF_SUBJECT_7; ?>"><?php echo Language::$word->CF_SUBJECT_10; ?></option>
               <option value="<?php echo Language::$word->CF_SUBJECT_7; ?>"><?php echo Language::$word->CF_SUBJECT_11; ?></option>
               <option value="<?php echo Language::$word->CF_SUBJECT_7; ?>"><?php echo Language::$word->CF_SUBJECT_12; ?></option>
               <option value="<?php echo Language::$word->CF_SUBJECT_7; ?>"><?php echo Language::$word->CF_SUBJECT_13; ?></option>
               <option value="<?php echo Language::$word->CF_SUBJECT_7; ?>"><?php echo Language::$word->CF_SUBJECT_14; ?></option>
               <option value="<?php echo Language::$word->CF_SUBJECT_7; ?>"><?php echo Language::$word->CF_SUBJECT_15; ?></option>
               <option value="<?php echo Language::$word->CF_SUBJECT_7; ?>"><?php echo Language::$word->CF_SUBJECT_16; ?></option>
               <option value="<?php echo Language::$word->CF_SUBJECT_7; ?>"><?php echo Language::$word->CF_SUBJECT_17; ?></option>
               <option value="<?php echo Language::$word->CF_SUBJECT_7; ?>"><?php echo Language::$word->CF_SUBJECT_18; ?></option>
               <option value="<?php echo Language::$word->CF_SUBJECT_7; ?>"><?php echo Language::$word->CF_SUBJECT_19; ?></option>
               <option value="<?php echo Language::$word->CF_SUBJECT_7; ?>"><?php echo Language::$word->CF_SUBJECT_20; ?></option>
               <option value="<?php echo Language::$word->CF_SUBJECT_7; ?>"><?php echo Language::$word->CF_SUBJECT_21; ?></option>

            </select>
         </div>
      </div>
      <div class="wojo block fields">
         <div class="field">
            <label><?php echo Language::$word->MESSAGE; ?>
               <i class="icon asterisk"></i>
            </label>
            <textarea class="small" placeholder="<?php echo Language::$word->MESSAGE; ?>" name="notes"></textarea>
         </div>
         <div class="field">
            <label><?php echo Language::$word->CAPTCHA; ?>
               <i class="icon asterisk"></i>
            </label>
            <div class="wojo right labeled fluid input">
               <input name="captcha" placeholder="<?php echo Language::$word->CAPTCHA; ?>" type="text">
               <div class="wojo simple passive button captcha"><?php echo Session::captcha(); ?></div>
            </div>
         </div>
         <div class="field">
            <div class="wojo checkbox">
               <input name="agree" type="checkbox" value="1" id="agree">
               <label for="agree">
                  <a href="<?php echo Url::url($this->core->system_slugs->policy[0]->{'slug' . Language::$lang}); ?>" class="secondary">
                     <small><?php echo Language::$word->AGREE; ?></small>
                  </a>
               </label>
            </div>
         </div>
      </div>
      <div class="field">
         <button type="button" data-hide="true" data-url="ajax/" data-action="contact" name="dosubmit" class="wojo primary fluid button"><?php echo Language::$word->CF_SEND; ?></button>
      </div>
   </form>
</div>