<?php
   /**
    * _page_edit
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _page_edit.tpl.php, v1.00 5/7/2023 8:56 PM Gewa Exp $
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
                     <div class="field five wide">
                        <label><?php echo Language::$word->PAG_NAME; ?>
                           <small><?php echo $lang->abbr; ?></small>
                           <i class="icon asterisk"></i>
                        </label>
                        <div class="wojo basic large input">
                           <input type="text" placeholder="<?php echo Language::$word->NAME; ?>"
                                  value="<?php echo $this->data->{'title_' . $lang->abbr}; ?>"
                                  name="title_<?php echo $lang->abbr ?>">
                        </div>
                     </div>
                     <div class="field five wide">
                        <label><?php echo Language::$word->PAG_SLUG; ?>
                           <small><?php echo $lang->abbr; ?></small>
                        </label>
                        <div class="wojo basic large input">
                           <input type="text" placeholder="<?php echo Language::$word->PAG_SLUG; ?>"
                                  value="<?php echo $this->data->{'slug_' . $lang->abbr}; ?>"
                                  name="slug_<?php echo $lang->abbr; ?>">
                        </div>
                     </div>
                  </div>
                  <div class="wojo fields">
                     <div class="field">
                        <label><?php echo Language::$word->PAG_CAPTION; ?>
                           <small><?php echo $lang->abbr; ?></small>
                        </label>
                        <input type="text" placeholder="<?php echo Language::$word->PAG_CAPTION; ?>"
                               value="<?php echo $this->data->{'caption_' . $lang->abbr}; ?>"
                               name="caption_<?php echo $lang->abbr; ?>">
                     </div>
                     <div class="field">
                        <label><?php echo Language::$word->BGIMG; ?>
                           <small><?php echo $lang->abbr; ?></small>
                        </label>
                        <div class="wojo action input">
                           <input id="bg_<?php echo $lang->abbr; ?>" placeholder="<?php echo Language::$word->BGIMG; ?>"
                                  name="custom_bg_<?php echo $lang->abbr; ?>" type="text"
                                  value="<?php echo $this->data->{'custom_bg_' . $lang->abbr}; ?>" readonly>
                           <div class="wojo small simple button removebg">
                              <?php echo Language::$word->REMOVE; ?>
                           </div>
                           <div class="filepicker wojo small icon secondary button"
                                data-parent="#bg_<?php echo $lang->abbr; ?>" data-ext="images">
                              <i class="open folder icon"></i>
                           </div>
                        </div>
                     </div>
                  </div>
                  <?php if ($this->data->page_type == 'normal' or $this->data->page_type == 'home' or $this->data->page_type == 'policy'): ?>
                     <div class="wojo fields">
                        <div class="field">
                           <a class="wojo secondary button"
                              href="<?php echo Url::url('admin/builder/' . $lang->abbr, $this->data->id); ?>">
                              <i class="icon sliders horizontal"></i>
                              <?php echo Language::$word->PAG_SUB5; ?></a>
                           <?php /*?><textarea class="bodypost" name="body_<?php echo $lang->abbr;?>"><?php echo Url::out_url($this->data->{'body_' . $lang->abbr});?></textarea><?php */ ?>
                        </div>
                     </div>
                  <?php endif; ?>
                  <div class="wojo fields">
                     <div class="field">
                        <label><?php echo Language::$word->METAKEYS; ?>
                           <small><?php echo $lang->abbr; ?></small>
                        </label>
                        <textarea class="small" placeholder="<?php echo Language::$word->METAKEYS; ?>"
                                  name="keywords_<?php echo $lang->abbr; ?>"><?php echo $this->data->{'keywords_' . $lang->abbr}; ?></textarea>
                     </div>
                     <div class="field">
                        <label><?php echo Language::$word->METADESC; ?>
                           <small><?php echo $lang->abbr; ?></small>
                        </label>
                        <textarea class="small" placeholder="<?php echo Language::$word->METADESC; ?>"
                                  name="description_<?php echo $lang->abbr; ?>"><?php echo $this->data->{'description_' . $lang->abbr}; ?></textarea>
                     </div>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>
      </div>
      <div class="row gutters">
         <div class="columns mobile-100">
            <div class="wojo block fields">
               <div class="field">
                  <label><?php echo Language::$word->PAG_ACCLVL; ?></label>
                  <select name="access" id="access_id" data-id="<?php echo $this->data->id; ?>" class="access_id">
                     <?php echo Utility::loopOptionsSimpleAlt($this->access_list, $this->data->access); ?>
                  </select>
               </div>
            </div>
         </div>
         <div class="columns mobile-100">
            <div class="wojo block fields">
               <div class="field">
                  <label><?php echo Language::$word->PAG_MEMLVL; ?></label>
                  <div id="membership">
                     <?php if ($this->data->membership_id): ?>
                        <a data-wdropdown="#membership_id"
                           class="wojo white right fluid button"><?php echo Language::$word->ADM_MEMBS; ?>
                           <i class="icon chevron down"></i>
                        </a>
                        <div class="wojo static dropdown small pointing top-left" id="membership_id">
                           <div class="row grid phone-1 mobile-1 tablet-2 screen-2">
                              <?php echo Utility::loopOptionsMultiple($this->membership_list, 'id', 'title' . Language::$lang, $this->data->membership_id, 'membership_id'); ?>
                           </div>
                        </div>
                     <?php else: ?>
                        <input disabled="disabled" type="text" value="<?php echo Language::$word->PAG_NOMEM_REQ; ?>" name="na">
                     <?php endif; ?>
                  </div>
               </div>
               <div class="field" id="modshow">
                  <input type="hidden" name="module_data" value="0">
               </div>
            </div>
         </div>
      </div>
      <?php if ($this->image): ?>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->MAINIMAGE; ?></label>
               <div class="scrollbox height350">
                  <div class="mason eight">
                     <?php foreach ($this->image as $row): ?>
                        <div class="items">
                           <a data-img="<?php echo str_replace(SITEURL, '', $row);?>" class="flex">
                              <img src="<?php echo $row; ?>" alt="" class="wojo image<?php echo (str_replace(SITEURL, '', $row) == $this->data->main_image)? ' shadow boxed rounded' : ' default'; ?>">
                           </a>
                        </div>
                     <?php endforeach; ?>
                  </div>
               </div>
            </div>
         </div>
      <?php endif; ?>
      <div class="wojo fields">
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
         <div class="field">
            <label><?php echo Language::$word->PAG_NOHEAD; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="show_header" type="radio" value="1"
                      id="show_header_1" <?php echo Validator::getChecked($this->data->show_header, 1); ?>>
               <label for="show_header_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="show_header" type="radio" value="0"
                      id="show_header_0" <?php echo Validator::getChecked($this->data->show_header, 0); ?>>
               <label for="show_header_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <?php if ($this->data->page_type == 'normal'): ?>
            <div class="field">
               <label><?php echo Language::$word->PAG_MDLCOMMENT; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="is_comments" type="radio" value="1"
                         id="is_comments_1" <?php echo Validator::getChecked($this->data->is_comments, 1); ?>>
                  <label for="is_comments_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="is_comments" type="radio" value="0"
                         id="is_comments_0" <?php echo Validator::getChecked($this->data->is_comments, 0); ?>>
                  <label for="is_comments_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
         <?php else: ?>
            <input type="hidden" name="is_comments" value="0">
         <?php endif; ?>
         <?php if ($this->auth->usertype == 'owner'): ?>
            <div class="field">
               <label><?php echo Language::$word->PAG_PGADM; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="is_admin" type="radio" value="1"
                         id="is_admin_1" <?php echo Validator::getChecked($this->data->is_admin, 1); ?>>
                  <label for="is_admin_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="is_admin" type="radio" value="0"
                         id="is_admin_0" <?php echo Validator::getChecked($this->data->is_admin, 0); ?>>
                  <label for="is_admin_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
         <?php else: ?>
            <input type="hidden" name="is_admin" value="<?php echo $this->data->is_admin; ?>">
         <?php endif; ?>
      </div>
   </div>
   <div class="center-align">
      <a href="<?php echo Url::url('admin/pages'); ?>"
         class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
      <button type="button" data-route="admin/pages/action/" data-action="update" name="dosubmit"
              class="wojo primary button"><?php echo Language::$word->PAG_SUB3; ?></button>
   </div>
   <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
   <input type="hidden" name="main_image" value="<?php echo $this->data->main_image; ?>">
</form>
<script src="<?php echo ADMINVIEW . 'js/page.js'; ?>"></script>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $.Page({
         url: "<?php echo ADMINURL . 'ajax/';?>",
         clang: "<?php echo Language::$lang;?>",
         lang: {
            nomemreq: "<?php echo Language::$word->PAG_NOMEM_REQ;?>",
            select: "<?php echo Language::$word->ADM_MEMBS;?>",
         }
      });
   });
   // ]]>
</script>