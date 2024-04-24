<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 5/18/2023 1:40 PM Gewa Exp $
    *
    */
   
   use Wojo\Core\Router;
   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Url\Url;
   use Wojo\Validator\Validator;
   
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   if (!$this->auth->checkPlugAcl('rss')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<?php switch (Url::segment($this->segments, 3)): case 'edit': ?>
   <!-- Start edit -->
   <form method="post" id="wojo_form" name="wojo_form">
      <div class="wojo form segment margin-bottom">
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->NAME; ?>
                  <i class="icon asterisk"></i>
               </label>
               <div class="wojo large basic input">
                  <input type="text" placeholder="<?php echo Language::$word->NAME; ?>" value="<?php echo $this->data->title; ?>"
                         name="title">
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_PLG_RSS_URL; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input type="text" placeholder="<?php echo Language::$word->_PLG_RSS_URL; ?>"
                      value="<?php echo $this->data->url; ?>" name="url">
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_PLG_RSS_SHOW_DATE; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="show_date" type="radio" value="1"
                         id="show_date_1" <?php echo Validator::getChecked($this->data->show_date, 1); ?>>
                  <label for="show_date_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="show_date" type="radio" value="0"
                         id="show_date_0" <?php echo Validator::getChecked($this->data->show_date, 0); ?>>
                  <label for="show_date_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
            <div class="field">
               <label><?php echo Language::$word->_PLG_RSS_SHOW_DESC; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="show_desc" type="radio" value="1"
                         id="show_desc_1" <?php echo Validator::getChecked($this->data->show_desc, 1); ?>>
                  <label for="show_desc_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="show_desc" type="radio" value="0"
                         id="show_desc_0" <?php echo Validator::getChecked($this->data->show_desc, 0); ?>>
                  <label for="show_desc_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_PLG_RSS_ITEMS; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input name="items" type="range" min="1" max="30" step="1" value="<?php echo $this->data->items; ?>" hidden
                      data-suffix=" itm">
            </div>
            <div class="field">
               <label><?php echo Language::$word->_PLG_RSS_BODYTRIM; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input name="max_words" type="range" min="10" max="300" step="10"
                      value="<?php echo $this->data->max_words; ?>" hidden data-suffix=" word">
            </div>
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('admin/plugins', 'rss'); ?>"
            class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-route="admin/plugins/rss/action/" data-action="update" name="dosubmit"
                 class="wojo primary button"><?php echo Language::$word->SAVECONFIG; ?></button>
      </div>
      <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
   </form>
   <?php break; ?>
<?php case 'new': ?>
   <!-- Start new -->
   <form method="post" id="wojo_form" name="wojo_form">
      <div class="wojo form segment margin-bottom">
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->NAME; ?>
                  <i class="icon asterisk"></i>
               </label>
               <div class="wojo large basic input">
                  <input type="text" placeholder="<?php echo Language::$word->NAME; ?>" name="title">
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_PLG_RSS_URL; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input type="text" placeholder="<?php echo Language::$word->_PLG_RSS_URL; ?>" name="url">
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_PLG_RSS_SHOW_DATE; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="show_date" type="radio" value="1" id="show_date_1" checked="checked">
                  <label for="show_date_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="show_date" type="radio" value="0" id="show_date_0">
                  <label for="show_date_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
            <div class="field">
               <label><?php echo Language::$word->_PLG_RSS_SHOW_DESC; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="show_desc" type="radio" value="1" id="show_desc_1" checked="checked">
                  <label for="show_desc_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="show_desc" type="radio" value="0" id="show_desc_0">
                  <label for="show_desc_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_PLG_RSS_ITEMS; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input name="items" type="range" min="1" max="30" step="1" value="5" hidden data-suffix=" itm">
            </div>
            <div class="field">
               <label><?php echo Language::$word->_PLG_RSS_BODYTRIM; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input name="max_words" type="range" min="10" max="300" step="10" value="50" hidden data-suffix=" word">
            </div>
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('admin/plugins', 'rss'); ?>"
            class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-route="admin/plugins/rss/action/" data-action="add" name="dosubmit"
                 class="wojo primary button"><?php echo Language::$word->_PLG_RSS_TITLE1; ?></button>
      </div>
   </form>
   <?php break; ?>
<?php default: ?>
   <!-- Start default -->
   <div class="row gutters justify-end">
      <div class="columns auto mobile-100 phone-100">
         <a href="<?php echo Url::url(Router::$path, 'new/'); ?>" class="wojo small secondary fluid button">
            <i class="icon plus alt"></i><?php echo Language::$word->_PLG_RSS_NEW; ?></a>
      </div>
   </div>
   <?php if (!$this->data): ?>
      <div class="center-align">
         <img src="<?php echo ADMINVIEW; ?>images/notfound.svg" alt="" class="wojo big inline image">
         <div class="margin-small-top">
            <p class="wojo small icon alert inverted attached compact message">
               <i class="icon exclamation triangle"></i><?php echo Language::$word->_PLG_RSS_NORSS; ?></p>
         </div>
      </div>
   <?php else: ?>
      <div class="row phone-1 mobile-1 tablet-2 screen-2 gutters">
         <?php foreach ($this->data as $row): ?>
            <div class="columns" id="item_<?php echo $row->id; ?>">
               <div class="wojo attached framed card">
                  <div class="content">
                     <h5><?php echo $row->title; ?></h5>
                     <p><?php echo $row->url; ?></p>
                  </div>
                  <div class="divided footer center-align">
                     <a href="<?php echo Url::url(Router::$path . '/edit', $row->id); ?>"
                        class="wojo icon primary inverted button">
                        <i class="icon pencil"></i>
                     </a>
                     <a data-set='{"option":[{"action": "delete","title": "<?php echo Validator::sanitize($row->title, 'chars'); ?>","id":<?php echo $row->id; ?>}],"action":"delete","parent":"#item_<?php echo $row->id; ?>", "url":"plugins/rss/action/"}'
                        class="wojo icon negative inverted button data">
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