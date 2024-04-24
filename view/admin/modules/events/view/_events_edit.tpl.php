<?php
   /**
    * _events_edit
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _events_edit.tpl.php, v1.00 5/10/2023 8:52 PM Gewa Exp $
    *
    */

   use Wojo\Date\Date;
   use Wojo\Language\Language;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;
   use Wojo\Validator\Validator;

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
                     <div class="field">
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
                     <div class="field">
                        <label><?php echo Language::$word->_MOD_EM_VENUE; ?>
                           <small><?php echo $lang->abbr; ?></small>
                        </label>
                        <div class="wojo large basic input">
                           <input type="text" placeholder="<?php echo Language::$word->_MOD_EM_VENUE; ?>"
                                  value="<?php echo $this->data->{'venue_' . $lang->abbr}; ?>"
                                  name="venue_<?php echo $lang->abbr ?>">
                        </div>
                     </div>
                  </div>
                  <div class="wojo fields">
                     <div class="field basic">
                  <textarea class="altpost"
                            name="body_<?php echo $lang->abbr; ?>"><?php echo Url::out_url($this->data->{'body_' . $lang->abbr}); ?></textarea>
                     </div>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->_MOD_EM_CONTACT; ?></label>
            <input type="text" placeholder="<?php echo Language::$word->_MOD_EM_CONTACT; ?>"
                   value="<?php echo $this->data->contact_person; ?>" name="contact_person">
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_EM_PHONE; ?></label>
            <input type="text" placeholder="<?php echo Language::$word->_MOD_EM_PHONE; ?>"
                   value="<?php echo $this->data->contact_phone; ?>" name="contact_phone">
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_EM_EMAIL; ?></label>
            <input type="text" placeholder="<?php echo Language::$word->_MOD_EM_EMAIL; ?>"
                   value="<?php echo $this->data->contact_email; ?>" name="contact_email">
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->_MOD_EM_DATE_ST; ?>
               <i class="icon asterisk"></i>
            </label>
            <div class="wojo icon input">
               <input id="fromdate" name="date_start" type="text" placeholder="<?php echo Language::$word->_MOD_EM_DATE_ST; ?>"
                      value="<?php echo Date::doDate('calendar', $this->data->date_start); ?>" readonly>
               <i class="icon calendar event"></i>
            </div>
         </div>
         <div class="field auto">
            <label><?php echo Language::$word->_MOD_EM_TIME_ST; ?>
               <i class="icon asterisk"></i>
            </label>
            <div class="wojo icon input">
               <input name="time_start" type="text" placeholder="<?php echo Language::$word->_MOD_EM_TIME_ST; ?>"
                      value="<?php echo $this->data->time_start; ?>" readonly class="timepick">
               <i class="icon time"></i>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_EM_DATE_E; ?>
               <i class="icon asterisk"></i>
            </label>
            <div class="wojo icon input">
               <input id="enddate" name="date_end" type="text" placeholder="<?php echo Language::$word->_MOD_EM_DATE_E; ?>"
                      value="<?php echo Date::doDate('calendar', $this->data->date_end); ?>" readonly>
               <i class="icon calendar event"></i>
            </div>
         </div>
         <div class="field auto">
            <label><?php echo Language::$word->_MOD_EM_TIME_ET; ?>
               <i class="icon asterisk"></i>
            </label>
            <div class="wojo icon input">
               <input name="time_end" type="text" placeholder="<?php echo Language::$word->_MOD_EM_TIME_ET; ?>"
                      value="<?php echo $this->data->time_end; ?>" readonly class="timepick">
               <i class="icon time"></i>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->_MOD_EM_COLOUR; ?></label>
            <input type="text" value="<?php echo $this->data->color; ?>" data-wcolor="simple" name="color"
                   data-color='{"format":"hex","color": "<?php echo $this->data->color; ?>"}' readonly>
         </div>
         <div class="field">
            <label><?php echo Language::$word->PUBLISHED; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="active" type="radio" value="1"
                      id="active_1" <?php echo Validator::getChecked($this->data->active, 1); ?>>
               <label for="active_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="active" type="radio" value="0"
                      id="active_0" <?php echo Validator::getChecked($this->data->active, 0); ?>>
               <label for="active_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
      </div>
   </div>
   <div class="center-align">
      <a href="<?php echo Url::url('admin/modules', 'events/'); ?>"
         class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
      <button type="button" data-route="admin/modules/events/action/" data-action="update" name="dosubmit"
              class="wojo primary button"><?php echo Language::$word->_MOD_EM_UPDATE; ?></button>
   </div>
   <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
</form>