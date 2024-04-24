<?php
   /**
    * register
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: register.tpl.php, v1.00 6/22/2023 10:54 AM Gewa Exp $
    *
    */

   use Wojo\Core\Session;
   use Wojo\Language\Language;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<main>
   <div class="padding-big-vertical relative">
      <div class="wojo-grid">
         <div class="row justify-center">
            <div class="columns screen-50 tablet-70 mobile-100 phone-100">
               <div class="wojo card zindex2">
                  <div class="content">
                     <form method="post" id="reg_form" name="reg_form">
                        <div class="center-align margin-bottom">
                           <h5><?php echo Language::$word->M_SUB23; ?></h5>
                           <?php echo Language::$word->M_SUB31; ?>
                           <a href="<?php echo Url::url($this->core->system_slugs->login[0]->{'slug' . Language::$lang}); ?>">
                              <span class="text-weight-500"><?php echo Language::$word->LOGIN_1; ?>.</span>
                           </a>
                        </div>
                        <div class="wojo form">
                           <div class="wojo block fields">
                              <div class="field">
                                 <label><?php echo Language::$word->M_EMAIL; ?>
                                    <i class="icon asterisk"></i>
                                 </label>
                                 <input name="email" type="email" placeholder="<?php echo Language::$word->M_EMAIL; ?>">
                              </div>
                              <div class="field">
                                 <label><?php echo Language::$word->M_PASSWORD; ?>
                                    <i class="icon asterisk"></i>
                                 </label>
                                 <input type="password" name="password" placeholder="********">
                              </div>
                           </div>
                           <div class="wojo fields">
                              <div class="field">
                                 <label><?php echo Language::$word->M_FNAME; ?>
                                    <i class="icon asterisk"></i>
                                 </label>
                                 <input name="fname" type="text" placeholder="<?php echo Language::$word->M_FNAME; ?>">
                              </div>
                              <div class="field">
                                 <label><?php echo Language::$word->M_LNAME; ?>
                                    <i class="icon asterisk"></i>
                                 </label>
                                 <input name="lname" type="text" placeholder="<?php echo Language::$word->M_LNAME; ?>">
                              </div>
                           </div>
                           <?php echo $this->custom_fields; ?>
                           <?php if ($this->core->enable_tax): ?>
                              <div class="wojo block fields">
                                 <div class="field">
                                    <label><?php echo Language::$word->M_ADDRESS; ?>
                                       <i class="icon asterisk"></i>
                                    </label>
                                    <input type="text" name="address" placeholder="<?php echo Language::$word->M_ADDRESS; ?>">
                                 </div>
                              </div>
                              <div class="wojo fields">
                                 <div class="field">
                                    <label><?php echo Language::$word->M_CITY; ?>
                                       <i class="icon asterisk"></i>
                                    </label>
                                    <input type="text" name="city" placeholder="<?php echo Language::$word->M_CITY; ?>">
                                 </div>
                                 <div class="field">
                                    <label><?php echo Language::$word->M_STATE; ?>
                                       <i class="icon asterisk"></i>
                                    </label>
                                    <input type="text" name="state" placeholder="<?php echo Language::$word->M_STATE; ?>">
                                 </div>
                              </div>
                              <div class="wojo fields">
                                 <div class="field three wide">
                                    <label>
                                       <?php echo Language::$word->M_ZIP; ?>
                                       <i class="icon asterisk"></i>
                                    </label>
                                    <input type="text" name="zip">
                                 </div>
                                 <div class="field">
                                    <label>
                                       <?php echo Language::$word->M_COUNTRY; ?>
                                       <i class="icon asterisk"></i>
                                    </label>
                                    <select name="country">
                                       <?php echo Utility::loopOptions($this->countries, 'abbr', 'name'); ?>
                                    </select>
                                 </div>
                              </div>
                           <?php endif; ?>
                           <div class="wojo block fields">
                              <div class="field">
                                 <label><?php echo Language::$word->CAPTCHA; ?>
                                    <i class="icon asterisk"></i>
                                 </label>
                                 <div class="wojo labeled input">
                                    <input placeholder="<?php echo Language::$word->CAPTCHA; ?>" name="captcha" type="text">
                                    <span class="wojo simple label"><?php echo Session::captcha(); ?></span>
                                 </div>
                              </div>
                              <div class="field">
                                 <div class="wojo checkbox">
                                    <input name="agree" type="checkbox" value="1" id="agree">
                                    <label for="agree">
                                       <a href="<?php echo Url::url($this->core->system_slugs->policy[0]->{'slug' . Language::$lang}); ?>">
                                          <small><?php echo Language::$word->AGREE; ?></small>
                                       </a>
                                    </label>
                                 </div>
                              </div>
                              <div class="field basic">
                                 <button class="wojo primary fluid button" data-url="login/action/" data-action="register" name="dosubmit" type="button"><?php echo Language::$word->M_SUB24; ?></button>
                              </div>
                           </div>
                        </div>
                     </form>
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