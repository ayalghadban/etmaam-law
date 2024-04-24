<?php
   /**
    * _blog_new
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _blog_new.tpl.php, v1.00 5/8/2023 12:17 AM Gewa Exp $
    *
    */

   use Wojo\Core\Session;
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
                  <a class="lang-color <?php echo Utility::colorToWord($lang->color); ?>" data-tab="lang_<?php echo $lang->abbr; ?>">
                     <span class="flag icon <?php echo $lang->abbr; ?>"></span><?php echo $lang->name; ?></a>
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
                           <input type="text" placeholder="<?php echo Language::$word->NAME; ?>" name="title_<?php echo $lang->abbr ?>">
                        </div>
                     </div>
                     <div class="field">
                        <label><?php echo Language::$word->ITEMSLUG; ?>
                           <small><?php echo $lang->abbr; ?></small>
                        </label>
                        <div class="wojo large basic input">
                           <input type="text" placeholder="<?php echo Language::$word->ITEMSLUG; ?>" name="slug_<?php echo $lang->abbr ?>">
                        </div>
                     </div>
                  </div>
                  <div class="wojo fields">
                     <div class="field">
                        <textarea class="bodypost" name="body_<?php echo $lang->abbr; ?>"></textarea>
                     </div>
                  </div>
                  <div class="wojo fields">
                     <div class="field">
                        <label><?php echo Language::$word->METAKEYS; ?>
                           <small><?php echo $lang->abbr; ?></small>
                        </label>
                        <textarea class="small" placeholder="<?php echo Language::$word->METAKEYS; ?>" name="keywords_<?php echo $lang->abbr; ?>"></textarea>
                     </div>
                     <div class="field">
                        <label><?php echo Language::$word->METADESC; ?>
                           <small><?php echo $lang->abbr; ?></small>
                        </label>
                        <textarea class="small" placeholder="<?php echo Language::$word->METADESC; ?>" name="description_<?php echo $lang->abbr; ?>"></textarea>
                     </div>
                  </div>
                  <div class="wojo fields">
                     <div class="field basic">
                        <label><?php echo Language::$word->_MOD_AM_SUB2; ?>
                           <small><?php echo $lang->abbr; ?></small>
                        </label>
                        <input type="text" placeholder="<?php echo Language::$word->_MOD_AM_SUB2; ?>" class="wojo tags" name="tags_<?php echo $lang->abbr ?>">
                     </div>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>
      </div>
   </div>
   <div class="wojo form margin-bottom">
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->PAG_MEMLVL; ?></label>
            <a data-wdropdown="#membership_id" class="wojo secondary right button"><?php echo Language::$word->M_SUB8; ?>
               <i class="icon chevron down"></i>
            </a>
            <div class="wojo static dropdown small pointing top-left" id="membership_id">
               <div class="max-width400">
                  <div class="row grid phone-1 mobile-1 tablet-2 screen-2">
                     <?php echo Utility::loopOptionsMultiple($this->membership_list, 'id', 'title' . Language::$lang, '', 'membership_id'); ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->CATEGORIES; ?></label>
            <div class="wojo segment">
               <div class="scrollbox height300">
                  <div class="wojo relaxed divided list">
                     <?php echo $this->droplist; ?>
                  </div>
               </div>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->MAINIMAGE; ?></label>
            <input type="file" name="thumb" data-class="left" data-type="image" accept="image/png, image/jpeg">
            <div class="vertical margin">
               <label class="label"><?php echo Language::$word->_MOD_AM_SUB1; ?></label>
               <input type="text" placeholder="<?php echo Language::$word->_MOD_AM_SUB1; ?>" name="caption">
            </div>
            <label><?php echo Language::$word->FILE; ?></label>
            <div class="row horizontal-gutters">
               <div class="columns auto">
                  <input type="file" data-input="false" data-badge="true" data-buttonText="<?php echo Language::$word->BROWSE; ?>" name="file" id="file" class="filestyle">
               </div>
               <div class="columns">
                  <a id="removeFile" class="wojo negative icon button">
                     <i class="icon x alt"></i>
                  </a>
               </div>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field basic">
            <label><?php echo Language::$word->_MOD_AM_SUB3; ?></label>
            <div class="row grid phone-1 tablet-2 screen-4 gutters" id="layoutMode">
               <div class="columns center-align">
                  <div class="wojo simple segment">
                     <a class="wojo inline-flex outline" data-type="1">
                        <img src="<?php echo AMODULEURL; ?>blog/view/images/align_left.png" alt="">
                     </a>
                  </div>
               </div>
               <div class="columns center-align">
                  <div class="wojo simple segment">
                     <a class="wojo inline-flex" data-type="2">
                        <img src="<?php echo AMODULEURL; ?>blog/view/images/align_right.png" alt="">
                     </a>
                  </div>
               </div>
               <div class="columns center-align">
                  <div class="wojo simple segment">
                     <a class="wojo inline-flex" data-type="3">
                        <img src="<?php echo AMODULEURL; ?>blog/view/images/align_top.png" alt="">
                     </a>
                  </div>
               </div>
               <div class="columns center-align">
                  <div class="wojo simple segment">
                     <a class="wojo inline-flex" data-type="4">
                        <img src="<?php echo AMODULEURL; ?>blog/view/images/align_bottom.png" alt="">
                     </a>
                  </div>
               </div>
            </div>
            <input type="hidden" name="layout" value="1">
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->_MOD_AM_SUB6; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="show_created" type="radio" value="1" id="show_created_1" checked="checked">
               <label for="show_created_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="show_created" type="radio" value="0" id="show_created_0">
               <label for="show_created_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_AM_SUB7; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="show_author" type="radio" value="1" id="show_author_1" checked="checked">
               <label for="show_author_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="show_author" type="radio" value="0" id="show_author_0">
               <label for="show_author_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_AM_SUB8; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="show_ratings" type="radio" value="1" id="show_ratings_1" checked="checked">
               <label for="show_ratings_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="show_ratings" type="radio" value="0" id="show_ratings_0">
               <label for="show_ratings_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->_MOD_AM_SUB9; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="show_comments" type="radio" value="1" id="show_comments_1" checked="checked">
               <label for="show_comments_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="show_comments" type="radio" value="0" id="show_comments_0">
               <label for="show_comments_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_AM_SUB10; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="show_like" type="radio" value="1" id="show_like_1" checked="checked">
               <label for="show_like_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="show_like" type="radio" value="0" id="show_like_0">
               <label for="show_like_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_AM_SUB11; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="show_sharing" type="radio" value="1" id="show_sharing_1" checked="checked">
               <label for="show_sharing_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="show_sharing" type="radio" value="0" id="show_sharing_0">
               <label for="show_sharing_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field basic">
            <label><?php echo Language::$word->PUBLISHED; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="active" type="radio" value="1" id="active_1" checked="checked">
               <label for="active_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="active" type="radio" value="0" id="active_0">
               <label for="active_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field"></div>
      </div>
   </div>
   <div class="wojo form segment margin-bottom">
      <div class="field">
         <label><?php echo Language::$word->IMAGES; ?></label>
         <input type="file" name="images" id="images" data-input="false" data-buttonText="<?php echo Language::$word->MULTIPLE; ?>" data-fields='{"action":"images","id":<?php echo Session::get('blogtoken'); ?>}' class="filestyle" multiple>
         <div class="scrollbox margin-top height300">
            <div class="wojo sortable row blocks phone-1 mobile-2 tablet-3 screen-5 gutters" id="sortable"></div>
         </div>
      </div>
   </div>
   <div class="center-align">
      <a href="<?php echo Url::url('admin/modules', 'blog/'); ?>" class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
      <button type="button" data-route="admin/modules/blog/action/" data-action="add" name="dosubmit" class="wojo primary button"><?php echo Language::$word->_MOD_AM_NEW; ?></button>
   </div>
</form>