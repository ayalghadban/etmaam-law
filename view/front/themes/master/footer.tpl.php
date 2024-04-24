<?php
   /**
    * footer
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: footer.tpl.php, v1.00 6/10/2023 1:25 PM Gewa Exp $
    *
    */

   use Wojo\Core\Session;
   use Wojo\Date\Date;
   use Wojo\Debug\Debug;
   use Wojo\Language\Language;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
  <a href="https://api.whatsapp.com/send?phone=966554799222" target="_blank" id="whatsapp"> 
        <i class="icon primary inverted whatsapp"></i> 
    </a>
<footer class="footer_10">
   <div class="wrapper">
      <div class="contents">
         <div class="wojo-grid">
            <div class="row" style="padding-bottom: 30px;">
               <div class="columns screen-25 mobile-100 phone-100 display-block order_2">
                   <div class="footer_logo_10 margin-small-bottom">
                       <a href="<?php echo SITEURL; ?>" class="logo"><?php echo ($this->core->logo)? '<img src="https://etmam.com.sa/view/front/themes/master/images/logo-laws.svg" alt=" ' . $this->core->company . '">' : $this->core->company; ?></a>
                       <p class="text-size-medium" 
                          style="word-wrap: break-word;
                                 
                                  font-family: 'Somar';
                                  font-size: 12px;
                                  font-style: normal;
                                  font-weight: 500;
                                  line-height: 22px;">
                          <?php echo Language::$word->FOT_ABOUT_I; ?>
                       </p>
                   </div>
                  <a href="https://www.facebook.com/etmaam2/" class="wojo small simple icon button"><i class="icon primary inverted facebook"></i></a>
                  <a href="https://twitter.com/etmaam_law" class="wojo small simple icon button"><i class="icon primary inverted twitter"></i></a>
                  <a href="https://www.instagram.com/etmaam2" class="wojo small simple icon button"><i class="icon primary inverted instagram"></i></a>
                  <a href="https://web.whatsapp.com/send?phone=+966554799222&text=" class="wojo small simple icon button"><i class="icon primary inverted whatsapp"></i></a>
                  <a href="https://www.linkedin.com/company/etmaam2/mycompany/" class="wojo small simple icon button"><i class="icon primary inverted linkedin"></i></a>
               </div>
               <div class="columns screen-15 mobile-100 phone-100 display-block order_3">
                  <p class="text-size-medium" style="font-size: 18px; font-family: Somar; font-weight: 600; word-wrap: break-word;">

                          <?php echo Language::$word->IMPORTANT_LINKS; ?>
                      </p>
                      <ul class="custom-list" style="list-style-type: none; padding: 0px; font-family: 'Somar', sans-serif; font-weight: 500;font-size: 14px;">
                        <li style="padding: 5px 0;" onmouseover="hover(this)" onmouseout="unhover(this)"><a href="https://etmam.com.sa/page/what-is-etmaam-kanoun/" style="text-decoration: none; color: inherit;"><?php echo Language::$word->WHO_WE_ARE; ?></a></li>
                        <li style="padding: 5px 0;" onmouseover="hover(this)" onmouseout="unhover(this)"><a href="https://etmam.com.sa/page/our-services/" style="text-decoration: none; color: inherit;"><?php echo Language::$word->OUR_SERVICES; ?>
</a></li>
                        <li style="padding: 5px 0;" onmouseover="hover(this)" onmouseout="unhover(this)"><a href="https://etmam.com.sa/page/contact-us/" style="text-decoration: none; color: inherit;"><?php echo Language::$word->CONTACT_US; ?></a></li>
                        <li style="padding: 5px 0;" onmouseover="hover(this)" onmouseout="unhover(this)"><a href="https://etmam.com.sa/page/contact-us/" style="text-decoration: none; color: inherit;"><?php echo Language::$word->FREE_CONSULTATION; ?></a></li>
                    </ul>

               </div>
               <div class="columns screen-30 mobile-100 phone-100 margin-meduim order_4">
                  <p class="text-size-medium" style="font-size: 18px; font-family:Somar; font-weight: 600;">
                       <?php echo Language::$word->CONTACT_WITH_US; ?>
                      </p>
                      <ul style="list-style-type:none; padding:0; font-weight:500;">
                               <li style ="display: block;"onmouseover="hover1(this)" onmouseout="unhover1(this)"><?php echo Language::$word->PHONE; ?> 
                                   <svg width="16" height="20" viewBox="0 0 16 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6.1176 8.54141L7.79327 7.04766C8.25146 6.63843 8.57349 6.10531 8.71805 5.51674C8.86261 4.92817 8.8231 4.31099 8.60459 3.74442L7.89001 1.88786C7.62304 1.19475 7.09452 0.626752 6.41213 0.299574C5.72973 -0.0276034 4.94481 -0.0893409 4.21726 0.12694C1.53993 0.923908 -0.518001 3.34517 0.115447 6.22033C0.532025 8.1118 1.3293 10.486 2.83959 13.012C4.353 15.5441 6.08172 17.4143 7.56392 18.7289C9.80127 20.71 12.9997 20.2151 15.0576 18.3479C15.6093 17.8475 15.944 17.1612 15.9936 16.4289C16.0432 15.6967 15.8039 14.9736 15.3244 14.4071L14.0139 12.8587C13.6186 12.3905 13.0884 12.048 12.4914 11.8754C11.8944 11.7028 11.258 11.708 10.6641 11.8902L8.49849 12.5536C7.93923 11.992 7.44914 11.369 7.03813 10.697C6.64142 10.0175 6.33225 9.29298 6.1176 8.53989" fill="#332D2B"/>
                                   </svg> 
                               </li>
                            <li style="display:block; padding-top:12px;"onmouseover="hover1(this)" onmouseout="unhover1(this)"><?php echo Language::$word->EMAIL_ETMAAM ; ?>  
                                <svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.95 0H3.05C2.3737 0 1.7251 0.276562 1.24688 0.768845C0.76866 1.26113 0.5 1.92881 0.5 2.625V11.375C0.5 12.0712 0.76866 12.7389 1.24688 13.2312C1.7251 13.7234 2.3737 14 3.05 14H14.95C15.6263 14 16.2749 13.7234 16.7531 13.2312C17.2313 12.7389 17.5 12.0712 17.5 11.375V2.625C17.5 1.92881 17.2313 1.26113 16.7531 0.768845C16.2749 0.276562 15.6263 0 14.95 0ZM14.95 1.75L9.425 5.66125C9.29578 5.73805 9.14921 5.77848 9 5.77848C8.85079 5.77848 8.70422 5.73805 8.575 5.66125L3.05 1.75H14.95Z" fill="#A29632"/>
                                </svg> 
                            </li>
                          <li style="display:block; padding-top:12px;"onmouseover="hover1(this)" onmouseout="unhover1(this)"><?php echo Language::$word->ADD_1; ?>
                            <svg width="16" height="20" viewBox="0 0 16 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 0C6.0116 0.00245748 4.10531 0.831051 2.6993 2.30402C1.29328 3.77699 0.502354 5.77405 0.500008 7.85714C0.497626 9.55945 1.0284 11.2156 2.01092 12.5714C2.01092 12.5714 2.21546 12.8536 2.24887 12.8943L8 20L13.7539 12.8907C13.7839 12.8529 13.9891 12.5714 13.9891 12.5714L13.9898 12.5693C14.9718 11.214 15.5023 9.55866 15.5 7.85714C15.4976 5.77405 14.7067 3.77699 13.3007 2.30402C11.8947 0.831051 9.9884 0.00245748 8 0ZM8 10.7143C7.4606 10.7143 6.93331 10.5467 6.48481 10.2328C6.03631 9.91882 5.68675 9.4726 5.48033 8.95052C5.27391 8.42845 5.2199 7.85397 5.32513 7.29974C5.43037 6.74551 5.69011 6.23642 6.07153 5.83684C6.45294 5.43726 6.9389 5.16514 7.46794 5.0549C7.99697 4.94466 8.54534 5.00124 9.04368 5.21749C9.54202 5.43374 9.96797 5.79995 10.2676 6.2698C10.5673 6.73965 10.7273 7.29205 10.7273 7.85714C10.7264 8.61461 10.4387 9.34079 9.92747 9.8764C9.41621 10.412 8.72304 10.7133 8 10.7143Z" fill="#332D2B"/>
                            </svg> 
                          </li>
                          <li style="display:block; padding-top:12px;"onmouseover="hover1(this)" onmouseout="unhover1(this)"><?php echo Language::$word->ADD_2; ?>
                            <svg width="16" height="20" viewBox="0 0 16 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 0C6.0116 0.00245748 4.10531 0.831051 2.6993 2.30402C1.29328 3.77699 0.502354 5.77405 0.500008 7.85714C0.497626 9.55945 1.0284 11.2156 2.01092 12.5714C2.01092 12.5714 2.21546 12.8536 2.24887 12.8943L8 20L13.7539 12.8907C13.7839 12.8529 13.9891 12.5714 13.9891 12.5714L13.9898 12.5693C14.9718 11.214 15.5023 9.55866 15.5 7.85714C15.4976 5.77405 14.7067 3.77699 13.3007 2.30402C11.8947 0.831051 9.9884 0.00245748 8 0ZM8 10.7143C7.4606 10.7143 6.93331 10.5467 6.48481 10.2328C6.03631 9.91882 5.68675 9.4726 5.48033 8.95052C5.27391 8.42845 5.2199 7.85397 5.32513 7.29974C5.43037 6.74551 5.69011 6.23642 6.07153 5.83684C6.45294 5.43726 6.9389 5.16514 7.46794 5.0549C7.99697 4.94466 8.54534 5.00124 9.04368 5.21749C9.54202 5.43374 9.96797 5.79995 10.2676 6.2698C10.5673 6.73965 10.7273 7.29205 10.7273 7.85714C10.7264 8.61461 10.4387 9.34079 9.92747 9.8764C9.41621 10.412 8.72304 10.7133 8 10.7143Z" fill="#332D2B"/>
                            </svg> 
                          </li>
                          <li style="display:block; padding-top:12px;"onmouseover="hover1(this)" onmouseout="unhover1(this)"><?php echo Language::$word->aDD_3; ?>
                            <svg width="16" height="20" viewBox="0 0 16 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 0C6.0116 0.00245748 4.10531 0.831051 2.6993 2.30402C1.29328 3.77699 0.502354 5.77405 0.500008 7.85714C0.497626 9.55945 1.0284 11.2156 2.01092 12.5714C2.01092 12.5714 2.21546 12.8536 2.24887 12.8943L8 20L13.7539 12.8907C13.7839 12.8529 13.9891 12.5714 13.9891 12.5714L13.9898 12.5693C14.9718 11.214 15.5023 9.55866 15.5 7.85714C15.4976 5.77405 14.7067 3.77699 13.3007 2.30402C11.8947 0.831051 9.9884 0.00245748 8 0ZM8 10.7143C7.4606 10.7143 6.93331 10.5467 6.48481 10.2328C6.03631 9.91882 5.68675 9.4726 5.48033 8.95052C5.27391 8.42845 5.2199 7.85397 5.32513 7.29974C5.43037 6.74551 5.69011 6.23642 6.07153 5.83684C6.45294 5.43726 6.9389 5.16514 7.46794 5.0549C7.99697 4.94466 8.54534 5.00124 9.04368 5.21749C9.54202 5.43374 9.96797 5.79995 10.2676 6.2698C10.5673 6.73965 10.7273 7.29205 10.7273 7.85714C10.7264 8.61461 10.4387 9.34079 9.92747 9.8764C9.41621 10.412 8.72304 10.7133 8 10.7143Z" fill="#332D2B"/>
                            </svg> 
                          </li>
                      </ul>
               </div>
               <!--<?php  include FPLUGPATH . 'blog/blogcombo-footer/index.tpl.php';?>-->
               <div class="columns screen-30 mobile-100 phone-100 order_1">
                  <h3 class="" style=" font-family: Somar; font-size:32px; font-weight:600;"><?php echo Language::$word->PARTISIPATE; ?></h3>
                  <form id="wojo_form_newsletter" name="wojo_form_newsletter" method="post" class="wojo small form" style="margin-top: 20px;display: flex;
  flex-direction: row-reverse;
  align-items: self-start;">
                     <div class="wojo small transparent input">
                        <input type="email" name="email" style="background-color: #FFF;dirction: rtl;color: #000;text-align: end;border-radius: 0;"
                        placeholder="<?php echo Language::$word->EMAIL; ?>">
                     </div>
                     <button style="flex: 1;padding: .625rem 0;border-radius: 0;" type="button" data-url="plugin/newsletter/action/" data-hide="true" data-action="process" name="dosubmit" class="wojo primary small fluid right button"><?php echo Language::$word->PARTI; ?></button>
                  </form>
               </div>
            </div>

            <div class="copyright "style="display: flex; justify-content:space-between;">
               <div class="row" style="margin-bottom: 10px;">
                <a class="" href="https://maroof.sa/210160" target="_blank">
                    <img class="lazy entered loading" width="30px" height="30px" src="https://etmaam.com.sa/assets/front/img/ma3rof.png" alt="" data-ll-status="loading">
                </a>
                 <a class="vatLink" href="https://etmaam.com.sa/assets/front/files/vat.pdf" target="_blank">
                    <img class="lazy entered loading" src="https://etmaam.com.sa/assets/front/img/VAT.png" width="30px"  height="30px" alt="" data-ll-status="loading">
                </a>
                <a class="" href="https://etmaam.com.sa/assets/front/files/Certificate.pdf" target="_blank">
                    <img class="lazy entered loading" width="30px" height="30px"  src="https://etmaam.com.sa/assets/front/img/المركز-السعودي-للأعمال.png" alt="" data-ll-status="loading">
                </a>
                </div>
                 <div class="row">
                  <div class="columns phone-100" style="font-size: 14px; font-family: Somar; font-weight: 500; word-wrap: break-word;text-align: center;"><?php echo Language::$word->ALL_FOOTER; ?> 

                  </div>
                </div>
               </div>
                 <p style="color:#fff;
text-align: center;
font-family: 'Somar';
font-size: 14px;
font-style: normal;
font-weight: 700;
line-height: normal;margin-top: 10px; ">
<?php echo Language::$word->ALL_FOOTER_1; ?>                </p>
            </div>
         </div>
      </div>
   </div>
   
</footer>
<a href="#" id="back-to-top" class="wojo small icon primary inverted button" title="Back to top"><i class="icon chevron up"></i></a>
<script src="<?php echo THEMEURL . 'js/master.js'; ?>"></script>
<?php if ($this->core->analytics): ?>
   <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $this->core->analytics; ?>"></script>
<?php endif; ?>
<script type="text/javascript">
function hover(element) {
    element.style.color = '#A29632';
}

function unhover(element) {
    element.style.color = '#332D2B';
}

function hover1(element) {
    element.style.color = '#486767';
}

function unhover1(element) {
    element.style.color = '#332D2B';
}
   // <![CDATA[
   <?php if($this->core->ploader):?>
   $(window).on('load', function () {
      setTimeout(function () {
         $("body").addClass("loaded");
      }, 200);
   });
   <?php endif;?>
   $(document).ready(function () {
      $.Master({
         url: "<?php echo FRONTVIEW;?>",
         surl: "<?php echo SITEURL;?>",
         weekstart: <?php echo($this->core->weekstart);?>,
         ampm: <?php echo ($this->core->time_format) == 'HH:mm'? 0 : 1;?>,
         loginCheck: <?php echo ($this->auth->logged_in and $this->core->one_login)? 1 : 0;?>,
         lang: {
            monthsFull: [ <?php echo Date::monthList(false);?> ],
            monthsShort: [ <?php echo Date::monthList(false, false);?> ],
            weeksFull: [ <?php echo Date::weekList(false); ?> ],
            weeksShort: [ <?php echo Date::weekList(false, false);?> ],
            weeksMed: [ <?php echo Date::weekList(false, false, true);?> ],
            button_text: "<?php echo Language::$word->BROWSE;?>",
            empty_text: "<?php echo Language::$word->NOFILE;?>",
            sel_pic: "<?php echo Language::$word->SELIMG;?>",
            canBtn: "<?php echo Language::$word->CANCEL;?>",
         }
      });
      <?php if($this->core->eucookie):?>
      $('body').wCookies({
         title: '&#x1F36A; <?php echo Language::$word->EU_W_COOKIES;?>?',
         message: '<?php echo Language::$word->EU_NOTICE;?>',
         delay: 600,
         expires: 360,
         cookieName: 'WCMS_GDPR',
         link: '<?php echo Url::url('/page', 'privacy-policy');?>',
         cookieTypes: [
            {
               type: '<?php echo Language::$word->EU_PREFS;?>',
               value: 'preferences',
               description: '<?php echo Language::$word->EU_PREFS_I;?>'
            },
            {
               type: '<?php echo Language::$word->EU_ANALYTICS;?>',
               value: 'analytics',
               description: '<?php echo Language::$word->EU_ANALYTICS_I;?>'
            },
            {
               type: '<?php echo Language::$word->EU_MARKETING;?>',
               value: 'marketing',
               description: '<?php echo Language::$word->EU_MARKETING_I;?>'
            }
         ],
         uncheckBoxes: true,
         acceptBtnLabel: '<?php echo Language::$word->EU_ACCEPT;?>',
         advancedBtnLabel: '<?php echo Language::$word->EU_CUSTOMISE;?>',
         moreInfoLabel: '<?php echo Language::$word->PRIVACY;?>',
         cookieTypesTitle: '<?php echo Language::$word->EU_SELCOOKIES;?>',
         fixedCookieTypeLabel: '<?php echo Language::$word->EU_ESSENTIALS;?>',
         fixedCookieTypeDesc: '<?php echo Language::$word->EU_ESSENTIALS_I;?>'
      });
      <?php endif;?>
   });
   <?php if($this->core->analytics):?>
   window.dataLayer = window.dataLayer || [];

   function gtag() {
      dataLayer.push(arguments);
   }

   gtag('js', new Date());
   gtag('config', '<?php echo $this->core->analytics;?>', {
      client_storage: '<?php echo ($this->core->eucookie and Session::cookieinArray('analytics', 'WCDP_cookieControlPrefs', true))? 'granted' : 'none';?>',
      ad_storage: '<?php echo ($this->core->eucookie and Session::cookieinArray('analytics', 'WCDP_cookieControlPrefs', true))? 'granted' : 'denied';?>',
      analytics_storage: '<?php echo ($this->core->eucookie and Session::cookieinArray('analytics', 'WCDP_cookieControlPrefs', true))? 'granted' : 'denied';?>',
   });
   <?php endif;?>
   // ]]>
</script>
<?php Debug::displayInfo();?>
</body>
</html>