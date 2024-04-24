<?php
   /**
    * password
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: password.tpl.php, v1.00 6/29/2023 11:05 PM Gewa Exp $
    *
    */

   use Wojo\Language\Language;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<main>
   <div class="padding-big-vertical relative">
      <div class="wojo-grid">
         <div class="row justify-center">
            <div class="columns screen-50 tablet-60 mobile-100 phone-100">
               <div class="wojo card zindex2">
                  <div class="content">
                     <div class="center-align margin-bottom">
                        <h5><?php echo Language::$word->M_PASSWORD_RES; ?></h5>
                        <div class="wojo normal circular image">
                           <img src="<?php echo UPLOADURL; ?>avatars/default.svg" id="icon" alt="User Icon">
                        </div>
                     </div>
                     <div class="wojo form">
                        <form method="post" id="wojo_form" name="wojo_form">
                           <div class="wojo block fields">
                              <div class="field">
                                 <label><?php echo Language::$word->NEWPASS; ?>
                                    <i class="icon asterisk"></i>
                                 </label>
                                 <input placeholder="<?php echo Language::$word->NEWPASS; ?>" name="password" type="password">
                              </div>
                              <div class="field basic">
                                 <button class="wojo primary fluid button" data-url="login/action/" data-action="password" name="dosubmit" type="button"><?php echo Language::$word->SUBMIT; ?></button>
                              </div>
                           </div>
                           <input type="hidden" name="token" value="<?php echo $this->segments[1];?>">
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <figure class="absolute zindex1 width-full position-top position-left">
         <svg viewBox="0 0 3000 1000" xmlns="http://www.w3.org/2000/svg">
            <path fill="#eff5f6" d="M-.5-.5v611.1L2999.5-.5z"/>
         </svg>
      </figure>
   </div>
</main>