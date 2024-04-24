<?php
   /**
    * _maps_new
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _maps_new.tpl.php, v1.00 5/20/2023 10:58 PM Gewa Exp $
    *
    */

   use Wojo\Language\Language;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<form method="post" id="wojo_form" name="wojo_form">
   <div class="wojo simple segment form margin-bottom">
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->NAME; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->NAME; ?>" name="name">
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_GM_SUB; ?>
               <i class="icon asterisk"></i>
            </label>
            <select name="type">
               <?php echo Utility::loopOptionsSimpleAlt($this->mtype); ?>
            </select>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->_MOD_GM_SUB1; ?></label>
            <input name="zoom" type="range" min="1" max="20" step="1" value="14" hidden data-suffix=" lvl"
                   data-type="labels" data-labels="1,5,10,15,20">
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_GM_SUB1_1; ?></label>
            <input name="minzoom" type="range" min="1" max="10" step="1" value="1" hidden data-suffix=" lvl"
                   data-type="labels" data-labels="1,5,10">
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_GM_SUB1_2; ?></label>
            <input name="maxzoom" type="range" min="10" max="20" step="1" value="20" hidden data-suffix=" lvl"
                   data-type="labels" data-labels="10,15,20">
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->_MOD_GM_SUB3; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="streetview" type="radio" value="1" id="streetview_1" checked="checked">
               <label for="streetview_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="streetview" type="radio" value="0" id="streetview_0">
               <label for="streetview_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_GM_SUB2; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="type_control" type="radio" value="1" id="type_control_1">
               <label for="type_control_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="type_control" type="radio" value="0" id="type_control_0" checked="checked">
               <label for="type_control_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->M_ADDRESS; ?>
               <i class="icon asterisk"></i>
            </label>
            <textarea placeholder="<?php echo Language::$word->M_ADDRESS; ?>" name="body"></textarea>
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_GM_PINS; ?></label>
            <div class="scrollbox height200">
               <div class="row grid phone-1 mobile-2 tablet-3 screen-4 small gutters" id="pinMode">
                  <?php foreach ($this->pins as $k => $row): ?>
                     <div class="columns center-align">
                        <a data-type="<?php echo $row; ?>"
                           class="wojo border radius inline-flex padding-mini<?php echo ($k == 0)? ' highlite' : ''; ?>">
                           <img src="<?php echo FMODULEURL . 'maps/view/images/pins/' . $row; ?>" alt=""
                                class="wojo inline image">
                        </a>
                     </div>
                  <?php endforeach; ?>
                  <?php unset($row); ?>
               </div>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->_MOD_GM_SUB4; ?></label>
            <div class="row grid phone-1 mobile-2 tablet-3 screen-4 gutters" id="layoutMode">
               <?php foreach ($this->styles as $row): ?>
                  <div class="columns">
                     <div
                       class="wojo compact boxed segment<?php echo ('basic' == pathinfo($row, PATHINFO_FILENAME))? ' outline active' : ''; ?>">
                        <a data-type="<?php echo pathinfo($row, PATHINFO_FILENAME); ?>">
                           <img src="<?php echo AMODULEURL . 'maps/view/images/styles/' . $row; ?>" alt="">
                        </a>
                     </div>
                  </div>
               <?php endforeach; ?>
               <?php unset($row); ?>
            </div>
         </div>
      </div>
      <div class="wojo auto wide divider"></div>
      <div class="wojo fields">
         <div class="field">
            <div class="wojo action icon input">
               <i class="icon search"></i>
               <input name="address" placeholder="<?php echo Language::$word->_MOD_GM_SUB5; ?>" type="text">
               <button type="button" name="find_address"
                       class="wojo small primary inverted button"><?php echo Language::$word->FIND; ?></button>
            </div>
            <div class="wojo space divider"></div>
            <div class="wojo basic segment height400" id="google_map"></div>
         </div>
      </div>
   </div>
   <div class="center-align">
      <a href="<?php echo Url::url('admin/modules', 'maps/'); ?>"
         class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
      <button type="button" data-route="admin/modules/maps/action/" data-action="add" name="dosubmit"
              class="wojo primary button"><?php echo Language::$word->_MOD_GM_SUB7; ?></button>
   </div>
   <input type="hidden" name="layout" value="basic">
   <input type="hidden" name="lat" value="43.6532">
   <input type="hidden" name="lng" value="-79.3832">
   <input type="hidden" name="pin" value="basic">
</form>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $this->core->mapapi; ?>&callback=Function.prototype"></script>
<script src="<?php echo AMODULEURL; ?>maps/view/js/gmaps.js"></script>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $.Gmaps({
         url: "<?php echo ADMINURL . 'modules/maps/action/';?>",
         murl: "<?php echo AMODULEURL;?>maps/",
         furl: "<?php echo FMODULEURL;?>maps/",
      });
   });
   // ]]>
</script>