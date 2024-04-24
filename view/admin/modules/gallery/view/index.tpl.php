<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 5/12/2023 8:37 PM Gewa Exp $
    *
    */

   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   if (!$this->auth->checkModAcl('gallery')): print Message::msgError(Language::$word->NOACCESS);
      return; endif;
?>
<?php switch (Url::segment($this->segments, 3)): case 'edit': ?>
   <!-- Start edit -->
   <?php include('_edit.tpl.php'); ?>
   <?php break; ?>
<?php case 'new': ?>
   <!-- Start new -->
   <?php include('_new.tpl.php'); ?>
   <?php break; ?>
<?php case 'photos': ?>
   <!-- Start photos -->
   <?php include('_photos.tpl.php'); ?>
   <?php break; ?>
<?php default: ?>
   <!-- Start default -->
   <?php include('_grid.tpl.php'); ?>
   <?php break; ?>
<?php endswitch; ?>
<script src="<?php echo SITEURL; ?>assets/sortable.js"></script>
<script src="<?php echo AMODULEURL; ?>gallery/view/js/gallery.js"></script>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $.Gallery({
         url: "<?php echo ADMINURL . 'modules/gallery/action/';?>",
         sortable: ".wojo.sortable",
         dir: "<?php echo in_array('photos', $this->segments)? $this->data->dir : null;?>",
         lang: {
            done: "<?php echo Language::$word->DONE;?>"
         }
      });

   });
   // ]]>
</script>