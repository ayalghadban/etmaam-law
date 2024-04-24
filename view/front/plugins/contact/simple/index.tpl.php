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
   <form id="wojo_form_bcontact" name="wojo_form_bcontact" method="post">
      <div class="wojo fields">
         <div class="field">
            <div class="wojo basic input">
               <input type="text" placeholder="<?php echo Language::$word->NAME; ?>" value="<?php if ($this->auth->is_User()) {
                  echo $this->auth->name;
               } ?>" name="name">
            </div>
         </div>
         <div class="field">
            <div class="wojo basic input">
               <input type="text" placeholder="<?php echo Language::$word->M_EMAIL; ?>" value="<?php if ($this->auth->is_User()) {
                  echo $this->auth->email;
               } ?>" name="email">
            </div>
         </div>
      </div>
      <div class="wojo block fields">
         <div class="field">
            <div class="wojo basic input">
               <textarea class="small" placeholder="<?php echo Language::$word->MESSAGE; ?>" name="notes"></textarea>
            </div>
         </div>
         <div class="field">
            <div class="wojo basic input">
               <input name="captcha" placeholder="<?php echo Language::$word->CAPTCHA; ?>" type="text">
               <div class="wojo simple passive button captcha"><?php echo Session::captcha(); ?></div>
            </div>
         </div>
         <div class="field basic">
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
      <button type="button" data-hide="true" data-action="processContact" name="dosubmit" class="wojo primary fluid button"><?php echo Language::$word->CF_SEND; ?></button>
      <input type="hidden" name="subject" value="<?php echo Language::$word->CF_SUBJECT_1; ?>">
   </form>
</div>