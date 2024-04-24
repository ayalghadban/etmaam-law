<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 5/14/2023 8:31 PM Gewa Exp $
    *
    */

   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   if (!$this->auth->checkModAcl('faq')): print Message::msgError(Language::$word->NOACCESS);
      return; endif;
?>
<?php switch (Url::segment($this->segments, 3)): case 'category': ?>
   <!-- Start category edit -->
   <?php include('_faq_category_edit.tpl.php'); ?>
   <?php break; ?>
<?php case 'categories': ?>
   <!-- Start category new -->
   <?php include('_faq_category_new.tpl.php'); ?>
   <?php break; ?>
<?php case 'edit': ?>
   <!-- Start edit -->
   <?php include('_faq_edit.tpl.php'); ?>
   <?php break; ?>
<?php case 'new': ?>
   <!-- Start new -->
   <?php include('_faq_new.tpl.php'); ?>
   <?php break; ?>
<?php default: ?>
   <!-- Start default -->
   <?php include('_faq_list.tpl.php'); ?>
   <?php break; ?>
<?php endswitch; ?>
<script src="<?php echo SITEURL; ?>assets/sortable.js"></script>
<script src="<?php echo AMODULEURL; ?>faq/view/js/faq.js"></script>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $.Faq({
         url: "<?php echo ADMINURL . 'modules/faq/action/';?>",
         lang: {
            delMsg3: "<?php echo Language::$word->DELCONFIRM1;?>",
            delMsg8: "<?php echo Language::$word->DELCONFIRM2;?>",
            canBtn: "<?php echo Language::$word->CANCEL;?>",
            trsBtn: "<?php echo Language::$word->DELETE_REC;?>",
            err: "<?php echo Language::$word->ERROR;?>",
            err1: "<?php echo Language::$word->FU_ERROR7;?>",
         }
      });
   });
   // ]]>
</script> 