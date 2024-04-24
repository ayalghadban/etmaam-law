<?php
   /**
    * user
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: user.tpl.php, v1.00 5/9/2023 8:49 PM Gewa Exp $
    *
    */

   use Wojo\Auth\Auth;
   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   if (!Auth::hasPrivileges('manage_users')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<?php switch (Url::segment($this->segments)): case 'edit': ?>
   <!-- Start edit -->
   <?php include '_user_edit.tpl.php'; ?>
   <?php break; ?>
   <!-- Start new -->
<?php case 'new': ?>
   <?php include '_user_new.tpl.php'; ?>
   <?php break; ?>
   <!-- Start history -->
<?php case 'history': ?>
   <?php include '_user_history.tpl.php'; ?>
   <?php break; ?>
   <!-- Start grid -->
<?php case 'grid': ?>
   <?php include '_user_grid.tpl.php'; ?>
   <?php break; ?>
   <!-- Start default -->
<?php default: ?>
   <?php include '_user_list.tpl.php'; ?>
   <?php break; ?>
<?php endswitch; ?>