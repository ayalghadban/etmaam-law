<?php
   /**
    * gateway
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: gateway.tpl.php, v1.00 4/30/2023 9:17 AM Gewa Exp $
    *
    */

   use Wojo\Core\Router;
   use Wojo\Language\Language;
   use Wojo\Url\Url;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   $this->auth->checkOwner();
?>
<?php switch (Url::segment($this->segments)): case 'edit': ?>
   <form method="post" id="wojo_form" name="wojo_form">
      <div class="wojo simple segment form margin-bottom">
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->GW_NAME; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input type="text" placeholder="<?php echo Language::$word->GW_NAME; ?>" value="<?php echo $this->data->displayname; ?>" name="displayname">
            </div>
            <div class="field">
               <label><?php echo $this->data->extra_txt; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input type="text" placeholder="<?php echo $this->data->extra_txt; ?>" value="<?php echo $this->data->extra; ?>" name="extra">
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo $this->data->extra_txt2; ?></label>
               <input type="text" placeholder="<?php echo $this->data->extra_txt2; ?>" value="<?php echo $this->data->extra2; ?>" name="extra2">
            </div>
            <div class="field">
               <label><?php echo $this->data->extra_txt3; ?>
               </label>
               <input type="text" placeholder="<?php echo $this->data->extra_txt3; ?>" value="<?php echo $this->data->extra3; ?>" name="extra3">
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->GW_LIVE; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="live" type="radio" value="1" id="live_1" <?php echo Validator::getChecked($this->data->live, 1); ?>>
                  <label for="live_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="live" type="radio" value="0" id="live_0" <?php echo Validator::getChecked($this->data->live, 0); ?>>
                  <label for="live_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
            <div class="field">
               <label><?php echo Language::$word->ACTIVE; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="active" type="radio" value="1" id="active_1" <?php echo Validator::getChecked($this->data->active, 1); ?>>
                  <label for="active_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="active" type="radio" value="0" id="active_0" <?php echo Validator::getChecked($this->data->active, 0); ?>>
                  <label for="active_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->GW_IPNURL; ?></label>
               <?php echo SITEURL . 'gateways/' . $this->data->dir . '/ipn.php'; ?>
            </div>
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('admin/gateways'); ?>" class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-route="admin/gateways/action/" data-action="update" name="dosubmit" class="wojo primary button"><?php echo Language::$word->GW_UPDATE; ?></button>
      </div>
      <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
   </form>
   <?php break; ?><?php default: ?><?php if ($this->data): ?>
   <div class="wojo simple cards screen-3 tablet-3 mobile-1">
      <?php foreach ($this->data as $row): ?>
         <div class="card" id="item_<?php echo $row->id; ?>">
            <div class="content dimmable <?php echo $row->active == 0? 'active' : ''; ?>" id="cp_<?php echo $row->id; ?>">
               <a href="<?php echo Url::url($this->path, 'edit/' . $row->id); ?>">
                  <img src="<?php echo SITEURL . 'gateways/' . $row->dir; ?>/<?php echo $row->name; ?>_logo.svg" alt="">
               </a>
            </div>
            <div class="divided footer">
               <div class="row align-middle">
                  <div class="columns">
                     <a href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>"><?php echo $row->displayname; ?></a>
                  </div>
                  <div class="columns auto">
                     <div class="wojo fitted toggle checkbox is_dimmable" data-set='{"option":[{"action": "status","id":<?php echo $row->id; ?>}],"parent":"#cp_<?php echo $row->id; ?>", "url":"gateways/action/"}'>
                        <input name="active" type="checkbox" value="1" <?php echo Validator::getChecked($row->active, 1); ?> id="gateway_<?php echo $row->id; ?>">
                        <label for="gateway_<?php echo $row->id; ?>"><?php echo Language::$word->ACTIVE; ?></label>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      <?php endforeach; ?>
   </div>
<?php endif; ?>
   <?php break; ?>
<?php endswitch; ?>