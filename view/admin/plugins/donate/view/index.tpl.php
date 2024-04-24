<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 5/15/2023 8:54 AM Gewa Exp $
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
   if (!$this->auth->checkPlugAcl('donation')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<?php switch (Url::segment($this->segments, 3)): case 'edit': ?>
   <!-- Start edit -->
   <form method="post" id="wojo_form" name="wojo_form">
      <div class="wojo form simple segment margin-bottom">
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_PLG_DP_SUB1; ?>
                  <i class="icon asterisk"></i>
               </label>
               <div class="wojo large basic input">
                  <input type="text" placeholder="<?php echo Language::$word->_PLG_DP_SUB1; ?>"
                         value="<?php echo $this->data->title; ?>" name="title">
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_PLG_DP_TARGET; ?>
                  <i class="icon asterisk"></i>
               </label>
               <div class="wojo labeled input">
                  <div class="wojo simple label">
                     <?php echo Utility::currencySymbol(); ?>
                  </div>
                  <input type="text" placeholder="<?php echo Language::$word->_PLG_DP_TARGET; ?>"
                         value="<?php echo $this->data->target_amount; ?>" name="target_amount">
               </div>
            </div>
            <div class="field">
               <label><?php echo Language::$word->_PLG_DP_SUB3; ?>
                  <i class="icon asterisk"></i>
               </label>
               <select name="redirect_page">
                  <?php echo Utility::loopOptions($this->pagelist, 'id', 'title' . Language::$lang, $this->data->redirect_page); ?>
               </select>
            </div>
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('admin/plugins', 'donate'); ?>"
            class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-route="admin/plugins/donate/action/" data-action="update" name="dosubmit"
                 class="wojo primary button"><?php echo Language::$word->_PLG_DP_SUB4; ?></button>
      </div>
      <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
   </form>
   <?php break; ?>
<?php case 'new': ?>
   <!-- Start new -->
   <form method="post" id="wojo_form" name="wojo_form">
      <div class="wojo form simple segment margin-bottom">
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_PLG_DP_SUB1; ?>
                  <i class="icon asterisk"></i>
               </label>
               <div class="wojo large basic input">
                  <input type="text" placeholder="<?php echo Language::$word->_PLG_DP_SUB1; ?>" name="title">
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_PLG_DP_TARGET; ?>
                  <i class="icon asterisk"></i>
               </label>
               <div class="wojo labeled input">
                  <div class="wojo simple label">
                     <?php echo Utility::currencySymbol(); ?>
                  </div>
                  <input type="text" placeholder="<?php echo Language::$word->_PLG_DP_TARGET; ?>" name="target_amount">
               </div>
            </div>
            <div class="field">
               <label><?php echo Language::$word->_PLG_DP_SUB2; ?>
                  <i class="icon asterisk"></i>
               </label>
               <a data-wdropdown="#gateways" class="wojo secondary right button"><?php echo Language::$word->SELECT; ?>
                  <i class="icon chevron down"></i>
               </a>
               <div class="wojo static dropdown small pointing top-left" id="gateways">
                  <div class="max-width400">
                     <div class="row grid phone-1 mobile-1 tablet-2 screen-2">
                        <?php echo Utility::loopOptionsMultiple($this->gateways, 'id', 'name', false, 'gateways'); ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->_PLG_DP_SUB3; ?>
                  <i class="icon asterisk"></i>
               </label>
               <select name="redirect_page">
                  <?php echo Utility::loopOptions($this->pagelist, 'id', 'title' . Language::$lang); ?>
               </select>
            </div>
            <div class="field"></div>
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('admin/plugins', 'donate'); ?>"
            class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-route="admin/plugins/donate/action/" data-action="add" name="dosubmit"
                 class="wojo primary button"><?php echo Language::$word->_PLG_DP_NEW; ?></button>
      </div>
   </form>
   <?php break; ?>
<?php default: ?>
   <!-- Start default -->
   <div class="row gutters justify-end">
      <div class="columns auto mobile-100 phone-100">
         <a href="<?php echo Url::url(Router::$path, 'new/'); ?>" class="wojo small secondary fluid button">
            <i class="icon plus alt"></i><?php echo Language::$word->_PLG_DP_NEW; ?></a>
      </div>
   </div>
   <?php if (!$this->data): ?>
      <div class="center-align">
         <img src="<?php echo ADMINVIEW; ?>images/notfound.svg" alt="" class="wojo big inline image">
         <div class="margin-small-top">
            <p class="wojo small icon alert inverted attached compact message">
               <i class="icon exclamation triangle"></i><?php echo Language::$word->_PLG_DP_NODON; ?></p>
         </div>
      </div>
   <?php else: ?>
      <div class="row grid phone-1 mobile-1 tablet-2 screen-2 gutters">
         <?php foreach ($this->data as $row): ?>
            <div class="columns" id="item_<?php echo $row->id; ?>">
               <div class="wojo attached card">
                  <a data-content="<?php echo Language::$word->EXPORT; ?>"
                     href="<?php echo ADMINURL . 'plugins/donate/action/?action=export&amp;id=' . $row->id; ?>"
                     class="wojo top right icon simple attached button">
                     <i class="icon wysiwyg table"></i>
                  </a>
                  <div class="content">
                     <h5><?php echo $row->title; ?></h5>
                     <p><?php echo Language::$word->_PLG_DP_TARGET; ?>:
                        <span class="wojo negative text"><?php echo Utility::formatMoney($row->total); ?></span> / <span class="wojo positive text"><?php echo Utility::formatMoney($row->target_amount); ?></span>
                     </p>
                  </div>
                  <div class="footer divided center-align">
                     <a href="<?php echo Url::url(Router::$path . '/edit', $row->id); ?>"
                        class="wojo icon primary inverted button">
                        <i class="icon pencil"></i>
                     </a>
                     <a data-set='{"option":[{"action": "delete","title": "<?php echo Validator::sanitize($row->title, 'chars'); ?>","id":<?php echo $row->id; ?>}],"action":"delete","parent":"#item_<?php echo $row->id; ?>", "url":"plugins/donate/action/"}'
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