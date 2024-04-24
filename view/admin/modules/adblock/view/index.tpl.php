<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 5/19/2023 10:14 AM Gewa Exp $
    *
    */
   
   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Url\Url;
   
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   if (!$this->auth->checkModAcl('adblock')): print Message::msgError(Language::$word->NOACCESS);
      return; endif;
?>
<?php switch (Url::segment($this->segments, 3)): case 'edit': ?>
   <!-- Start edit -->
   <?php include('_adblock_edit.tpl.php'); ?>
   <?php break; ?>
<?php case 'new': ?>
   <!-- Start new -->
   <?php include('_adblock_new.tpl.php'); ?>
   <?php break; ?>
<?php default: ?>
   <!-- Start default -->
   <?php include('_adblock_grid.tpl.php'); ?>
   <?php break; ?>
<?php endswitch; ?>