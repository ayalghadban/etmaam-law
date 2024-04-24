<?php
   /**
    * _slider_edit
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _slider_edit.tpl.php, v1.00 5/18/2023 8:38 PM Gewa Exp $
    *
    */

   use Wojo\Language\Language;
   use Wojo\Url\Url;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="row gutters justify-end">
   <div class="columns auto mobile-100 phone-100">
      <a id="addnew" class="wojo secondary icon button spaced">
         <i class="icon plus"></i>
      </a>
      <div class="wojo primary button" data-slide="true" data-trigger="#settings">
         <i class="icon gears"></i>
         <?php echo Language::$word->SETTINGS; ?>
      </div>
   </div>
</div>
<div id="settings" class="hide-all">
   <!-- Configuration -->
   <form method="post" id="wojo_form" name="wojo_form">
      <div class="wojo form segment margin-bottom">
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->NAME; ?>
                  <i class="icon asterisk"></i>
               </label>
               <div class="wojo large basic input">
                  <input type="text" placeholder="<?php echo Language::$word->NAME; ?>" value="<?php echo $this->data->title; ?>"
                         name="title">
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field five wide">
               <label><?php echo Language::$word->_PLG_SL_AUTOPLAY; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="autoplay" type="radio" value="1"
                         id="autoplay_1" <?php echo Validator::getChecked($this->data->autoplay, 1); ?>>
                  <label for="autoplay_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="autoplay" type="radio" value="0"
                         id="autoplay_0" <?php echo Validator::getChecked($this->data->autoplay, 0); ?>>
                  <label for="autoplay_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
            <div class="field five wide">
               <label><?php echo Language::$word->_PLG_SL_LOOPS; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="autoloop" type="radio" value="1"
                         id="autoloop_1" <?php echo Validator::getChecked($this->data->autoloop, 1); ?>>
                  <label for="autoloop_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="autoloop" type="radio" value="0"
                         id="autoloop_0" <?php echo Validator::getChecked($this->data->autoloop, 0); ?>>
                  <label for="autoloop_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field five wide">
               <label><?php echo Language::$word->_PLG_SL_PONHOVER; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="autoplayHoverPause" type="radio" value="1"
                         id="autoplayHoverPause_1" <?php echo Validator::getChecked($this->data->autoplayHoverPause, 1); ?>>
                  <label for="autoplayHoverPause_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="autoplayHoverPause" type="radio" value="0"
                         id="autoplayHoverPause_0" <?php echo Validator::getChecked($this->data->autoplayHoverPause, 0); ?>>
                  <label for="autoplayHoverPause_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
            <div class="field five wide">
               <label><?php echo Language::$word->_PLG_SL_ASPEED; ?>
                  <i class="icon asterisk"></i>
               </label>
               <div class="wojo labeled input">
                  <input placeholder="<?php echo Language::$word->_PLG_SL_ASPEED; ?>" type="text"
                         value="<?php echo $this->data->autoplaySpeed; ?>" name="autoplaySpeed">
                  <div class="wojo simple label"> ms</div>
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field five wide">
               <label><?php echo Language::$word->_PLG_SL_HEIGHT; ?></label>
               <input name="height" type="range" min="5" max="100" step="5" value="<?php echo $this->data->height; ?>" hidden
                      data-suffix=" vh" data-type="labels" data-labels="5,20,50,70,100">
            </div>
         </div>
         <div class="wojo wide auto divider"></div>
         <div class="row blocks phone-1 mobile-2 tablet-2 screen-4 gutters" id="layoutMode">
            <div class="columns center-align">
               <div class="wojo simple segment">
                  <a data-type="basic"
                     class="wojo inline-flex<?php echo ($this->data->layout == 'basic')? ' outline' : ''; ?>">
                     <img
                       src="<?php echo APLUGINURL; ?>slider/view/images/basic.png" alt="">
                  </a>
                  <h6 class="margin-small-top basic">Basic</h6>
               </div>
            </div>
            <div class="columns center-align">
               <div class="wojo simple segment">
                  <a data-type="dots"
                     class="wojo inline-flex<?php echo ($this->data->layout == 'dots')? ' outline' : ''; ?>">
                     <img src="<?php echo APLUGINURL; ?>slider/view/images/dots.png" alt="">
                  </a>
                  <h6 class="margin-small-top basic">Bullets Only</h6>
               </div>
            </div>
            <div class="columns center-align">
               <div class="wojo simple segment">
                  <a data-type="arrows"
                     class="wojo inline-flex<?php echo ($this->data->layout == 'arrows')? ' outline' : ''; ?>">
                     <img
                       src="<?php echo APLUGINURL; ?>slider/view/images/arrows.png" alt="">
                  </a>
                  <h6 class="margin-small-top basic">Arrows Only</h6>
               </div>
            </div>
            <div class="columns center-align">
               <div class="wojo simple segment">
                  <a data-type="standard"
                     class="wojo inline-flex<?php echo ($this->data->layout == 'standard')? ' outline' : ''; ?>">
                     <img src="<?php echo APLUGINURL; ?>slider/view/images/standard.png" alt="">
                  </a>
                  <h6 class="margin-small-top basic">Standard</h6>
               </div>
            </div>
         </div>
         <div class="center aligned">
            <a data-slide="true" data-trigger="#settings"
               class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
            <button type="button" data-route="admin/plugins/slider/action/" data-action="update" name="dosubmit"
                    class="wojo primary button"><?php echo Language::$word->_PLG_SL_SUB8; ?></button>
         </div>
      </div>
      <input type="hidden" name="layout" value="<?php echo $this->data->layout; ?>">
      <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
   </form>
</div>
<!-- Slides -->
<div class="wojo sortable row blocks screen-5 tablet-3 mobile-2 phone-1 gutters justify-center wedit" id="sortable">
   <?php if ($this->slides): ?>
      <?php foreach ($this->slides as $rows): ?>
         <div class="columns" id="item_<?php echo $rows->id; ?>" data-id="<?php echo $rows->id; ?>">
            <div class="wojo card attached" data-mode="<?php echo $rows->mode; ?>"
                 data-color="<?php echo $rows->color; ?>" data-image="<?php echo $rows->image; ?>"
              <?php switch ($rows->mode): case 'tr': ?>
                 style="background-image:url(<?php echo APLUGINURL . 'slider/view/images/transbg.png'; ?>);background-repeat: repeat;"
                 <?php break; ?>
              <?php case 'cl': ?>
                 style="background-color:<?php echo $rows->color; ?>"
                 <?php break; ?>
              <?php default: ?>
                 style="background-image:url(<?php echo UPLOADURL . 'thumbs/' . basename($rows->image); ?>);background-size: cover; background-position: center center; background-repeat: no-repeat;"
                 <?php break; ?>
              <?php endswitch; ?>
            >
               <div class="handle draggable">
                  <i class="icon grip horizontal"></i>
               </div>
               <div class="content">
                  <div class="margin-bottom">
                     <span class="wojo white text" data-editable="true"
                           data-set='{"action": "rename", "id":<?php echo $rows->id; ?>, "url":"plugins/slider/action/"}'><?php echo Validator::truncate($rows->title, 20); ?></span>
                  </div>
                  <div class="wojo fluid white buttons eMenu">
                     <a class="wojo small icon button" data-width="auto" data-tooltip="<?php echo Language::$word->PROP; ?>"
                        data-set='{"mode":"prop","id":<?php echo $rows->id; ?>,"type":"<?php echo $rows->mode; ?>"}'>
                        <i class="icon sliders horizontal"></i>
                     </a>
                     <a href="<?php echo Url::url('/admin/plugins/slider/builder', $rows->id); ?>"
                        class="wojo small icon button" data-width="auto" data-tooltip="<?php echo Language::$word->EDIT; ?>"
                        data-set='{"mode":"edit","id":<?php echo $rows->id; ?>}'>
                        <i class="icon pencil"></i>
                     </a>
                     <a class="wojo small icon button" data-width="auto" data-tooltip="<?php echo Language::$word->DUPLICATE; ?>"
                        data-set='{"mode":"duplicate","id":<?php echo $rows->id; ?>}'>
                        <i class="icon copy"></i>
                     </a>
                     <a class="wojo small icon button" data-width="auto" data-tooltip="<?php echo Language::$word->DELETE; ?>"
                        data-set='{"mode":"delete","id":<?php echo $rows->id; ?>}'>
                        <i class="icon trash"></i>
                     </a>
                  </div>
               </div>
            </div>
         </div>
      <?php endforeach; ?>
   <?php endif; ?>
</div>
<!-- Slide Source-->
<div class="wojo small form segment hide-all" id="source">
   <a id="closeSource" class="wojo primary inverted top right attached spaced icon button">
      <i class="icon check"></i>
   </a>
   <div class="wojo fields align-middle">
      <div class="field two wide labeled">
         <label><?php echo Language::$word->_PLG_SL_SUB12; ?></label>
      </div>
      <div class="field auto">
         <div class="wojo checkbox inline radio fitted">
            <input name="source" type="radio" value="bg" id="source_bg" checked="checked">
            <label for="source_bg">&nbsp;</label>
         </div>
      </div>
      <div data-id="bg_asset" class="field auto hide-all">
         <a class="wojo small primary button bg_image"><?php echo Language::$word->_PLG_SL_SUB13; ?></a>
         <input type="hidden" name="bg_img" value="" id="bg_img">
      </div>
   </div>
   <div class="wojo fields">
      <div class="field two wide labeled">
         <label>Transparent</label>
      </div>
      <div class="field auto">
         <div class="wojo checkbox inline radio fitted">
            <input name="source" type="radio" id="source_tr" value="tr">
            <label for="source_tr">&nbsp;</label>
         </div>
      </div>
   </div>
   <div class="wojo fields">
      <div class="field two wide labeled">
         <label>Solid Color</label>
      </div>
      <div class="field auto">
         <div class="wojo checkbox inline radio fitted">
            <input name="source" type="radio" id="source_cl" value="cl">
            <label for="source_cl">&nbsp;</label>
         </div>
      </div>
      <div data-id="cl_asset" class="auto hide-all">
         <input type="text" value="rgba(0, 128, 96, 0.35)" id="bgColor">
         <input type="hidden" id="bg_color" value="">
      </div>
   </div>
</div>
<link href="<?php echo APLUGINURL; ?>slider/view/css/slider.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo SITEURL; ?>assets/sortable.js"></script>
<script src="<?php echo APLUGINURL; ?>slider/view/js/slider.js"></script>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $.Slider({
         url: "<?php echo ADMINURL . 'plugins/slider/action/';?>",
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