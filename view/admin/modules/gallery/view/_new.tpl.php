<?php
   /**
    * _new
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _new.tpl.php, v1.00 5/12/2023 8:50 PM Gewa Exp $
    */

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
                                  name="title_<?php echo $lang->abbr ?>">
                        </div>
                     </div>
                     <div class="field five wide">
                        <label><?php echo Language::$word->ITEMSLUG; ?>
                           <small><?php echo $lang->abbr; ?></small>
                        </label>
                        <div class="wojo large basic input">
                           <input type="text" placeholder="<?php echo Language::$word->ITEMSLUG; ?>"
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
            <input name="thumb_w" type="range" min="100" max="700" step="20" value="300" hidden data-suffix=" px"
                   data-type="labels" data-labels="100,300,500,700">
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_GA_THUMBH; ?>
               <i class="icon asterisk"></i>
            </label>
            <input name="thumb_h" type="range" min="100" max="700" step="20" value="300" hidden data-suffix=" px"
                   data-type="labels" data-labels="100,300,500,700">
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_GA_COLS; ?>
               <i class="icon asterisk"></i>
            </label>
            <input name="cols" type="range" min="2" max="5" step="1" value="3" hidden data-suffix=" itm" data-type="labels"
                   data-labels="2,3,4,5">
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->_MOD_GA_WMARK; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="watermark" type="radio" value="1" id="watermark_1">
               <label for="watermark_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="watermark" type="radio" value="0" id="watermark_0" checked="checked">
               <label for="watermark_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_GA_LIKE; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="likes" type="radio" value="1" id="likes_1" checked="checked">
               <label for="likes_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="likes" type="radio" value="0" id="likes_0">
               <label for="likes_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->_MOD_GA_RESIZE_THE; ?></label>
            <select name="resize">
               <option value="thumbnail">Thumbnail</option>
               <option value="resize">Resize</option>
               <option value="bestFit">Best Fit</option>
               <option value="resizeToHeight">Fit to Height</option>
               <option value="resizeToWidth">Fit to Width</option>
            </select>
         </div>
      </div>
   </div>
   <div class="center-align">
      <a href="<?php echo Url::url('admin/modules', 'gallery/'); ?>"
         class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
      <button type="button" data-route="admin/modules/gallery/action/" data-action="add" name="dosubmit"
              class="wojo primary button"><?php echo Language::$word->_MOD_GA_SUB3; ?></button>
   </div>
</form>