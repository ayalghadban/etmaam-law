<?php
   /**
    * page
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: page.tpl.php, v1.00 5/7/2023 8:56 AM Gewa Exp $
    *
    */

   use Wojo\Auth\Auth;
   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   if (!Auth::hasPrivileges('manage_pages')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<?php switch (Url::segment($this->segments)): case 'edit': ?>
   <!-- Start edit -->
   <?php include '_page_edit.tpl.php'; ?>
   <?php break; ?>
   <!-- Start new -->
<?php case 'new': ?>
   <?php include '_page_new.tpl.php'; ?>
   <?php break; ?>
   <!-- Start default -->
<?php default: ?>
   <?php include '_page_list.tpl.php'; ?>
   <?php break; ?>
<?php endswitch; ?>