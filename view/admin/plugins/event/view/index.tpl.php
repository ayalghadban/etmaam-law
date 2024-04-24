<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 5/16/2023 8:54 AM Gewa Exp $
    *
    */

   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   if (!$this->auth->checkPlugAcl('event')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<form method="post" id="wojo_form" name="wojo_form">
   <div class="row justify-center">
      <div class="columns screen-50 tablet-100 mobile-100 phone-100">
         <div class="wojo form simple segment margin-bottom">
            <div class="wojo block fields">
               <div class="field">
                  <label><?php echo Language::$word->_PLG_UE_SELECT; ?></label>
                  <?php if ($this->data): ?>
                     <a data-wdropdown="#event_id"
                        class="wojo secondary right fluid button"><?php echo Language::$word->SELECT; ?>
                        <i class="icon chevron down"></i>
                     </a>
                     <div class="wojo static dropdown small pointing top-left" id="event_id">
                        <div class="row grid phone-1 mobile-1 tablet-2 screen-2">
                           <?php echo Utility::loopOptionsMultiple($this->data, 'id', 'title' . Language::$lang, $this->row, 'event_id'); ?>
                        </div>
                     </div>
                  <?php else: ?>
                     <?php echo Language::$word->_PLG_UE_NOEVENT; ?>
                  <?php endif; ?>
               </div>
            </div>
         </div>
         <div class="center-align">
            <a href="<?php echo Url::url('admin/plugins'); ?>"
               class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
            <button type="button" data-route="admin/plugins/event/action/" data-action="update" name="dosubmit"
                    class="wojo primary button"><?php echo Language::$word->SAVECONFIG; ?></button>
         </div>
      </div>
   </div>
</form>