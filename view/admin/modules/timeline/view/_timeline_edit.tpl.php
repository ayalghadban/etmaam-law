<?php
   /**
    * _timeline_edit
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _timeline_edit.tpl.php, v1.00 5/19/2023 10:39 PM Gewa Exp $
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
   <div class="wojo segment form margin-bottom">
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->NAME; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->NAME; ?>" value="<?php echo $this->data->name; ?>"
                   name="name">
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_TML_LMODE; ?></label>
            <select name="colmode">
               <?php echo Utility::loopOptionsSimpleAlt($this->layoutlist, $this->data->colmode); ?>
            </select>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->_MOD_TML_SUB4; ?></label>
            <input name="maxitems" type="range" min="1" max="30" step="1" value="<?php echo $this->data->maxitems; ?>"
                   hidden data-suffix=" itm">
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_TML_SUB5; ?></label>
            <input name="showmore" type="range" min="1" max="30" step="1" value="<?php echo $this->data->showmore; ?>"
                   hidden data-suffix=" itm">
         </div>
      </div>
      <?php if ($this->data->type == 'rss'): ?>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_MOD_TML_SUB8; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input type="text" placeholder="<?php echo Language::$word->_MOD_TML_SUB8; ?>"
                      value="<?php echo $this->data->rssurl; ?>" name="rssurl">
            </div>
         </div>
      <?php endif; ?>
   </div>
   <div class="center-align">
      <a href="<?php echo Url::url('admin/modules', 'timeline/'); ?>"
         class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
      <button type="button" data-route="admin/modules/timeline/action/" data-action="update" name="dosubmit"
              class="wojo primary button"><?php echo Language::$word->_MOD_TML_SUB2; ?></button>
   </div>
   <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
   <input type="hidden" name="type" value="<?php echo $this->data->type; ?>">
</form>