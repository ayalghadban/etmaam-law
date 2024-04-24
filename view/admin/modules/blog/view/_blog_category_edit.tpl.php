<?php
   /**
    * _blog_category_edit
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _blog_category_edit.tpl.php, v1.00 5/10/2023 18:34 AM Gewa Exp $
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
   <div class="wojo form">
      <div class="wojo lang tabs">
         <ul class="nav">
            <?php foreach ($this->langlist as $lang): ?>
               <li<?php echo ($lang->abbr == $this->core->lang)? ' class="active"' : null; ?>>
                  <a class="lang-color <?php echo Utility::colorToWord($lang->color); ?>" data-tab="lang_<?php echo $lang->abbr; ?>">
                     <span class="flag icon <?php echo $lang->abbr; ?>"></span><?php echo $lang->name; ?></a>
               </li>
            <?php endforeach; ?>
         </ul>
         <div class="tab spaced">
            <?php foreach ($this->langlist as $lang): ?>
               <div data-tab="lang_<?php echo $lang->abbr; ?>" class="item">
                  <div class="wojo fields">
                     <div class="field five wide">
                        <label><?php echo Language::$word->NAME; ?>
                           <small><?php echo $lang->abbr; ?></small>
                           <i class="icon asterisk"></i>
                        </label>
                        <div class="wojo basic large input">
                           <input type="text" placeholder="<?php echo Language::$word->NAME; ?>" value="<?php echo $this->data->{'name_' . $lang->abbr}; ?>" name="name_<?php echo $lang->abbr ?>">
                        </div>
                     </div>
                     <div class="field five wide">
                        <label><?php echo Language::$word->CATSLUG; ?>
                           <small><?php echo $lang->abbr; ?></small>
                        </label>
                        <div class="wojo basic large input">
                           <input type="text" placeholder="<?php echo Language::$word->CATSLUG; ?>" value="<?php echo $this->data->{'slug_' . $lang->abbr}; ?>" name="slug_<?php echo $lang->abbr; ?>">
                        </div>
                     </div>
                  </div>
                  <div class="wojo fields">
                     <div class="field basic">
                        <label><?php echo Language::$word->METAKEYS; ?>
                           <small><?php echo $lang->abbr; ?></small>
                        </label>
                        <textarea class="small" placeholder="<?php echo Language::$word->METAKEYS; ?>" name="keywords_<?php echo $lang->abbr; ?>"><?php echo $this->data->{'keywords_' . $lang->abbr}; ?></textarea>
                     </div>
                     <div class="field basic">
                        <label><?php echo Language::$word->METADESC; ?>
                           <small><?php echo $lang->abbr; ?></small>
                        </label>
                        <textarea class="small" placeholder="<?php echo Language::$word->METADESC; ?>" name="description_<?php echo $lang->abbr; ?>"><?php echo $this->data->{'description_' . $lang->abbr}; ?></textarea>
                     </div>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>
      </div>
   </div>
   <div class="wojo form segment margin-bottom">
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->_MOD_AM_SUB13; ?></label>
            <select id="parent_id" name="parent_id">
               <option value="0"><?php echo Language::$word->MEN_SUB; ?></option>
               <?php echo $this->droplist; ?>
            </select>
         </div>
         <div class="field">
            <label><?php echo Language::$word->PUBLISHED; ?></label>
            <div class="wojo checkbox toggle fitted inline">
               <input name="active" type="radio" value="1" id="active_1" <?php echo Validator::getChecked($this->data->active, 1); ?>>
               <label for="active_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox toggle fitted inline">
               <input name="active" type="radio" value="0" id="active_0" <?php echo Validator::getChecked($this->data->active, 0); ?>>
               <label for="active_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
      </div>
      <div class="wojo form margin-bottom" id="mSort">
         <div id="sortlist" class="nestable">
            <?php if ($this->droplist) : echo $this->sortlist; endif; ?>
         </div>
      </div>
      <div class="wojo block fields">
         <div class="field basic">
            <label><?php echo Language::$word->_MOD_AM_SUB3; ?></label>
            <div class="row grid phone-1 tablet-2 tablet-4 screen-4 gutters" id="layoutMode">
               <div class="columns center-align">
                  <div class="wojo simple segment">
                     <a data-type="1" class="wojo inline-flex<?php echo ($this->data->layout == 1)? ' outline' : ''; ?>">
                        <img src="<?php echo AMODULEURL; ?>blog/view/images/list.svg" alt="" class="wojo normal image">
                     </a>
                  </div>
               </div>
               <div class="columns center-align">
                  <div class="wojo simple segment">
                     <a data-type="2" class="wojo inline-flex<?php echo ($this->data->layout == 2)? ' outline' : ''; ?>">
                        <img src="<?php echo AMODULEURL; ?>blog/view/images/modern.svg" alt="" class="wojo normal image">
                     </a>
                  </div>
               </div>
               <div class="columns center-align">
                  <div class="wojo simple segment">
                     <a data-type="3" class="wojo inline-flex<?php echo ($this->data->layout == 3)? ' outline' : ''; ?>">
                        <img src="<?php echo AMODULEURL; ?>blog/view/images/masonry.svg" alt="" class="wojo normal image">
                     </a>
                  </div>
               </div>
               <div class="columns center-align">
                  <div class="wojo simple segment">
                     <a data-type="4" class="wojo inline-flex<?php echo ($this->data->layout == 4)? ' outline' : ''; ?>">
                        <img src="<?php echo AMODULEURL; ?>blog/view/images/classic.svg" alt="" class="wojo normal image">
                     </a>
                  </div>
               </div>
            </div>
            <input type="hidden" name="layout" value="<?php echo $this->data->layout; ?>">
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_AM_SUB17; ?></label>
            <input name="perpage" type="range" min="5" max="20" step="1" value="<?php echo $this->data->perpage; ?>" hidden data-suffix=" itm">
         </div>
      </div>
      <div class="wojo wide auto divider"></div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->_MOD_AM_SUB16; ?></label>
            <div class="scrollbox styled height500" id="cIcons">
               <?php include(BASEPATH . 'view/admin/snippets/icons.tpl.php'); ?>
            </div>
            <input name="icon" type="hidden" value="<?php echo $this->data->icon; ?>">
         </div>
      </div>
   </div>
   <div class="center-align">
      <a href="<?php echo Url::url('admin/modules/blog/categories'); ?>" class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
      <button type="button" data-route="admin/modules/blog/action/" data-action="category" name="dosubmit" class="wojo primary button"><?php echo Language::$word->_MOD_AM_UPDATECAT; ?></button>
   </div>
   <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
</form>