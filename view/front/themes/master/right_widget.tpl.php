<?php
   /**
    * right_widget
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: right_widget.tpl.php, v1.00 6/13/2023 10:36 AM Gewa Exp $
    *
    */

   use Wojo\Core\Plugin;
   use Wojo\Url\Url;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<?php if ($this->layout->rightCount): ?>
   <aside class="rightwidget margin-big-vertical">
      <?php foreach ($this->layout->rightWidget as $row): ?>
         <div class="rightwidget-wrap <?php echo ($row->alt_class)?: ''; ?>">
            <?php if ($row->show_title): ?>
               <h5 class="wojo header"><?php echo $row->title; ?></h5>
            <?php endif; ?>
            <?php if ($row->body): ?>
               <div class="rightwidget-body"><?php echo Url::out_url($row->body); ?></div>
            <?php endif; ?>
            <?php if ($row->jscode): ?>
               <script>
                  <?php echo Validator::cleanOut($row->jscode);?>
               </script>
            <?php endif; ?>
            <?php if ($row->system): ?>
               <?php echo Plugin::loadPluginFile(array($row->plugalias, $row->plugin_id, $row->plug_id, $this->plugins)); ?>
            <?php endif; ?>
         </div>
      <?php endforeach; ?>
      <?php unset($row); ?>
   </aside>
<?php endif; ?>