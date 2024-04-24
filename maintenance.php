<?php
    /**
     * maintenance
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @var object $services
     * @copyright 2023
     * @version $Id: maintenance.php, v1.00 6/21/2023 8:10 AM Gewa Exp $
     *
     */
    
    use Wojo\Cache\Cache;
    use Wojo\Core\Core;
    use Wojo\Language\Language;
    use Wojo\Url\Url;
    
    const _WOJO = true;
    include('init.php');
    
    $d = explode('-', $services->core->offline_d);
    $t = explode(':', $services->core->offline_t);
    
    if (!$services->core->offline) {
        Url::redirect(SITEURL);
    }
?>
<!DOCTYPE html>
<html lang="<?php echo Core::$language; ?>">
<head>
   <meta charset="utf-8">
   <title><?php echo $services->core->company; ?></title>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="apple-mobile-web-app-capable" content="yes">
   <meta name="msapplication-TileColor" content="#da532c">
   <meta name="theme-color" content="#ffffff">
   <meta name="dcterms.rights" content="<?php echo $services->core->company; ?> &copy; All Rights Reserved">
   <meta name="robots" content="index">
   <meta name="robots" content="follow">
   <meta name="revisit-after" content="1 day">
   <meta name="generator" content="Powered by CMS pro! v<?php echo $services->core->wojov; ?>">
   <link rel="apple-touch-icon" sizes="180x180" href="<?php echo SITEURL; ?>assets/favicons/apple-touch-icon.png">
   <link rel="icon" type="image/png" sizes="32x32" href="<?php echo SITEURL; ?>assets/favicons/favicon-32x32.png">
   <link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITEURL; ?>assets/favicons/favicon-16x16.png">
   <link rel="manifest" href="<?php echo SITEURL; ?>assets/favicons/site.webmanifest">
   <link rel="mask-icon" href="<?php echo SITEURL; ?>assets/favicons/safari-pinned-tab.svg" color="#5bbad5">
    <?php if (in_array(Core::$language, array('he', 'ae', 'ir'))): ?>
       <link href="<?php echo THEMEURL . 'cache/' . Cache::cssCache(array(
               'color_rtl.css', 'base_rtl.css', 'transition_rtl.css', 'statistic_rtl.css', 'button_rtl.css', 'icon_rtl.css', 'flag_rtl.css', 'image_rtl.css', 'label_rtl.css', 'form_rtl.css', 'input_rtl.css', 'list_rtl.css', 'card_rtl.css', 'table_rtl.css', 'dropdown_rtl.css', 'statistic_rtl.css', 'message_rtl.css', 'modal_rtl.css', 'comment_rtl.css', 'progress_rtl.css', 'editor_rtl.css',
               'feed_rtl.css', 'comment_rtl.css', 'tooltip_rtl.css', 'utility_rtl.css', 'style_rtl.css'
           ), THEMEBASE); ?>" rel="stylesheet" type="text/css">
    <?php else: ?>
       <link href="<?php echo THEMEURL . 'cache/' . Cache::cssCache(array('color.css', 'base.css', 'transition.css', 'statistic.css', 'button.css', 'icon.css', 'flags.css', 'image.css', 'label.css', 'form.css', 'input.css', 'list.css', 'card.css', 'table.css', 'dropdown.css', 'message.css', 'modal.css', 'comment.css', 'progress.css', 'tooltip.css', 'utility.css', 'style.css'),
               THEMEBASE); ?>" rel="stylesheet" type="text/css">
    <?php endif; ?>
   <script src="<?php echo SITEURL . 'assets/jquery.js'; ?>"></script>
   <script src="<?php echo SITEURL . 'assets/global.js'; ?>"></script>
</head>
<body>
<header id="mheader">
   <a href="#!" class="logo">
       <?php echo ($services->core->logo) ? '<img src="' . SITEURL . 'uploads/' . $services->core->logo . '" alt="' . $services->core->company . '">' : $services->core->company; ?></a>
</header>
<main>
   <div class="wojo-grid height-full">
      <div class="row gutters height-full align-middle justify-between">
         <div class="columns screen-40 tablet-40 mobile-100 phone-100 phone-hide">
            <img src="<?php echo THEMEURL; ?>images/maintenance-mode.svg" alt="Maintenance">
         </div>
         <div class="columns screen-50 tablet-60 mobile-100 phone-100">
            <h4><?php echo Language::$word->FRT_MTNC_TITLE; ?></h4>
            <div class="margin-vertical"><?php echo Url::out_url($services->core->offline_msg); ?></div>
            <div id="mdashboard" class="row">
               <div class="columns auto phone-50">
                  <div class="dash weeks_dash">
                     <div class="digit first">
                        <div class="top">1</div>
                        <div class="bottom">0</div>
                     </div>
                     <div class="digit last">
                        <div class="top">3</div>
                        <div class="bottom">0</div>
                     </div>
                     <span class="dash_title"><?php echo Language::$word->_WEEKS; ?></span>
                  </div>
               </div>
               <div class="columns auto phone-50">
                  <div class="dash days_dash">
                     <div class="digit first">
                        <div class="top">0</div>
                        <div class="bottom">0</div>
                     </div>
                     <div class="digit last">
                        <div class="top">0</div>
                        <div class="bottom">0</div>
                     </div>
                     <span class="dash_title"><?php echo Language::$word->_DAYS; ?></span>
                  </div>
               </div>
               <div class="columns auto phone-50">
                  <div class="dash hours_dash">
                     <div class="digit first">
                        <div class="top">2</div>
                        <div class="bottom">0</div>
                     </div>
                     <div class="digit last">
                        <div class="top">3</div>
                        <div class="bottom">0</div>
                     </div>
                     <span class="dash_title"><?php echo Language::$word->_HOURS; ?></span>
                  </div>
               </div>
               <div class="columns auto phone-50">
                  <div class="dash minutes_dash">
                     <div class="digit first">
                        <div style="display:none" class="top">2</div>
                        <div style="display:block" class="bottom">0</div>
                     </div>
                     <div class="digit last">
                        <div style="display:none" class="top">9</div>
                        <div style="display:block" class="bottom">0</div>
                     </div>
                     <span class="dash_title"><?php echo Language::$word->_MINUTES; ?></span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</main>
<div id="mfooter">Copyright &copy;<?php echo date('Y') . ' ' . $services->core->company; ?> | Powered by <small>[wojo::works] v.<?php echo $services->core->wojov; ?></small>
</div>
<script src="<?php echo SITEURL; ?>assets/countdown.js"></script>
<script type="text/javascript">
   $(document).ready(function () {
      $('#mdashboard').countDown({
         targetDate: {
            'day': <?php echo $d[2];?>,
            'month': <?php echo $d[1];?>,
            'year': <?php echo $d[0];?>,
            'hour': <?php echo $t[0];?>,
            'min': <?php echo $t[1];?>,
            'sec': 0
         }
      });
      // convert logo svg to editable
      $('.logo img').each(function () {
         let $img = $(this);
         let imgID = $img.attr('id');
         let imgClass = $img.attr('class');
         let imgURL = $img.attr('src');

         $.get(imgURL, function (data) {
            let $svg = $(data).find('svg');
            if (typeof imgID !== 'undefined') {
               $svg = $svg.attr('id', imgID);
            }
            if (typeof imgClass !== 'undefined') {
               $svg = $svg.attr('class', imgClass + ' replaced-svg');
            }
            $svg = $svg.removeAttr('xmlns:a');
            $img.replaceWith($svg);
         }, 'xml');

      });
   });
</script>
</body>
</html>