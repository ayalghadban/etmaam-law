<?php
   /**
    * _memberships_new
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _memberships_new.tpl.php, v1.00 5/10/2023 4:08 PM Gewa Exp $
    *
    */

   use Wojo\Date\Date;
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
                     <div class="field basic">
                        <label><?php echo Language::$word->NAME; ?>
                           <small><?php echo $lang->abbr; ?></small>
                           <i class="icon asterisk"></i>
                        </label>
                        <div class="wojo basic large input">
                           <input type="text" placeholder="<?php echo Language::$word->NAME; ?>"
                                  name="title_<?php echo $lang->abbr ?>">
                        </div>
                     </div>
                     <div class="field basic">
                        <label><?php echo Language::$word->DESCRIPTION; ?>
                           <small><?php echo $lang->abbr; ?></small>
                        </label>
                        <div class="wojo basic large input">
                           <input type="text" placeholder="<?php echo Language::$word->DESCRIPTION; ?>"
                                  name="description_<?php echo $lang->abbr; ?>">
                        </div>
                     </div>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>
      </div>
      <div class="row">
         <div class="columns screen-70 tablet-70 mobile-100">
            <div class="wojo fields align-middle">
               <div class="field four wide labeled">
                  <label><?php echo Language::$word->MEM_PRICE; ?>
                     <i class="icon asterisk"></i>
                  </label>
               </div>
               <div class="field">
                  <div class="wojo labeled input">
                     <div class="wojo simple label"><?php echo Utility::currencySymbol(); ?></div>
                     <input type="text" placeholder="<?php echo Language::$word->MEM_PRICE; ?>" name="price">
                  </div>
               </div>
            </div>
            <div class="wojo fields align-middle">
               <div class="field four wide labeled">
                  <label><?php echo Language::$word->MEM_DAYS; ?>
                     <i class="icon asterisk"></i>
                  </label>
               </div>
               <div class="field">
                  <div class="wojo input">
                     <input type="text" placeholder="<?php echo Language::$word->MEM_DAYS; ?>" name="days">
                     <select name="period">
                        <?php echo Utility::loopOptionsSimpleAlt(Date::getMembershipPeriod()); ?>
                     </select>
                  </div>
               </div>
            </div>
            <div class="wojo fields align-middle">
               <div class="field four wide labeled">
                  <label><?php echo Language::$word->MEM_PRIVATE; ?></label>
               </div>
               <div class="field">
                  <div class="wojo checkbox radio fitted inline">
                     <input name="private" type="radio" value="1" id="private_1">
                     <label for="private_1"><?php echo Language::$word->YES; ?></label>
                  </div>
                  <div class="wojo checkbox radio fitted inline">
                     <input name="private" type="radio" value="0" id="private_0" checked="checked">
                     <label for="private_0"><?php echo Language::$word->NO; ?></label>
                  </div>
               </div>
            </div>
            <div class="wojo fields align-middle">
               <div class="field four wide labeled">
                  <label><?php echo Language::$word->MEM_REC; ?></label>
               </div>
               <div class="field">
                  <div class="wojo checkbox radio fitted inline">
                     <input name="recurring" type="radio" value="1" id="recurring_1" checked="checked">
                     <label for="recurring_1"><?php echo Language::$word->YES; ?></label>
                  </div>
                  <div class="wojo checkbox radio fitted inline">
                     <input name="recurring" type="radio" value="0" id="recurring_0">
                     <label for="recurring_0"><?php echo Language::$word->NO; ?></label>
                  </div>
               </div>
            </div>
            <div class="wojo fields align-middle">
               <div class="field four wide labeled">
                  <label><?php echo Language::$word->PUBLISHED; ?></label>
               </div>
               <div class="field">
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
         <div class="columns screen-30 tablet-30 mobile-100">
            <input type="file" name="thumb" data-type="image" accept="image/png, image/jpeg">
         </div>
      </div>
   </div>
   <div class="center-align">
      <a href="<?php echo Url::url('admin/memberships'); ?>"
         class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
      <button type="button" data-route="admin/memberships/action/" data-action="add" name="dosubmit"
              class="wojo primary button"><?php echo Language::$word->MEM_SUB1; ?></button>
   </div>
</form>