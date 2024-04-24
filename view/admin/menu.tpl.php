<?php
   /**
    * menu
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: menu.tpl.php, v1.00 5/6/2023 9:31 AM Gewa Exp $
    *
    */

   use Wojo\Auth\Auth;
   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   if (!Auth::hasPrivileges('manage_menus')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<?php switch (Url::segment($this->segments)): case 'edit': ?>
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
                           <label><?php echo Language::$word->MEN_NAME; ?>
                              <small><?php echo $lang->abbr; ?></small>
                              <i class="icon asterisk"></i>
                           </label>
                           <div class="wojo large basic input">
                              <input type="text" placeholder="<?php echo Language::$word->MEN_NAME; ?>"
                                     value="<?php echo $this->data->{'name_' . $lang->abbr}; ?>"
                                     name="name_<?php echo $lang->abbr ?>">
                           </div>
                        </div>
                        <div class="field five wide">
                           <label><?php echo Language::$word->MEN_CAP; ?>
                              <small><?php echo $lang->abbr; ?></small>
                           </label>
                           <div class="wojo large basic input">
                              <input type="text" placeholder="<?php echo Language::$word->MEN_CAP; ?>"
                                     value="<?php echo $this->data->{'caption_' . $lang->abbr}; ?>"
                                     name="caption_<?php echo $lang->abbr; ?>">
                           </div>
                        </div>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->MEN_CTYPE; ?></label>
               <select name="content_type" id="contenttype">
                  <option value=""><?php echo Language::$word->MEN_SUB1; ?></option>
                  <?php echo Utility::loopOptionsSimpleAlt($this->contenttype, $this->data->content_type); ?>
               </select>
            </div>
            <div class="field">
               <label><?php echo Language::$word->MEN_SUB2; ?></label>
               <div class="<?php echo ($this->data->content_type != 'web')? 'show-all' : 'hide-all'; ?>" id="contentid">
                  <select
                    name="<?php echo($this->data->content_type == 'page'? 'page_id' : ($this->data->content_type == 'module'? 'mod_id' : 'web_id')); ?>"
                    id="page_id">
                     <?php if ($this->data->content_type == 'page'): ?>
                        <?php echo Utility::loopOptions($this->pagelist, 'id', 'title' . Language::$lang, $this->data->page_id); ?>
                     <?php endif; ?>
                     <?php if ($this->data->content_type == 'module'): ?>
                        <?php echo Utility::loopOptions($this->modulelist, 'id', 'title' . Language::$lang, $this->data->mod_id); ?>
                     <?php endif; ?>
                  </select>
               </div>
            </div>
         </div>
         <div id="webid" class="<?php echo ($this->data->content_type == 'web')? 'show-all' : 'hide-all'; ?>">
            <div class="wojo fields">
               <div class="field">
                  <label><?php echo Language::$word->MEN_SUB2; ?></label>
                  <input type="text" name="web" placeholder="<?php echo Language::$word->MEN_TARGET_T; ?>"
                         value="<?php echo $this->data->link; ?>">
               </div>
               <div class="field">
                  <label><?php echo Language::$word->MEN_TARGET_L; ?></label>
                  <select name="target">
                     <option value=""><?php echo Language::$word->MEN_TARGET; ?></option>
                     <option
                       value="_blank" <?php echo Validator::getSelected($this->data->target, '_blank'); ?>><?php echo Language::$word->MEN_TARGET_B; ?></option>
                     <option
                       value="_self" <?php echo Validator::getSelected($this->data->target, '_self'); ?>><?php echo Language::$word->MEN_TARGET_S; ?></option>
                  </select>
               </div>
            </div>
         </div>
         <?php if ($this->data->parent_id == 0): ?>
            <div class="wojo fields">
               <div class="field">
                  <label><?php echo Language::$word->MEN_COLS; ?>
                     <span data-tooltip="<?php echo Language::$word->MEN_COLS_T; ?>"><i
                          class="icon question circle"></i></span>
                  </label>
                  <input name="cols" type="range" min="1" max="4" step="1" value="<?php echo $this->data->cols; ?>"
                         data-suffix=" cols" data-type="labels" data-labels="1,2,3,4" hidden>
               </div>
               <div class="field"></div>
            </div>
         <?php else: ?>
            <input type="hidden" name="cols" value="1">
         <?php endif; ?>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->MEN_ICONS; ?></label>
               <div class="wojo dashed-secondary small segment">
                  <div class="scrollbox styled height500" id="mIcons">
                     <?php include(BASEPATH . 'view/admin/snippets/icons.tpl.php'); ?>
                  </div>
               </div>
               <input name="icon" type="hidden" value="<?php echo $this->data->icon; ?>">
            </div>
            <div class="field">
               <label><?php echo Language::$word->SORTING; ?></label>
               <div id="sortlist" class="nestable">
                  <?php if ($this->droplist) : echo $this->sortlist; endif; ?>
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label class="inverted"><?php echo Language::$word->PUBLISHED; ?></label>
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
         <a href="<?php echo Url::url('admin/menus'); ?>" class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-route="admin/menus/action/" data-action="update" name="dosubmit" class="wojo primary button"><?php echo Language::$word->MEN_SUB4; ?></button>
      </div>
      <input type="hidden" name="parent_id" value="<?php echo $this->data->parent_id; ?>">
      <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
   </form>
   <?php break; ?>
<?php default: ?>
   <form method="post" id="wojo_form" name="wojo_form">
      <div class="wojo form">
         <div class="wojo lang tabs">
            <ul class="nav">
               <?php foreach ($this->langlist as $lang): ?>
                  <li<?php echo ($lang->abbr == $this->core->lang)? ' class="active"' : null; ?>>
                     <a class="lang-color <?php echo Utility::colorToWord($lang->color); ?>"
                        data-tab="lang_<?php echo $lang->abbr; ?>">
                        <span class="flag icon <?php echo $lang->abbr; ?>"></span><?php echo $lang->name; ?></a>
                  </li>
               <?php endforeach; ?>
            </ul>
            <div class="tab spaced">
               <?php foreach ($this->langlist as $lang): ?>
                  <div data-tab="lang_<?php echo $lang->abbr; ?>" class="item">
                     <div class="wojo fields">
                        <div class="field basic">
                           <label><?php echo Language::$word->MEN_NAME; ?>
                              <small><?php echo $lang->abbr; ?></small>
                              <i class="icon asterisk"></i>
                           </label>
                           <div class="wojo large basic input">
                              <input type="text" placeholder="<?php echo Language::$word->MEN_NAME; ?>"
                                     name="name_<?php echo $lang->abbr ?>">
                           </div>
                        </div>
                        <div class="field basic">
                           <label><?php echo Language::$word->MEN_CAP; ?>
                              <small><?php echo $lang->abbr; ?></small>
                           </label>
                           <div class="wojo large basic input">
                              <input type="text" placeholder="<?php echo Language::$word->MEN_CAP; ?>"
                                     name="caption_<?php echo $lang->abbr; ?>">
                           </div>
                        </div>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->MEN_PARENT; ?></label>
               <select id="parent_id" name="parent_id">
                  <option value="0"><?php echo Language::$word->MEN_SUB; ?></option>
                  <?php echo $this->droplist; ?>
               </select>
            </div>
            <div class="field">
               <label><?php echo Language::$word->MEN_CTYPE; ?></label>
               <select name="content_type" id="contenttype">
                  <option value=""><?php echo Language::$word->MEN_SUB1; ?></option>
                  <?php echo Utility::loopOptionsSimpleAlt($this->contenttype); ?>
               </select>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <input type="hidden" name="cols" value="1">
            </div>
            <div class="field" id="contentid">
               <label><?php echo Language::$word->MEN_SUB2; ?></label>
               <select name="content_id" id="page_id">
                  <option value="0"><?php echo Language::$word->NONE; ?></option>
               </select>
            </div>
         </div>
         <div id="webid" class="hide-all">
            <div class="wojo fields">
               <div class="field">
                  <label><?php echo Language::$word->MEN_SUB2; ?></label>
                  <input type="text" name="web" placeholder="<?php echo Language::$word->MEN_TARGET_T; ?>">
               </div>
               <div class="field">
                  <label><?php echo Language::$word->MEN_TARGET_L; ?></label>
                  <select name="target">
                     <option value=""><?php echo Language::$word->MEN_TARGET; ?></option>
                     <option value="_blank"><?php echo Language::$word->MEN_TARGET_B; ?></option>
                     <option value="_self"><?php echo Language::$word->MEN_TARGET_S; ?></option>
                  </select>
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->MEN_ICONS; ?></label>
               <div class="wojo dashed-secondary small segment">
                  <div class="scrollbox styled height500" id="mIcons">
                     <?php include(BASEPATH . 'view/admin/snippets/icons.tpl.php'); ?>
                  </div>
               </div>
               <input name="icon" type="hidden">
            </div>
            <div class="field">
               <label><?php echo Language::$word->SORTING; ?></label>
               <div id="sortlist" class="nestable">
                  <?php if ($this->droplist) : echo $this->sortlist; endif; ?>
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label class="inverted"><?php echo Language::$word->PUBLISHED; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="active" type="radio" value="1" id="active_1" checked="checked">
                  <label for="active_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="active" type="radio" value="0" id="active_0">
                  <label for="active_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
         </div>
      </div>
      <div class="center-align">
         <button type="button" data-route="admin/menus/action/" data-action="add" name="dosubmit"
                 class="wojo primary button"><?php echo Language::$word->MEN_SUB3; ?></button>
      </div>
   </form>
   <?php break; ?>
<?php endswitch; ?>
<script src="<?php echo SITEURL; ?>assets/sortable.js"></script>
<script src="<?php echo ADMINVIEW; ?>js/menu.js"></script>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $.Menu({
         url: "<?php echo ADMINURL;?>",
         lang: {
            delMsg3: "<?php echo Language::$word->TRASH;?>",
            delMsg8: "<?php echo Language::$word->DELCONFIRM3;?>",
            canBtn: "<?php echo Language::$word->CANCEL;?>",
            trsBtn: "<?php echo Language::$word->MTOTRASH;?>",
            nonBtn: "<?php echo Language::$word->NONE;?>",
         }
      });
   });
   // ]]>
</script>