<?php
   /**
    * _maps_edit
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _maps_edit.tpl.php, v1.00 5/20/2023 11:58 PM Gewa Exp $
    *
    */

   use Wojo\Language\Language;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;
   use Wojo\Validator\Validator;

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
            <input type="text" placeholder="<?php echo Language::$word->NAME; ?>" value="<?php echo $this->data->name; ?>"
                   name="name">
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_GM_SUB; ?>
               <i class="icon asterisk"></i>
            </label>
            <select name="type">
               <?php echo Utility::loopOptionsSimpleAlt($this->mtype, $this->data->type); ?>
            </select>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->_MOD_GM_SUB1; ?></label>
            <input name="zoom" type="range" min="1" max="20" step="1" value="<?php echo $this->data->zoom; ?>" hidden
                   data-suffix=" lvl" data-type="labels" data-labels="1,5,10,15,20">
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_GM_SUB1_1; ?></label>
            <input name="minzoom" type="range" min="1" max="10" step="1" value="<?php echo $this->minmaxzoom[0]; ?>" hidden
                   data-suffix=" lvl" data-type="labels" data-labels="1,5,10">
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_GM_SUB1_2; ?></label>
            <input name="maxzoom" type="range" min="10" max="20" step="1" value="<?php echo $this->minmaxzoom[1]; ?>" hidden
                   data-suffix=" lvl" data-type="labels" data-labels="10,15,20">
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->_MOD_GM_SUB3; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="streetview" type="radio" value="1"
                      id="streetview_1" <?php echo Validator::getChecked($this->data->streetview, 1); ?>>
               <label for="streetview_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="streetview" type="radio" value="0"
                      id="streetview_0" <?php echo Validator::getChecked($this->data->streetview, 0); ?>>
               <label for="streetview_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_GM_SUB2; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="type_control" type="radio" value="1"
                      id="type_control_1" <?php echo Validator::getChecked($this->data->type_control, 1); ?>>
               <label for="type_control_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="type_control" type="radio" value="0"
                      id="type_control_0" <?php echo Validator::getChecked($this->data->type_control, 0); ?>>
               <label for="type_control_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->M_ADDRESS; ?>
               <i class="icon asterisk"></i>
            </label>
            <textarea placeholder="<?php echo Language::$word->M_ADDRESS; ?>"
                      name="body"><?php echo $this->data->body; ?></textarea>
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_GM_PINS; ?></label>
            <div class="scrollbox height200">
               <div class="row grid phone-1 mobile-2 tablet-3 screen-4 small gutters" id="pinMode">
                  <?php foreach ($this->pins as $row): ?>
                     <div class="columns center-align">
                        <a data-type="<?php echo $row; ?>"
                           class="wojo border radius inline-flex padding-mini<?php echo ($this->data->pin == $row)? ' highlite' : ''; ?>">
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
                     <div class="wojo compact boxed segment<?php echo ($this->data->layout == pathinfo($row, PATHINFO_FILENAME))? ' outline active' : ''; ?>">
                        <a data-type="<?php echo pathinfo($row, PATHINFO_FILENAME); ?>">
                           <img src="<?php echo AMODULEURL . 'maps/view/images/styles/' . $row; ?>" alt=""
                             class="wojo rounded image">
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
         <div class="field basic">
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
      <button type="button" data-route="admin/modules/maps/action/" data-action="update" name="dosubmit"
              class="wojo primary button"><?php echo Language::$word->_MOD_GM_UPDATE; ?></button>
   </div>
   <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
   <input type="hidden" name="layout" value="<?php echo $this->data->layout; ?>">
   <input type="hidden" name="lat" value="<?php echo $this->data->lat; ?>">
   <input type="hidden" name="lng" value="<?php echo $this->data->lng; ?>">
   <input type="hidden" name="pin" value="<?php echo $this->data->pin; ?>">
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