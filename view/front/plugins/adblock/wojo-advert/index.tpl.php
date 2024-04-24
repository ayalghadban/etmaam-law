<?php
   /**
    * index.tpl.php
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 6/14/2023 11:52 AM Gewa Exp $
    *
    */

   use Wojo\Module\Adblock\Adblock;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;
   use Wojo\Validator\Validator;
   
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<!-- Ad Block -->
<?php if ($conf = Utility::findInArray($this->properties['all'], 'id', $this->properties['id'])): ?>
   <?php $href = ''; ?>
   <div class="wojo compact margin-bottom segment<?php echo ($conf[0]->alt_class)? ' ' . $conf[0]->alt_class : null; ?>">
      <?php if ($conf[0]->show_title): ?>
         <h3><?php echo $conf[0]->title; ?></h3>
      <?php endif; ?>
      <?php if ($conf[0]->body): ?>
         <?php echo Url::out_url($conf[0]->body); ?>
      <?php endif; ?>
      <?php if ($row = Adblock::render($this->properties['plugin_id'])): ?>
         <?php if (Adblock::isOnline($row)): ?>
            <?php $href = (str_starts_with($row->image_link, 'http'))? $row->image_link : 'https://' . $row->image_link; ?>
            <?php $ad_content = ($row->image)? ('<a href="' . $href . '" id="b_' . $row->id . '" title="' . $row->image_alt . '"><img src="' . FPLUGINURL . $row->plugin_id . '/' . $row->image . '" alt="' . $row->image_alt . '" class="wojo rounded image" /></a>') : Validator::cleanOut($row->banner_html); ?>
            <?php echo $ad_content; ?>
            <?php Adblock::updateView($row->id); ?>
         <?php endif; ?>
      <?php endif; ?>
   </div>
   <?php if ($conf[0]->jscode): ?>
      <script><?php echo $conf[0]->jscode; ?></script>
   <?php endif; ?>
   <script type="text/javascript">
      // <![CDATA[
      $(document).ready(function () {
         $("#b_<?php echo $row->id;?>").on('click', function () {
            $.post("<?php echo SITEURL . 'adblock/action/';?>", {
               action: 'update',
               id: "<?php echo $row->id;?>"
            });
            window.location.href = "<?php echo $href;?>";
         });
      });
      // ]]>
   </script>
<?php endif; ?>