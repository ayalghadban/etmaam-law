<?php
   /**
    * language
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: language.tpl.php, v1.00 5/8/2023 9:15 AM Gewa Exp $
    *
    */

   use Wojo\Auth\Auth;
   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   if (!Auth::hasPrivileges('manage_languages')): print Message::msgError(Language::$word->NOACCESS);
      return; endif;
?>
<?php switch (Url::segment($this->segments)): case 'edit': ?>
   <!-- Start edit -->
   <?php include '_language_edit.tpl.php'; ?>
   <?php break; ?>
   <!-- Start new -->
<?php case 'new': ?>
   <?php include '_language_new.tpl.php'; ?>
   <?php break; ?>
   <!-- Start translate -->
<?php case 'translate': ?>
   <?php include '_language_translate.tpl.php'; ?>
   <?php break; ?>
   <!-- Start default -->
<?php default: ?>
   <?php include '_language_grid.tpl.php'; ?>
   <?php break; ?>
<?php endswitch; ?>
<script src="<?php echo ADMINVIEW . 'js/language.js'; ?>"></script>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $.Language({
         url: "<?php echo ADMINURL;?>",
      });
   });
   // ]]>
</script>