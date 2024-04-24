<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 5/18/2023 10:28 AM Gewa Exp $
    *
    */

   use Wojo\Language\Language;
   use Wojo\Message\Message;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   if (!$this->auth->checkPlugAcl('newsletter')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<div class="row gutters justify-end">
   <div class="columns auto mobile-100 phone-100">
      <a href="<?php echo ADMINURL . 'plugins/newsletter/action/?action=export'; ?>"
         class="wojo small secondary button">
         <i class="icon wysiwyg table"></i><?php echo Language::$word->EXPORT; ?></a>
   </div>
</div>
<div class="center-align">
   <p class="wojo small icon info inverted compact message">
      <i class="icon information square"></i><?php echo str_replace('[TOTAL]', $this->data, Language::$word->_PLG_NSL_INFO); ?>
   </p>
</div>