<?php
   /**
    * _edit
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _edit.tpl.php, v1.00 5/12/2023 8:56 PM Gewa Exp $
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
                     <div class="field five wide">
                        <label><?php echo Language::$word->ITEMSLUG; ?>
                           <small><?php echo $lang->abbr; ?></small>
                        </label>
                        <div class="wojo large basic input">
                           <input type="text" placeholder="<?php echo Language::$word->ITEMSLUG; ?>"
                                  value="<?php echo $this->data->{'slug_' . $lang->abbr}; ?>"
                                  name="slug_<?php echo $lang->abbr ?>">
                        </div>
                     </div>
                  </div>
                  <div class="wojo fields">
                     <div class="field">
                        <label><?php echo Language::$word->DESCRIPTION; ?>
                           <small><?php echo $lang->abbr; ?></small>
                        </label>
                        <input type="text" placeholder="<?php echo Language::$word->DESCRIPTION; ?>"
                               value="<?php echo $this->data->{'description_' . $lang->abbr}; ?>"
                               name="description_<?php echo $lang->abbr ?>">
                     </div>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->_MOD_GA_THUMBW; ?>
               <i class="icon asterisk"></i>
            </label>
            <input name="thumb_w" type="range" min="100" max="700" step="20" value="<?php echo $this->data->thumb_w; ?>"
                   hidden data-suffix=" px" data-type="labels" data-labels="100,300,500,700">
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_GA_THUMBH; ?>
               <i class="icon asterisk"></i>
            </label>
            <input name="thumb_h" type="range" min="100" max="700" step="20" value="<?php echo $this->data->thumb_h; ?>"
                   hidden data-suffix=" px" data-type="labels" data-labels="100,300,500,700">
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_GA_COLS; ?>
               <i class="icon asterisk"></i>
            </label>
            <input name="cols" type="range" min="2" max="5" step="1" value="<?php echo $this->data->cols; ?>" hidden
                   data-suffix=" itm" data-type="labels" data-labels="2,3,4,5">
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->_MOD_GA_WMARK; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="watermark" type="radio" value="1"
                      id="watermark_1" <?php echo Validator::getChecked($this->data->watermark, 1); ?>>
               <label for="watermark_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="watermark" type="radio" value="0"
                      id="watermark_0" <?php echo Validator::getChecked($this->data->watermark, 0); ?>>
               <label for="watermark_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_GA_LIKE; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="likes" type="radio" value="1"
                      id="likes_1" <?php echo Validator::getChecked($this->data->likes, 1); ?>>
               <label for="likes_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="likes" type="radio" value="0"
                      id="likes_0" <?php echo Validator::getChecked($this->data->likes, 0); ?>>
               <label for="likes_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
      </div>
   </div>
   <div class="wojo form segment margin-bottom" id="uResize">
      <h6><?php echo Language::$word->_MOD_GA_RESIZE_TH; ?></h6>
      <p class="wojo small negative icon inverted message">
         <i
           class="icon information square"></i><?php echo Language::$word->_MOD_GA_INFO; ?></p>
      <div class="wojo fields">
         <div class="field">
            <div class="wojo checkbox radio fitted inline">
               <input name="resize" type="radio" value="thumbnail" id="thumbnail_1" checked="checked">
               <label for="thumbnail_1">Thumbnail</label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="resize" type="radio" value="resize" id="resize_1">
               <label for="resize_1">Resize</label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="resize" type="radio" value="bestFit" id="bestFit_1">
               <label for="bestFit_1">Best Fit</label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="resize" type="radio" value="resizeToHeight" id="resizeToHeight_1">
               <label for="resizeToHeight_1">Fit to Height</label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="resize" type="radio" value="resizeToWidth" id="resizeToWidth_1">
               <label for="resizeToWidth_1">Fit to Width</label>
            </div>
         </div>
      </div>
      <button type="button" name="imgprop" id="doResize"
              class="wojo small secondary button"><?php echo Language::$word->GO; ?></button>
   </div>
   <div class="center-align">
      <a href="<?php echo Url::url('admin/modules', 'gallery'); ?>"
         class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
      <button type="button" data-route="admin/modules/gallery/action/" data-url="modules/gallery" data-action="update" name="dosubmit"
              class="wojo primary button"><?php echo Language::$word->_MOD_GA_SUB2; ?></button>
   </div>
   <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
   <input type="hidden" name="dir" value="<?php echo $this->data->dir; ?>">
</form>