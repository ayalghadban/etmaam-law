<?php
   /**
    * login
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: login.tpl.php, v1.00 4/25/2023 1:49 PM Gewa Exp $
    *
    */

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
            <div class="columns screen-40 tablet-50 mobile-100 phone-100">
               <div class="wojo card zindex2">
                  <div class="content">
                     <div class="center-align margin-bottom">
                        <h5><?php echo Utility::sayHello(); ?></h5>
                        <div class="wojo normal circular image">
                           <img src="<?php echo UPLOADURL; ?>avatars/default.svg" id="icon" alt="User Icon">
                        </div>
                     </div>
                     <div class="wojo form" id="loginform">
                        <div class="margin-bottom"><?php echo Language::$word->M_SUB20; ?>
                           <a href="<?php echo Url::url($this->core->system_slugs->register[0]->{'slug' . Language::$lang}); ?>">
                              <span class="text-weight-500"><?php echo Language::$word->M_SUB21; ?>.</span>
                           </a>
                        </div>
                        <form id="admin_form" name="admin_form" method="post">
                           <div class="wojo block fields">
                              <div class="field">
                                 <label><?php echo Language::$word->USERNAME; ?></label>
                                 <div class="wojo icon input">
                                    <i class="icon envelope"></i>
                                    <input type="text" name="username" placeholder="<?php echo Language::$word->USERNAME; ?>">
                                 </div>
                              </div>
                              <div class="field">
                                 <div class="row">
                                    <div class="columns"><label class="label"><?php echo Language::$word->M_PASSWORD; ?></label></div>
                                    <div class="columns auto">
                                       <a id="passreset" class="text-size-small text-weight-500"><?php echo Language::$word->M_PASSWORD_RES; ?>?</a>
                                    </div>
                                 </div>
                                 <div class="wojo icon input">
                                    <i class="icon lock"></i>
                                    <input type="password" name="password" placeholder="<?php echo Language::$word->M_PASSWORD; ?>">
                                 </div>
                              </div>
                              <div class="field basic">
                                 <button id="doSubmit" type="button" class="wojo primary fluid button"
                                         name="submit"><?php echo Language::$word->LOGIN; ?></button>
                              </div>
                           </div>
                        </form>
                     </div>

                     <div id="passform" class="wojo form hide-all">
                        <div class="wojo block fields">
                           <div class="field">
                              <div class="wojo icon input">
                                 <i class="icon envelope"></i>
                                 <input type="text" name="pEmail" id="pEmail" class="input-container"
                                        placeholder="<?php echo Language::$word->M_EMAIL1; ?>">
                              </div>
                           </div>
                           <div class="field">
                              <button id="dopass" type="button" class="wojo fluid primary button"
                                      name="doopass"><?php echo Language::$word->SUBMIT; ?></button>
                           </div>
                        </div>
                        <div class="center-align">
                           <a id="backto" class="icon-text">
                              <i class="icon chevron left"></i><?php echo Language::$word->M_SUB14; ?></a>
                        </div>
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
<script type="text/javascript" src="<?php echo ADMINVIEW; ?>js/login.js"></script>
<script type="text/javascript">
   $(document).ready(function () {
      $.Login({
         aurl: "<?php echo ADMINURL;?>",
         surl: "<?php echo SITEURL;?>"
      });
   });
</script>
