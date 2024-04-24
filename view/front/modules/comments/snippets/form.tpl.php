<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @var object $settings
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 4/29/2023 9:05 AM Gewa Exp $
    *
    */

   use Wojo\Core\Session;
   use Wojo\Language\Language;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="row vertical-gutters">
   <div class="columns">
      <div class="center-align margin-bottom">
         <h5><?php echo Language::$word->_MOD_CM_SUB2; ?></h5>
      </div>
      <div class="wojo basic segment form">
         <form id="wojo_form" name="wojo_form" method="post">
            <div class="wojo fields">
               <div class="field">
                  <label><?php echo Language::$word->RATING; ?></label>
                  <div class="wojo checkbox radio fitted inline">
                     <input name="star" type="radio" value="1" id="star_1">
                     <label for="star_1">1</label>
                  </div>
                  <div class="wojo checkbox radio fitted inline">
                     <input name="star" type="radio" value="2" id="star_2">
                     <label for="star_2">2</label>
                  </div>
                  <div class="wojo checkbox radio fitted inline">
                     <input name="star" type="radio" value="3" checked="checked" id="star_3">
                     <label for="star_3">3</label>
                  </div>
                  <div class="wojo checkbox radio fitted inline">
                     <input name="star" type="radio" value="4" id="star_4">
                     <label for="star_4">4</label>
                  </div>
                  <div class="wojo checkbox radio fitted inline">
                     <input name="star" type="radio" value="5" id="star_5">
                     <label for="star_5">5</label>
                  </div>
               </div>
            </div>
            <div class="wojo fields">
               <div class="field">
                  <label><?php echo Language::$word->NAME; ?>
                     <i class="icon asterisk"></i>
                  </label>
                  <div class="wojo input">
                     <input name="name" placeholder="<?php echo Language::$word->NAME; ?>" type="text" value="<?php if ($this->auth->logged_in) {
                        echo $this->auth->name;
                     } ?>">
                  </div>
               </div>
               <?php if ($settings->show_captcha): ?>
                  <div class="field">
                     <label><?php echo Language::$word->CAPTCHA; ?>
                        <i class="icon asterisk"></i>
                     </label>
                     <div class="wojo labeled input">
                        <input placeholder="<?php echo Language::$word->CAPTCHA; ?>" name="captcha" type="text">
                        <span class="wojo simple passive button captcha"><?php echo Session::captcha(); ?></span>
                     </div>
                  </div>
               <?php endif; ?>
            </div>
            <div class="wojo fields">
               <div class="field">
                  <label><?php echo Language::$word->MESSAGE; ?>
                     <i class="icon asterisk"></i>
                  </label>
                  <div class="wojo input">
                     <textarea data-counter="<?php echo $settings->char_limit; ?>" class="small" id="combody" placeholder="<?php echo Language::$word->MESSAGE; ?>" name="body"></textarea>
                  </div>
                  <p class="wojo tiny text content-right combody_counter"><?php echo Language::$word->_MOD_CM_CHAR . ' <span class="wojo positive text">' . $settings->char_limit . ' </span>'; ?></p>
               </div>
            </div>
            <div class="content-center">
               <button type="button" name="doComment" class="wojo primary button"><?php echo Language::$word->CF_SEND; ?></button>
            </div>
            <input name="parent_id" type="hidden" value="<?php echo $this->data->id ?? $this->row->id; ?>">
            <input name="section" type="hidden" value="<?php echo $this->segments[0]; ?>">
            <input name="url" type="hidden" value="<?php echo Url::uri(); ?>">
         </form>
      </div>
   </div>
</div>