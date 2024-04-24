<?php
   /**
    * _slider_new
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _slider_new.tpl.php, v1.00 5/18/2023 8:25 PM Gewa Exp $
    *
    */

   use Wojo\Language\Language;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<form method="post" id="wojo_form" name="wojo_form">
   <div class="wojo form segment margin-bottom">
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->NAME; ?>
               <i class="icon asterisk"></i>
            </label>
            <div class="wojo large basic input">
               <input type="text" placeholder="<?php echo Language::$word->NAME; ?>" name="title">
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->_PLG_SL_AUTOPLAY; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="autoplay" type="radio" value="1" id="autoplay_1" checked="checked">
               <label for="autoplay_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="autoplay" type="radio" value="0" id="autoplay_0">
               <label for="autoplay_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field five wide">
            <label><?php echo Language::$word->_PLG_SL_LOOPS; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="autoloop" type="radio" value="1" id="autoloop_1" checked="checked">
               <label for="autoloop_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="autoloop" type="radio" value="0" id="autoloop_0">
               <label for="autoloop_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->_PLG_SL_PONHOVER; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="autoplayHoverPause" type="radio" value="1" id="autoplayHoverPause_1" checked="checked">
               <label for="autoplayHoverPause_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="autoplayHoverPause" type="radio" value="0" id="autoplayHoverPause_0">
               <label for="autoplayHoverPause_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field five wide">
            <label><?php echo Language::$word->_PLG_SL_ASPEED; ?>
               <i class="icon asterisk"></i>
            </label>
            <div class="wojo labeled input">
               <input placeholder="<?php echo Language::$word->_PLG_SL_ASPEED; ?>" type="text" value="1000" name="autoplaySpeed">
               <div class="wojo simple label"> ms</div>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->_PLG_SL_HEIGHT; ?></label>
            <input name="height" type="range" min="5" max="100" step="5" value="50" hidden data-suffix=" vh"
                   data-type="labels" data-labels="5,20,50,70,100">
         </div>
      </div>
      <div class="wojo wide auto divider"></div>
      <div class="row blocks phone-1 mobile-2 tablet-2 screen-4 gutters" id="layoutMode">
         <div class="columns center-align">
            <div class="wojo simple segment">
               <a class="wojo inline-flex outline" data-type="basic">
                  <img src="<?php echo APLUGINURL; ?>slider/view/images/basic.png" alt="">
               </a>
               <h6 class="margin-small-top basic">Basic</h6>
            </div>
         </div>
         <div class="columns center-align">
            <div class="wojo simple segment">
               <a class="wojo inline-flex" data-type="arrows">
                  <img src="<?php echo APLUGINURL; ?>slider/view/images/arrows.png" alt="">
               </a>
               <h6 class="margin-small-top basic">Arrows Only</h6>
            </div>
         </div>
         <div class="columns center-align">
            <div class="wojo simple segment">
               <a class="wojo inline-flex" data-type="dots">
                  <img src="<?php echo APLUGINURL; ?>slider/view/images/dots.png" alt="">
               </a>
               <h6 class="margin-small-top basic">Bullets Only</h6>
            </div>
         </div>
         <div class="columns center-align">
            <div class="wojo simple segment">
               <a class="wojo inline-flex" data-type="standard">
                  <img src="<?php echo APLUGINURL; ?>slider/view/images/standard.png" alt="">
               </a>
               <h6 class="margin-small-top basic">Standard</h6>
            </div>
         </div>
      </div>
   </div>
   <div class="center-align">
      <a href="<?php echo Url::url('admin/plugins', 'slider'); ?>"
         class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
      <button type="button" data-route="admin/plugins/slider/action/" data-action="add"  name="dosubmit"
              class="wojo primary button"><?php echo Language::$word->_PLG_SL_SUB8; ?></button>
   </div>
   <input type="hidden" name="layout" value="basic">
</form>
<link href="<?php echo APLUGINURL; ?>slider/view/css/slider.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo SITEURL; ?>assets/sortable.js"></script>
<script src="<?php echo APLUGINURL; ?>slider/view/js/slider.js"></script>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $.Slider({
         url: "<?php echo ADMINURL . 'plugins/slider/action/'?>",
         aurl: "<?php echo ADMINVIEW;?>",
         surl: "<?php echo SITEURL;?>",
         turl: "<?php echo THEMEURL;?>",
         lang: {
            canBtn: "<?php echo Language::$word->CANCEL;?>",
            updBtn: "<?php echo Language::$word->UPDATE;?>",
         }
      });
   });
   // ]]>
</script>