<?php
   /**
    * header
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: header.tpl.php, v1.00 6/10/2023 1:25 PM Gewa Exp $
    *
    */

   use Wojo\Cache\Cache;
   use Wojo\Core\Content;
   use Wojo\Core\Core;
   use Wojo\File\File;
   use Wojo\Language\Language;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;
   use Wojo\Core\Session;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<!DOCTYPE html>
<html lang="ar">
<head>

   <meta charset="utf-8">
   <title><?php echo $this->title; ?></title>
   <meta name="keywords" content="<?php echo $this->keywords; ?>">
   <meta name="description" content="<?php echo $this->description; ?>">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="apple-mobile-web-app-capable" content="yes">
   <meta name="msapplication-TileColor" content="#da532c">
   <meta name="theme-color" content="#ffffff">
   <meta name="dcterms.rights" content="<?php echo $this->core->company; ?> &copy; All Rights Reserved">
   <meta name="robots" content="index">
   <meta name="robots" content="follow">
   <meta name="revisit-after" content="1 day">
   <meta name="generator" content="Powered by CMS pro! v<?php echo $this->core->wojov; ?>">
   <?php echo $this->meta ?? null; ?>
   <link rel="apple-touch-icon" sizes="180x180" href="<?php echo SITEURL; ?>assets/favicons/apple-touch-icon.png">
   <link rel="icon" type="image/png" sizes="32x32" href="<?php echo SITEURL; ?>assets/favicons/favicon-32x32.png">
   <link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITEURL; ?>assets/favicons/favicon-16x16.png">
   <link rel="manifest" href="<?php echo SITEURL; ?>assets/favicons/site.webmanifest">
   <link rel="mask-icon" href="<?php echo SITEURL; ?>assets/favicons/safari-pinned-tab.svg" color="#5bbad5">

   <!--?php if (in_array(Core::$language, array('ar', 'ae', 'sa'))): ?>-->

   <!--   <link href="?php echo THEMEURL . 'cache/' . Cache::cssCache(array(-->
   <!--       'color_rtl.css', 'base_rtl.css', 'transition_rtl.css', 'statistic_rtl.css', 'button_rtl.css', 'icon_rtl.css', 'flag_rtl.css', 'image_rtl.css', 'label_rtl.css', 'form_rtl.css', 'input_rtl.css', 'list_rtl.css', 'card_rtl.css', 'table_rtl.css', 'dropdown_rtl.css', 'statistic_rtl.css', 'message_rtl.css', 'modal_rtl.css', 'comment_rtl.css', 'progress_rtl.css', 'tooltip_rtl.css',-->
   <!--       'utility_rtl.css', 'style_rtl.css'-->
   <!--     ), THEMEBASE); ?>" rel="stylesheet" type="text/css">-->
   <!--?php else: ?>-->
   <!--   <link href="?php echo THEMEURL . 'cache/' . Cache::cssCache(array(-->
   <!--       'color.css', 'base.css', 'transition.css', 'statistic.css', 'button.css', 'icon.css', 'flags.css', 'image.css', 'label.css', 'form.css', 'input.css', 'list.css', 'card.css', 'table.css', 'dropdown.css', 'message.css', 'modal.css', 'comment.css', 'progress.css', 'tooltip.css', 'utility.css', 'style.css'-->
   <!--     ), THEMEBASE); ?>" rel="stylesheet" type="text/css">-->
   <!--?php endif; ?>-->




<?php
$rtlLanguages = array('ar', 'ae', 'sa'); // قائمة اللغات التي تستخدم تنسيق RTL
$cssFilesRTL = array(
        'color_rtl.css', 'base_rtl.css', 'transition_rtl.css', 'statistic_rtl.css',
    'button_rtl.css', 'icon_rtl.css', 'flags_rtl.css', 'image_rtl.css',
    'label_rtl.css', 'form_rtl.css', 'input_rtl.css', 'list_rtl.css',
    'card_rtl.css', 'table_rtl.css', 'dropdown_rtl.css', 'statistic_rtl.css',
    'message_rtl.css', 'modal_rtl.css', 'comment_rtl.css', 'progress_rtl.css',
     'comment_rtl.css', 'tooltip_rtl.css',
    'utility_rtl.css', 'style_rtl.css'
    
);
$cssFilesLTR = array(
    
     'color.css', 'base.css', 'transition.css', 'statistic.css', 'button.css',
    'icon.css', 'flags.css', 'image.css', 'label.css', 'form.css', 'input.css',
    'list.css', 'card.css', 'table.css', 'dropdown.css', 'message.css',
    'modal.css', 'comment.css', 'progress.css', 'tooltip.css', 'utility.css',
    'style.css'
);


if (in_array(Core::$language, array('ar', 'ae', 'sa'))){
$baseURL = THEMEURL . 'css/'; 
}else{
    $baseURL = THEMEURL . 'css/'; 

}

// تحديد الملفات بناءً على اللغة
$cssFilesToLoad = in_array(Core::$language, $rtlLanguages) ? $cssFilesRTL : $cssFilesLTR;

// إنشاء عناصر link لتحميل ملفات CSS
foreach ($cssFilesToLoad as $cssFile) {
    echo '<link href="' . $baseURL . $cssFile . '" rel="stylesheet" type="text/css">' . PHP_EOL;
}

?>

        <link rel="stylesheet" href="https://etmam.com.sa/view/front/themes/master/css/swiper-bundle.min.css" />
        <link rel="stylesheet" href="https://etmam.com.sa/view/front/themes/master/css/animate.css" />
        <script src="https://etmam.com.sa/view/front/themes/master/js/swiper-bundle.min.js"></script>
        <script src="https://etmam.com.sa/view/front/themes/master/js/main.min.js"></script>
        <script src="https://etmam.com.sa/view/front/themes/master/js/wow.min.js"></script>
   <link href="<?php echo THEMEURL . 'plugins/cache/' . Cache::pluginCssCache(THEMEBASE . 'plugins'); ?>" rel="stylesheet" type="text/css">
   <link href="<?php echo THEMEURL . 'modules/cache/' . Cache::moduleCssCache(THEMEBASE . 'modules'); ?>" rel="stylesheet" type="text/css">
   <script src="<?php echo SITEURL . 'assets/jquery.js'; ?>"></script>
   <script src="<?php echo SITEURL . 'assets/global.js'; ?>"></script>
   <script src="<?php echo THEMEURL . 'plugins/cache/' . Cache::pluginJsCache(THEMEBASE . 'plugins'); ?>"></script>
   <script src="<?php echo THEMEURL . 'modules/cache/' . Cache::moduleJsCache(THEMEBASE . 'modules'); ?>"></script>
   <?php if (Utility::in_array_any(['dashboard', 'checkout'], $this->segments)): ?>
      <script defer src="https://checkout.razorpay.com/v1/checkout.js"></script>
      <script defer src="https://js.stripe.com/v3/"></script>
      
            <script src="https://etmam.com.sa/view/front/themes/master/js/swiper-bundle.min.js"></script>
                  <script src="https://etmam.com.sa/view/front/themes/master/js/swiper.min.js"></script>
   <?php endif; ?>
</head>
<body class="page_<?php if (count($this->segments)): echo $this->segments[0]; endif; ?>">
    <div id="preloader">
        <div class="loader revolve">
            <div id="wppu-object-wrapper" class="lifebeauty" style="width:400px;height:400px;padding:13%;">
				<div class="wppu-object-logo">
				    <?php if (Core::$language == 'sa'): ?>
				  	<img src="https://etmam.com.sa/view/front/themes/master/images/logo.jpeg" class="officallogo" style=";">
				  	    <?php else: ?>
				  	<img src="https://etmam.com.sa/view/front/themes/master/images/etmaam.png" class="officallogo" style=";">
<?php endif; ?>

				</div>
				<div class="wpppu-object-wrap" style="color:#A29632;"></div>
			</div>
        </div>
    </div>

<header id="header">
    
   <div class="wojo-grid">
      <div class="row align-middle small-horizontal-gutters phone-vertical-gutters">
         <div class="columns auto phone-order-1 mobile-order-1">
            <a href="<?php echo SITEURL; ?>" class="logo"><?php echo ($this->core->logo)? '<img src="' . UPLOADURL . $this->core->logo . '" alt=" ' . $this->core->company . '">' : $this->core->company; ?></a>
         <a href="<?php echo SITEURL; ?>" class="logo logo_fixed"><?php echo ($this->core->logo)? '<img src="http://etmam.com.sa/uploads/logo.png.png" alt=" ' . $this->core->company . '">' : $this->core->company; ?></a>
         </div>
         <div class="columns screen-hide tablet-hide phone-order-2 mobile-order-2 left-align">
            <button type="button" class="wojo icon small primary button mobile-button">
               <i class="icon list"></i>
            </button>
         </div>

         <div class="columns mobile-100 phone-100 phone-order-4 mobile-order-4">
             <div class="overlay"></div>
            <nav class="wojo menu">
                <?php Content::renderMenu(Content::menuTree(true)); ?>
            </nav>
         </div>
         <div class="columns auto phone-order-3 mobile-order-3 mobile-100 phone-100 center-align">
            <div class="phone toolbar-phone">
               <div class="wojo horizontal divided list" id="iconList">

                 <!-- <?php if ($this->core->showsearch): ?>
                     <div class="item">
                       <a href="<?php echo Url::url($this->core->system_slugs->search[0]->{'slug' . Language::$lang}); ?>">
                           <i class="icon search"></i>
                        </a>
                     </div>
                  <?php endif; ?>-->

                  <?php if ($this->core->showlang): ?>
                     <!--Lang Switcher-->
                     <?php if (count($this->core->langlist) > 1): ?>
                        <div class="item">
                          <a data-wdropdown="#dropdown-langChange" class="capitalize-text">
                              <?php echo Core::$language; ?>
                             <i class="icon small chevron down"></i>
                          </a>
                         <div class="wojo small dropdown pointing top-right" id="dropdown-langChange">
                              <?php foreach ($this->core->langlist as $lang): ?>
                                <a data-value="<?php echo $lang->abbr; ?>" class="item<?php echo (Core::$language == $lang->abbr)? ' active' : null; ?>">
                                    <?php echo $lang->name; ?></a>
                              <?php endforeach; ?>
                           </div>
                        </div>
                     <?php endif; ?>
                  <?php endif; ?>

                  <!--digishop cart-->
                  <?php if (File::is_File(FMODPATH . 'digishop/index.tpl.php')): ?>
                     <div class="item">
                        <a href="<?php echo Url::url($this->core->modname['digishop'], $this->core->modname['digishop-checkout']); ?>">
                           <i class="icon basket"></i>
                        </a>
                     </div>
                  <?php endif; ?>

                  <!--shop cart-->
                  <?php if (File::is_File(FMODPATH . 'shop/index.tpl.php')): ?>
                     <div class="item">
                        <a href="<?php echo Url::url($this->core->modname['shop'], $this->core->modname['shop-checkout']); ?>">
                           <i class="icon bag"></i>
                        </a>
                     </div>
                  <?php endif; ?>

                  <!--Show Login-->
                  <?php if ($this->core->showlogin): ?>
                     <div class="item">
                        <?php if ($this->auth->is_User()): ?>
                           <a href="<?php echo Url::url($this->core->system_slugs->account[0]->{'slug' . Language::$lang}); ?>" class="phone-hide">
                              <?php echo Language::$word->HI; ?>
                              <?php echo $this->auth->name; ?>!
                           </a>
                           <a href="<?php echo Url::url($this->core->system_slugs->account[0]->{'slug' . Language::$lang}); ?>" class="screen-hide tablet-hide mobile-hide phone-show">
                              <i class="icon person"></i>
                           </a>
                        <?php else: ?>
                           <!--<a href="?php echo Url::url($this->core->system_slugs->login[0]->{'slug' . Language::$lang}); ?>" class="wojo secondary inverted small icon button">-->
                           <!--   <i class="icon person"></i>-->
                           <!--</a>-->
                           
                           <?php if (Core::$language == 'sa'): ?>
   <span style="cursor: pointer;" class="free_btn">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6.17978 13.7235L0.247205 7.63949C0.157317 7.54731 0.0934969 7.44745 0.0557441 7.3399C0.0179914 7.23236 -0.000585205 7.11713 1.40451e-05 6.99422C1.40451e-05 6.87131 0.0188906 6.75609 0.0566434 6.64854C0.0943961 6.541 0.157917 6.44113 0.247205 6.34895L6.17978 0.264987C6.34458 0.0959878 6.55072 0.00780133 6.79821 0.000426821C7.0457 -0.00694768 7.25903 0.0812388 7.43821 0.264987C7.61798 0.433986 7.71176 0.645388 7.71955 0.899194C7.72734 1.153 7.64105 1.37178 7.46068 1.55552L3.05619 6.07241H13.1011C13.3558 6.07241 13.5694 6.1609 13.742 6.33789C13.9146 6.51488 14.0006 6.73366 14 6.99422C14 7.2554 13.914 7.47449 13.742 7.65147C13.57 7.82846 13.3564 7.91665 13.1011 7.91603H3.05619L7.46068 12.4329C7.62547 12.6019 7.71176 12.817 7.71955 13.0782C7.72734 13.3394 7.64105 13.5545 7.46068 13.7235C7.29588 13.9078 7.08615 14 6.83147 14C6.57679 14 6.35956 13.9078 6.17978 13.7235Z" fill="#a29632"/>
        </svg> 
         إستشارة مجانية
    </span>
                    <div data-mode="readonly" data-wplugin-id="5" data-wplugin-plugin_id="1" data-wplugin-alias="contact">
   <form id="free_btn_form" action="" methods="post">
        <h2 style="font-weight: 700;">أرسل إستشارتك بشكل مجاني</h2>
         <div class="wojo block fields">
                                <div class="field">
                                    <input type="text" placeholder="الاسم الكامل" value="" name="name">
                                </div>
                                <div class="field">
                                    <input type="text" placeholder="رقم الجوال" name="phone">
                                </div>
                                <div class="field">
                                    <input type="text" placeholder="عنوان البريد الإلكتروني" value="" name="email">
                                </div>
                                <div class="field">
                                    <select id="practiceAreas" name="practiceAreas">
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
                                <div class="field">
                                    <textarea class="small" placeholder="رسالة" name="notes"></textarea>
                                </div>
                                <div class="field captcha-field">
                                    <input name="captcha" placeholder="كابتشا" type="text">
                                    <div class="wojo simple passive button captcha"><?php echo Session::captcha(); ?></div>
                                </div>
                                <div class="field">
                                    <div class="wojo checkbox">
                                        <input name="agree" type="checkbox" value="1" id="agree">
                                        <label for="agree">
                                            <a href="http://etmam.com.sa/privacy-policy/" class="secondary"
                                                wf-type="link" wf-label="Link" nav-id="nav_18">
                                                <small>نعم، أوافق على سياسة الخصوصية</small>
                                            </a>
                                        </label>
                                    </div>
                                </div>
                                <div class="field">
                                    <button type="button" data-hide="true" data-url="ajax/" data-action="contact"
                                        name="dosubmit" class="wojo primary fluid button">
                                        <svg width="31" height="13" viewBox="0 0 31 13" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6.8 11.3001L2 6.50008L6.8 1.70008" stroke="white" stroke-width="2"
                                                stroke-linecap="round" />
                                            <path d="M2 6.49991L30 6.49991" stroke="white" stroke-width="2"
                                                stroke-linecap="round" />
                                        </svg>
                                        إرسال الاستشارة
                                    </button>
                                </div>
    </form>
    </div>

<?php else: ?>
     <span style="cursor: pointer;" class="free_btn">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6.17978 13.7235L0.247205 7.63949C0.157317 7.54731 0.0934969 7.44745 0.0557441 7.3399C0.0179914 7.23236 -0.000585205 7.11713 1.40451e-05 6.99422C1.40451e-05 6.87131 0.0188906 6.75609 0.0566434 6.64854C0.0943961 6.541 0.157917 6.44113 0.247205 6.34895L6.17978 0.264987C6.34458 0.0959878 6.55072 0.00780133 6.79821 0.000426821C7.0457 -0.00694768 7.25903 0.0812388 7.43821 0.264987C7.61798 0.433986 7.71176 0.645388 7.71955 0.899194C7.72734 1.153 7.64105 1.37178 7.46068 1.55552L3.05619 6.07241H13.1011C13.3558 6.07241 13.5694 6.1609 13.742 6.33789C13.9146 6.51488 14.0006 6.73366 14 6.99422C14 7.2554 13.914 7.47449 13.742 7.65147C13.57 7.82846 13.3564 7.91665 13.1011 7.91603H3.05619L7.46068 12.4329C7.62547 12.6019 7.71176 12.817 7.71955 13.0782C7.72734 13.3394 7.64105 13.5545 7.46068 13.7235C7.29588 13.9078 7.08615 14 6.83147 14C6.57679 14 6.35956 13.9078 6.17978 13.7235Z" fill="#a29632"/>
        </svg> 
         Free consultation 
    </span>
<div data-mode="readonly" data-wplugin-id="5" data-wplugin-plugin_id="1" data-wplugin-alias="contact">
    <form id="free_btn_form" action="" methods="post">
        <h2 style="font-weight: 700;">Send your free consultation </h2>
         <div class="wojo block fields">
                                <div class="field">
                                    <input type="text" placeholder="Full Name " value="" name="name">
                                </div>
                                <div class="field">
                                    <input type="text" placeholder="Phone Number" name="phone">
                                </div>
                                <div class="field">
                                    <input type="text" placeholder="Email Adress" value="" name="email">
                                </div>
                                <div class="field">
                                    <select id="practiceAreas" name="practiceAreas">
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
                                <div class="field">
                                    <textarea class="small" placeholder="Notes" name="notes"></textarea>
                                </div>
                                <div class="field captcha-field">
                                    <input name="captcha" placeholder="Captcha" type="text">
                                    <div class="wojo simple passive button captcha"><?php echo Session::captcha(); ?></div>
                                </div>
                                <div class="field">
                                    <div class="wojo checkbox">
                                        <input name="agree" type="checkbox" value="1" id="agree">
                                        <label for="agree">
                                            <a href="http://etmam.com.sa/privacy-policy/" class="secondary"
                                                wf-type="link" wf-label="Link" nav-id="nav_18">
                                                <small>Yes, I agree to the privacy policy</small>
                                            </a>
                                        </label>
                                    </div>
                                </div>
                                <div class="field">
                                    <button type="button" data-hide="true" data-url="ajax/" data-action="contact"
                                        name="dosubmit" class="wojo primary fluid button">
                                        <svg width="31" height="13" viewBox="0 0 31 13" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6.8 11.3001L2 6.50008L6.8 1.70008" stroke="white" stroke-width="2"
                                                stroke-linecap="round" />
                                            <path d="M2 6.49991L30 6.49991" stroke="white" stroke-width="2"
                                                stroke-linecap="round" />
                                        </svg>
                                        Send consultation 
                                    </button>
                                </div>
    </form>
    </div>
<?php endif; ?>

                        <?php endif; ?>
                     </div>
                  <?php endif; ?>
                  <!--Show Login End-->
               </div>
            </div>
         </div>
      </div>
   </div>
   </header>