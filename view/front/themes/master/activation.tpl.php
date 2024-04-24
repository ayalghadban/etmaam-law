<?php
   /**
    * activation
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: activation.tpl.php, v1.00 6/29/2023 10:47 PM Gewa Exp $
    *
    */

   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Url\Url;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<main>
   <div class="bg-color-primary-inverted padding-big-vertical">
      <div class="wojo-grid">
         <?php if (Validator::get('done')): ?>
            <?php echo Message::msgOk(Language::$word->M_INFO9 . '<a href="' . Url::url($this->core->system_slugs->login[0]->{'slug' . Language::$lang}) . '" class="white">' . Language::$word->M_INFO9_1 . '</a>'); ?>
         <?php else: ?>
            <?php echo Message::msgError(Language::$word->M_INFO10); ?>
         <?php endif; ?>
      </div>
   </div>
</main>