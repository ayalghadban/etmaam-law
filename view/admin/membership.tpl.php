<?php
   /**
    * membership
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: membership.tpl.php, v1.00 5/10/2023 4:06 PM Gewa Exp $
    *
    */

   use Wojo\Auth\Auth;
   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   if (!Auth::hasPrivileges('manage_memberships')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<?php switch (Url::segment($this->segments)): case 'edit': ?>
   <!-- Start edit -->
   <?php include '_memberships_edit.tpl.php'; ?>
   <?php break; ?>
   <!-- Start new -->
<?php case 'new': ?>
   <?php include '_memberships_new.tpl.php'; ?>
   <?php break; ?>
   <!-- Start history -->
<?php case 'history': ?>
   <?php include '_memberships_history.tpl.php'; ?>
   <?php break; ?>
   <!-- Start default -->
<?php default: ?>
   <?php include '_memberships_grid.tpl.php'; ?>
   <?php break; ?>
<?php endswitch; ?>