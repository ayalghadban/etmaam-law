<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2024
    * @version 6.20: index.tpl.php, v1.00 1/19/2024 8:32 PM Gewa Exp $
    *
    */

   use Wojo\Core\Router;
   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   if (!$this->auth->checkPlugAcl('background')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<?php switch (Url::segment($this->segments, 3)): case 'edit': ?>
   <form method="post" id="wojo_form" name="wojo_form">
      <div class="wojo margin-bottom form">
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
                              <input type="text" placeholder="<?php echo Language::$word->NAME; ?>" value="<?php echo $this->data->{'title_' . $lang->abbr}; ?>" name="title_<?php echo $lang->abbr ?>">
                           </div>
                        </div>
                     </div>
                     <div class="wojo fields">
                        <div class="field">
                           <label><?php echo Language::$word->_PLG_VBG_HEADER; ?>
                              <small><?php echo $lang->abbr; ?></small>
                           </label>
                           <textarea class="small" name="header_<?php echo $lang->abbr; ?>"><?php echo $this->data->{'header_' . $lang->abbr}; ?></textarea>
                        </div>
                        <div class="field">
                           <label><?php echo Language::$word->_PLG_VBG_TEXT; ?>
                              <small><?php echo $lang->abbr; ?></small>
                           </label>
                           <textarea class="small" name="subtext_<?php echo $lang->abbr; ?>"><?php echo $this->data->{'subtext_' . $lang->abbr}; ?></textarea>
                        </div>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <input type="text" value="<?php echo $this->data->header_color; ?>" data-wcolor="simple" name="header_color"
                      data-color='{"format":"hex","color": "<?php echo $this->data->header_color; ?>"}' readonly>
            </div>
            <div class="field">
               <input type="text" value="<?php echo $this->data->subtext_color; ?>" data-wcolor="simple" name="subtext_color"
                      data-color='{"format":"hex","color": "<?php echo $this->data->subtext_color; ?>"}' readonly>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_PLG_VBG_TYPE; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="type" type="radio" value="local" id="type_1" <?php echo Validator::getChecked($this->data->type, 'local'); ?>>
                  <label for="type_1"><?php echo Language::$word->_PLG_VBG_TYPE_L; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="type" type="radio" value="youtube" id="type_0" <?php echo Validator::getChecked($this->data->type, 'youtube'); ?>>
                  <label for="type_0"><?php echo Language::$word->_PLG_VBG_TYPE_Y; ?></label>
               </div>
            </div>
            <div class="field">
               <label><?php echo Language::$word->_PLG_VBG_SOURCE; ?></label>
               <div class="<?php echo ($this->data->type == 'youtube')? 'youtube' : 'hide-all youtube'; ?>">
                  <input type="text" placeholder="<?php echo Language::$word->_PLG_VBG_TYPE_U; ?>" name="source_youtube" value="<?php echo ($this->data->type == 'youtube')? $this->data->source : ''; ?>">
               </div>
               <div class="<?php echo ($this->data->type == 'local')? 'local' : 'hide-all local'; ?>">
                  <div class="wojo action input">
                     <input id="source" placeholder="<?php echo Language::$word->_PLG_VBG_TYPE_L; ?>"
                            name="source_local" type="text" value="<?php echo ($this->data->type == 'local')? $this->data->source : ''; ?>" readonly>
                     <div class="filepicker wojo small icon secondary button"
                          data-parent="#source" data-ext="videos">
                        <i class="open folder icon"></i>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('admin/plugins', 'background'); ?>" class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-route="admin/plugins/background/action/" data-action="update" name="dosubmit" class="wojo primary button"><?php echo Language::$word->SAVECONFIG; ?></button>
      </div>
      <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
   </form>
   <script type="text/javascript">
      // <![CDATA[
      $(document).ready(function () {
         $('input[name=type]').on('change', function () {
            let value = $(this).val();
            if (value === 'youtube') {
               $('.youtube').removeClass('hide-all');
               $('.local').addClass('hide-all');
            } else {
               $('.youtube').addClass('hide-all');
               $('.local').removeClass('hide-all');
            }
         });
      });
      // ]]>
   </script>
   <?php break; ?>
<?php case 'new': ?>
   <!-- Start new -->
   <form method="post" id="wojo_form" name="wojo_form">
      <div class="wojo margin-bottom form">
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
                     </div>
                     <div class="wojo fields">
                        <div class="field">
                           <label><?php echo Language::$word->_PLG_VBG_HEADER; ?>
                              <small><?php echo $lang->abbr; ?></small>
                           </label>
                           <textarea class="small" name="header_<?php echo $lang->abbr; ?>"></textarea>
                        </div>
                        <div class="field">
                           <label><?php echo Language::$word->_PLG_VBG_TEXT; ?>
                              <small><?php echo $lang->abbr; ?></small>
                           </label>
                           <textarea class="small" name="subtext_<?php echo $lang->abbr; ?>"></textarea>
                        </div>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <input type="text" value="#ecf0f1" data-wcolor="simple" name="header_color"
                      data-color='{"format":"hex","color": "#ecf0f1"}' readonly>
            </div>
            <div class="field">
               <input type="text" value="#ecf0f1" data-wcolor="simple" name="subtext_color"
                      data-color='{"format":"hex","color": "#ecf0f1"}' readonly>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_PLG_VBG_TYPE; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="type" type="radio" value="local" id="type_1" checked="checked">
                  <label for="type_1"><?php echo Language::$word->_PLG_VBG_TYPE_L; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="type" type="radio" value="youtube" id="type_0">
                  <label for="type_0"><?php echo Language::$word->_PLG_VBG_TYPE_Y; ?></label>
               </div>
            </div>
            <div class="field">
               <label><?php echo Language::$word->_PLG_VBG_SOURCE; ?></label>
               <div class="hide-all youtube">
                  <input type="text" placeholder="<?php echo Language::$word->_PLG_VBG_TYPE_U; ?>" name="source_youtube">
               </div>
               <div class="local">
                  <div class="wojo action input">
                     <input id="source" placeholder="<?php echo Language::$word->_PLG_VBG_TYPE_L; ?>"
                            name="source_local" type="text" readonly>
                     <div class="filepicker wojo small icon secondary button"
                          data-parent="#source" data-ext="videos">
                        <i class="open folder icon"></i>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('admin/plugins', 'background'); ?>" class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-route="admin/plugins/background/action/" data-action="add" name="dosubmit" class="wojo primary button"><?php echo Language::$word->SAVECONFIG; ?></button>
      </div>
   </form>
   <script type="text/javascript">
      // <![CDATA[
      $(document).ready(function () {
         $('input[name=type]').on('change', function () {
            let value = $(this).val();
            if (value === 'youtube') {
               $('.youtube').removeClass('hide-all');
               $('.local').addClass('hide-all');
            } else {
               $('.youtube').addClass('hide-all');
               $('.local').removeClass('hide-all');
            }
         });
      });
      // ]]>
   </script>
   <?php break; ?>
<?php default: ?>
   <!-- Start default -->
   <div class="row gutters justify-end">
      <div class="columns auto mobile-100 phone-100">
         <a href="<?php echo Url::url(Router::$path, 'new/'); ?>" class="wojo small secondary fluid button">
            <i class="icon plus alt"></i><?php echo Language::$word->_PLG_VBG_NEW; ?></a>
      </div>
   </div>
   <?php if (!$this->data): ?>
      <div class="center-align">
         <img src="<?php echo ADMINVIEW; ?>images/notfound.svg" alt="" class="wojo big inline image">
         <div class="margin-small-top">
            <p class="wojo small icon alert inverted attached compact message">
               <i class="icon exclamation triangle"></i><?php echo Language::$word->_PLG_VBG_NOPLAY; ?></p>
         </div>
      </div>
   <?php else: ?>
      <div class="row grid phone-1 mobile-1 tablet-2 screen-2 gutters justify-center">
         <?php foreach ($this->data as $row): ?>
            <div class="columns" id="item_<?php echo $row->id; ?>">
               <div class="wojo attached framed card">
                  <div class="content center-align">
                     <img src="<?php echo APLUGINURL; ?>background/view/images/horizontal.png" class="wojo inline image" alt="">
                     <h5 class="margin-top"><?php echo $row->{'title' . Language::$lang}; ?></h5>
                  </div>
                  <div class="divided footer center-align">
                     <a href="<?php echo Url::url(Router::$path . '/edit', $row->id); ?>" class="wojo icon primary inverted button">
                        <i class="icon pencil"></i>
                     </a>
                     <a data-set='{"option":[{"action": "delete","title": "<?php echo Validator::sanitize($row->{'title' . Language::$lang}, 'chars'); ?>","id":<?php echo $row->id; ?>}],"action":"delete","parent":"#item_<?php echo $row->id; ?>", "url":"plugins/background/action/"}' class="wojo icon negative inverted button data">
                        <i class="icon trash"></i>
                     </a>
                  </div>
               </div>
            </div>
         <?php endforeach; ?>
      </div>
   <?php endif; ?>
   <?php break; ?>
<?php endswitch; ?>