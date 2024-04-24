<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 5/18/2023 3:59 PM Gewa Exp $
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
   if (!$this->auth->checkPlugAcl('carousel')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<?php switch (Url::segment($this->segments, 3)): case 'edit': ?>
   <!-- Start edit -->
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
                           <textarea class="bodypost" name="body_<?php echo $lang->abbr; ?>"><?php echo Url::out_url($this->data->{'body_' . $lang->abbr}); ?></textarea>
                        </div>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_PLG_CRL_SUB11; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="dots" type="radio" value="1" id="dots_1" <?php echo Validator::getChecked($this->data->dots, 1); ?>>
                  <label for="dots_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="dots" type="radio" value="0" id="dots_0" <?php echo Validator::getChecked($this->data->dots, 0); ?>>
                  <label for="dots_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
            <div class="field">
               <label><?php echo Language::$word->_PLG_CRL_SUB12; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="nav" type="radio" value="1" id="nav_1" <?php echo Validator::getChecked($this->data->nav, 1); ?>>
                  <label for="nav_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="nav" type="radio" value="0" id="nav_0" <?php echo Validator::getChecked($this->data->nav, 0); ?>>
                  <label for="nav_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
            <div class="field">
               <label><?php echo Language::$word->_PLG_CRL_SUB7; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="autoplay" type="radio" value="1" id="autoplay_1" <?php echo Validator::getChecked($this->data->autoplay, 1); ?>>
                  <label for="autoplay_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="autoplay" type="radio" value="0" id="autoplay_0" <?php echo Validator::getChecked($this->data->autoplay, 0); ?>>
                  <label for="autoplay_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_PLG_CRL_SUB14; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input name="large" type="range" min="1" max="8" step="1" value="<?php echo $this->settings->responsive->{1024}->items; ?>" hidden data-suffix=" &nbsp; &nbsp;<i class='icon tv'></i>">
            </div>
            <div class="field">
               <label><?php echo Language::$word->_PLG_CRL_SUB14; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input name="medium" type="range" min="1" max="8" step="1" value="<?php echo $this->settings->responsive->{769}->items; ?>" hidden data-suffix=" &nbsp; &nbsp;<i class='icon tablet'></i>">
            </div>
            <div class="field">
               <label><?php echo Language::$word->_PLG_CRL_SUB14; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input name="small" type="range" min="1" max="8" step="1" value="<?php echo $this->settings->responsive->{0}->items; ?>" hidden data-suffix=" &nbsp; &nbsp;<i class='icon phone'></i>">
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_PLG_CRL_SUB8; ?></label>
               <input name="margin" type="range" min="0" max="128" step="1" value="<?php echo $this->settings->margin; ?>" hidden data-suffix=" px">
            </div>
            <div class="field">
               <label><?php echo Language::$word->_PLG_CRL_SUB9; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="center" type="radio" value="1" id="center_1" <?php echo Validator::getChecked($this->data->center, 1); ?>>
                  <label for="center_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="center" type="radio" value="0" id="center_0" <?php echo Validator::getChecked($this->data->center, 0); ?>>
                  <label for="center_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
            <div class="field">
               <label><?php echo Language::$word->_PLG_CRL_SUB13; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="loop" type="radio" value="1" id="loop_1" <?php echo Validator::getChecked($this->data->autoloop, 1); ?>>
                  <label for="loop_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="loop" type="radio" value="0" id="loop_0" <?php echo Validator::getChecked($this->data->autoloop, 0); ?>>
                  <label for="loop_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('admin/plugins', 'carousel'); ?>" class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-route="admin/plugins/carousel/action/" data-action="update" name="dosubmit" class="wojo primary button"><?php echo Language::$word->SAVECONFIG; ?></button>
      </div>
      <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
   </form>
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
                           <textarea class="bodypost" name="body_<?php echo $lang->abbr; ?>"></textarea>
                        </div>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_PLG_CRL_SUB11; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="dots" type="radio" value="1" id="dots_1" checked="checked">
                  <label for="dots_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="dots" type="radio" value="0" id="dots_0">
                  <label for="dots_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
            <div class="field">
               <label><?php echo Language::$word->_PLG_CRL_SUB12; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="nav" type="radio" value="1" id="nav_1">
                  <label for="nav_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="nav" type="radio" value="0" id="nav_0" checked="checked">
                  <label for="nav_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
            <div class="field">
               <label><?php echo Language::$word->_PLG_CRL_SUB7; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="autoplay" type="radio" value="1" id="autoplay_1" checked="checked">
                  <label for="autoplay_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="autoplay" type="radio" value="0" id="autoplay_0">
                  <label for="autoplay_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_PLG_CRL_SUB14; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input name="large" type="range" min="1" max="8" step="1" value="1" hidden data-suffix=" &nbsp; &nbsp;<i class='icon tv'></i>">
            </div>
            <div class="field">
               <label><?php echo Language::$word->_PLG_CRL_SUB14; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input name="medium" type="range" min="1" max="8" step="1" value="1" hidden data-suffix=" &nbsp; &nbsp;<i class='icon tablet'></i>">
            </div>
            <div class="field">
               <label><?php echo Language::$word->_PLG_CRL_SUB14; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input name="small" type="range" min="1" max="8" step="1" value="1" hidden data-suffix=" &nbsp; &nbsp;<i class='icon phone'></i>">
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_PLG_CRL_SUB8; ?></label>
               <input name="margin" type="range" min="0" max="128" step="1" value="0" hidden data-suffix=" px">
            </div>
            <div class="field">
               <label><?php echo Language::$word->_PLG_CRL_SUB9; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="center" type="radio" value="1" id="center_1">
                  <label for="center_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="center" type="radio" value="0" id="center_0" checked="checked">
                  <label for="center_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
            <div class="field">
               <label><?php echo Language::$word->_PLG_CRL_SUB13; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="loop" type="radio" value="1" id="loop_1">
                  <label for="loop_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="loop" type="radio" value="0" id="loop_0" checked="checked">
                  <label for="loop_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('admin/plugins', 'carousel'); ?>" class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-route="admin/plugins/carousel/action/" data-action="add" name="dosubmit" class="wojo primary button"><?php echo Language::$word->SAVECONFIG; ?></button>
      </div>
   </form>
   <?php break; ?>
<?php default: ?>
   <!-- Start default -->
   <div class="row gutters justify-end">
      <div class="columns auto mobile-100 phone-100">
         <a href="<?php echo Url::url(Router::$path, 'new/'); ?>" class="wojo small secondary fluid button">
            <i class="icon plus alt"></i><?php echo Language::$word->_PLG_CRL_NEW; ?></a>
      </div>
   </div>
   <?php if (!$this->data): ?>
      <div class="center-align">
         <img src="<?php echo ADMINVIEW; ?>images/notfound.svg" alt="" class="wojo big inline image">
         <div class="margin-small-top">
            <p class="wojo small icon alert inverted attached compact message">
               <i class="icon exclamation triangle"></i><?php echo Language::$word->_PLG_CRL_NOPLAY; ?></p>
         </div>
      </div>
   <?php else: ?>
      <div class="row grid phone-1 mobile-1 tablet-2 screen-2 gutters justify-center">
         <?php foreach ($this->data as $row): ?>
            <div class="columns" id="item_<?php echo $row->id; ?>">
               <div class="wojo attached framed card">
                  <div class="content center-align">
                     <img src="<?php echo APLUGINURL; ?>carousel/view/images/horizontal.png" class="wojo inline image" alt="">
                     <h5 class="margin-top"><?php echo $row->{'title' . Language::$lang}; ?></h5>
                  </div>
                  <div class="divided footer center-align">
                     <a href="<?php echo Url::url(Router::$path . '/edit', $row->id); ?>" class="wojo icon primary inverted button">
                        <i class="icon pencil"></i>
                     </a>
                     <a data-set='{"option":[{"action": "delete","title": "<?php echo Validator::sanitize($row->{'title' . Language::$lang}, 'chars'); ?>","id":<?php echo $row->id; ?>}],"action":"delete","parent":"#item_<?php echo $row->id; ?>", "url":"plugins/carousel/action/"}' class="wojo icon negative inverted button data">
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