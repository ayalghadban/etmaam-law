<?php
   /**
    * _adblock_edit
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _adblock_edit.tpl.php, v1.00 5/12/2023 16:57 PM Gewa Exp $
    */

   use Wojo\Date\Date;
   use Wojo\Language\Language;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<form method="post" id="wojo_form" name="wojo_form">
   <div class="wojo form">
      <div class="wojo lang tabs">
         <ul class="nav">
            <?php foreach ($this->langlist as $lang): ?>
               <li<?php echo ($lang->abbr == $this->core->lang)? ' class="active"' : null; ?>>
                  <a class="lang-color <?php echo Utility::colorToWord($lang->color); ?>"
                     data-tab="lang_<?php echo $lang->abbr; ?>"><span
                       class="flag icon <?php echo $lang->abbr; ?>"></span><?php echo $lang->name; ?></a>
               </li>
            <?php endforeach; ?>
         </ul>
         <div class="tab spaced">
            <?php foreach ($this->langlist as $lang): ?>
               <div data-tab="lang_<?php echo $lang->abbr; ?>" class="item">
                  <div class="wojo fields">
                     <div class="field basic">
                        <label><?php echo Language::$word->NAME; ?>
                           <small><?php echo $lang->abbr; ?></small>
                           <i class="icon asterisk"></i>
                        </label>
                        <div class="wojo large basic input">
                           <input type="text" placeholder="<?php echo Language::$word->NAME; ?>"
                                  value="<?php echo $this->data->{'title_' . $lang->abbr}; ?>"
                                  name="title_<?php echo $lang->abbr ?>">
                        </div>
                     </div>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->_MOD_AB_SUB; ?>
               <i class="icon asterisk"></i>
            </label>
            <div class="wojo icon input">
               <input id="datefrom" data-rangeto="#dateto" name="start_date" type="text"
                      placeholder="<?php echo Language::$word->_MOD_AB_SUB10; ?>"
                      value="<?php echo Date::doDate('calendar', $this->data->start_date); ?>" readonly class="datepick">
               <i class="icon calendar range"></i>
               <input id="dateto" data-rangefrom="#datefrom" name="end_date" type="text"
                      placeholder="<?php echo Language::$word->_MOD_AB_SUB11; ?>"
                      value="<?php echo Date::doDate('MM/dd/yyyy', $this->data->end_date); ?>" readonly class="datepick">
               <i class="icon calendar range"></i>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_AB_SUB1; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->_MOD_AB_SUB1; ?>"
                   value="<?php echo $this->data->total_views_allowed; ?>" name="max_views">
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->_MOD_AB_SUB2; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->_MOD_AB_SUB2; ?>"
                   value="<?php echo $this->data->total_clicks_allowed; ?>" name="max_clicks">
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_AB_SUB3; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->_MOD_AB_SUB3; ?>"
                   value="<?php echo $this->data->minimum_ctr; ?>" name="min_ctr">
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <?php if ($this->data->image): ?>
               <label><?php echo Language::$word->_MOD_AB_SUB4; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input type="file" data-buttonText="<?php echo Language::$word->BROWSE; ?>" name="image" id="image"
                      class="filestyle" data-input="false">
               <div class="margin-vertical">
                  <label class="label"><?php echo Language::$word->_MOD_AB_SUB5; ?>
                     <i class="icon asterisk"></i>
                  </label>
                  <input type="text" placeholder="<?php echo Language::$word->_MOD_AB_SUB5; ?>"
                         value="<?php echo $this->data->image_link; ?>" name="image_link">
               </div>
               <label><?php echo Language::$word->_MOD_AB_SUB6; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input type="text" placeholder="<?php echo Language::$word->_MOD_AB_SUB6; ?>"
                      value="<?php echo $this->data->image_alt; ?>" name="image_alt">
               <input type="hidden" name="html" value="">
            <?php else: ?>
               <label><?php echo Language::$word->_MOD_AB_SUB7; ?>
                  <i class="icon asterisk"></i>
               </label>
               <textarea name="html"
                         placeholder="<?php echo Language::$word->_MOD_AB_SUB7; ?>"><?php echo $this->data->html; ?></textarea>
            <?php endif; ?>
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_AB_SUB4; ?></label>
            <?php if ($this->data->image): ?>
               <img src="<?php echo FPLUGINURL . $this->data->plugin_id . '/' . $this->data->image; ?>"
                    class="wojo normal image" alt="">
            <?php endif; ?>
         </div>
      </div>
   </div>
   <div class="center-align">
      <a href="<?php echo Url::url('admin/modules', 'adblock/'); ?>"
         class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
      <button type="button" data-route="admin/modules/adblock/action/" data-action="update" name="dosubmit"
              class="wojo primary button"><?php echo Language::$word->_MOD_AB_UPDATE; ?></button>
   </div>
   <input type="hidden" name="banner_type" value="<?php echo $this->data->image? 'yes' : 'no'; ?>">
   <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
   <input type="hidden" name="plugin_id" value="<?php echo $this->data->plugin_id; ?>">
</form>