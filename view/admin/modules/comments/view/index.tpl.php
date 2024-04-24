<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 5/11/2023 12:52 PM Gewa Exp $
    */

   use Wojo\Core\Router;
   use Wojo\Date\Date;
   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Url\Url;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   if (!$this->auth->checkModAcl('comments')): print Message::msgError(Language::$word->NOACCESS);
      return; endif;
?>
<?php switch (Url::segment($this->segments, 3)): case 'settings': ?>
   <form method="post" id="wojo_form" name="wojo_form">
      <div class="wojo form segment">
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_MOD_CM_SORTING; ?>
                  <i class="icon asterisk"></i>
               </label>
               <select name="sorting">
                  <option value="DESC" <?php echo Validator::getSelected($this->data->sorting, 'DESC'); ?>>--- <?php echo Language::$word->_MOD_CM_SORTING_T; ?> ---</option>
                  <option value="ASC" <?php echo Validator::getSelected($this->data->sorting, 'ASC'); ?>>--- <?php echo Language::$word->_MOD_CM_SORTING_B; ?> ---</option>
               </select>
            </div>
            <div class="field">
               <label><?php echo Language::$word->_MOD_CM_DATE; ?>
                  <i class="icon asterisk"></i>
               </label>
               <select name="dateformat">
                  <?php echo Date::getShortDate($this->data->dateformat); ?>
                  <?php echo Date::getLongDate($this->data->dateformat); ?>
               </select>
            </div>
            <div class="field auto">
               <label><?php echo Language::$word->_MOD_CM_TSINCE; ?></label>
               <div class="wojo checkbox toggle fitted inline">
                  <input name="timesince" type="checkbox" value="1" id="timesince" <?php echo Validator::getChecked($this->data->timesince, 1); ?>>
                  <label for="timesince"><?php echo Language::$word->YES; ?></label>
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label>
                  <?php echo Language::$word->_MOD_CM_CHAR; ?>
               </label>
               <input name="char_limit" type="range" min="20" max="400" step="10" value="<?php echo $this->data->char_limit; ?>" hidden data-suffix=" char" data-type="labels" data-labels="20,50,100,200,400">
            </div>
            <div class="field">
               <label>
                  <?php echo Language::$word->_MOD_CM_PERPAGE; ?>
               </label>
               <input name="perpage" type="range" min="5" max="50" step="5" value="<?php echo $this->data->perpage; ?>" hidden data-suffix=" itm" data-type="labels" data-labels="5,20,35,50">
            </div>
         </div>
      </div>
      <div class="wojo form segment margin-vertical">
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_MOD_CM_UNAME_R; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="username_req" type="radio" value="1" id="username_req_1" <?php echo Validator::getChecked($this->data->username_req, 1); ?>>
                  <label for="username_req_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="username_req" type="radio" value="0" id="username_req_0" <?php echo Validator::getChecked($this->data->username_req, 0); ?>>
                  <label for="username_req_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
            <div class="field">
               <label><?php echo Language::$word->_MOD_CM_RATING; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="rating" type="radio" value="1" id="rating_1" <?php echo Validator::getChecked($this->data->rating, 1); ?>>
                  <label for="rating_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="rating" type="radio" value="0" id="rating_0" <?php echo Validator::getChecked($this->data->rating, 0); ?>>
                  <label for="rating_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
            <div class="field">
               <label><?php echo Language::$word->_MOD_CM_CAPTCHA; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="show_captcha" type="radio" value="1" id="show_captcha_1" <?php echo Validator::getChecked($this->data->show_captcha, 1); ?>>
                  <label for="show_captcha_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="show_captcha" type="radio" value="0" id="show_captcha_0" <?php echo Validator::getChecked($this->data->show_captcha, 0); ?>>
                  <label for="show_captcha_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_MOD_CM_REG_ONLY; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="public_access" type="radio" value="1" id="public_access_1" <?php echo Validator::getChecked($this->data->public_access, 1); ?>>
                  <label for="public_access_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="public_access" type="radio" value="0" id="public_access_0" <?php echo Validator::getChecked($this->data->public_access, 0); ?>>
                  <label for="public_access_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
            <div class="field">
               <label><?php echo Language::$word->_MOD_CM_AA; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="auto_approve" type="radio" value="1" id="auto_approve_1" <?php echo Validator::getChecked($this->data->auto_approve, 1); ?>>
                  <label for="auto_approve_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="auto_approve" type="radio" value="0" id="auto_approve_0" <?php echo Validator::getChecked($this->data->auto_approve, 0); ?>>
                  <label for="auto_approve_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
            <div class="field">
               <label><?php echo Language::$word->_MOD_CM_NOTIFY; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="notify_new" type="radio" value="1" id="notify_new_1" <?php echo Validator::getChecked($this->data->notify_new, 1); ?>>
                  <label for="notify_new_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="notify_new" type="radio" value="0" id="notify_new_0" <?php echo Validator::getChecked($this->data->notify_new, 0); ?>>
                  <label for="notify_new_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field basic">
               <label><?php echo Language::$word->_MOD_CM_WORDS; ?></label>
               <textarea placeholder="<?php echo Language::$word->_MOD_CM_WORDS; ?>" name="blacklist_words"><?php echo $this->data->blacklist_words; ?></textarea>
            </div>
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('admin/modules/comments'); ?>" class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-route="admin/modules/comments/action/" data-action="configuration" name="dosubmit" class="wojo primary button"><?php echo Language::$word->SAVECONFIG; ?></button>
      </div>
   </form>
   <?php break; ?>
<?php default: ?>
   <div class="row gutters justify-end">
      <div class="columns auto mobile-100 phone-100">
         <a href="<?php echo Url::url(Router::$path, 'settings/'); ?>" class="wojo icon primary button">
            <i class="icon gears"></i>
         </a>
      </div>
   </div>
   <?php if (!$this->data): ?>
      <div class="center-align">
         <img src="<?php echo ADMINVIEW; ?>images/notfound.svg" alt="" class="wojo big inline image">
         <div class="margin-small-top">
            <p class="wojo small icon alert inverted attached compact message">
               <i class="icon exclamation triangle"></i><?php echo Language::$word->_MOD_CM_SUB3; ?></p>
         </div>
      </div>
   <?php else: ?>
      <?php foreach ($this->data as $row): ?>
         <div class="wojo simple segment margin-bottom" id="item_<?php echo $row->id; ?>">
            <div class="row">
               <div class="columns mobile-100 phone-order-2">
                  <p class="text-weight-500 text-size-medium">
                     <?php echo ($row->uname)?: $row->username; ?>
                  </p>
                  <div class="text-size-small"><?php echo $row->body; ?></div>
                  <span class="text-size-tiny text-weight-600 uppercase-text"><?php echo Date::doDate('long_date', $row->created); ?></span>
               </div>
               <div class="columns auto phone-100 right-align">
                  <a data-set='{"option":[{"action":"approve", "id":<?php echo $row->id; ?>}], "url":"modules/comments/action/", "complete":"remove", "parent":"#item_<?php echo $row->id; ?>", "mode":"instant"}' data-tooltip="<?php echo Language::$word->_MOD_CM_SUB4; ?>" class="wojo small icon button primary inverted action">
                     <i class="icon check"></i>
                  </a>
                  <a data-set='{"option":[{"action": "delete","title": "ID","id":<?php echo $row->id; ?>}],"action":"delete","parent":"#item_<?php echo $row->id; ?>", "url":"modules/comments/action/"}' class="wojo small icon button negative inverted data">
                     <i class="icon trash"></i>
                  </a>
               </div>
            </div>
         </div>
      <?php endforeach; ?>
      <div class="row gutters align middle spaced">
         <div class="columns auto mobile-100 phone-100">
            <div class="text-size-small text-weight-500"><?php echo Language::$word->TOTAL . ': ' . $this->pager->items_total; ?> / <?php echo Language::$word->CURPAGE . ': ' . $this->pager->current_page . ' ' . Language::$word->OF . ' ' . $this->pager->num_pages; ?></div>
         </div>
         <div class="columns auto mobile-100 phone-100"><?php echo $this->pager->display(); ?></div>
      </div>
   <?php endif; ?>
   <?php break; ?>
<?php endswitch; ?>