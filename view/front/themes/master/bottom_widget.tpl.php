<?php
   /**
    * bottom_widget
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: bottom_widget.tpl.php, v1.00 6/13/2023 10:37 AM Gewa Exp $
    *
    */

   use Wojo\Core\Plugin;
   use Wojo\Url\Url;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<?php if ($this->layout->bottomCount): ?>
   <div class="bottomwidget">
      <?php if ($this->layout->bottomCount > 1 and $this->layout->bcounter == 0): ?>
      <div class="wojo-grid">
         <div class="row gutters">
            <?php endif; ?>
            <?php foreach ($this->layout->bottomWidget as $row): ?>
               <?php if ($this->layout->bottomCount > 1 and $this->layout->bcounter): ?>
                  <div class="row">
               <?php endif; ?>
               <div class="columns screen-<?php echo $row->space; ?>0 tablet-<?php echo $row->space; ?>0 mobile-100 phone-100">
                  <div class="bottomwidget-wrap <?php echo ($row->alt_class)?: ''; ?>">
                     <?php if ($row->show_title and !$row->system): ?>
                        <h4 class="wojo header"><?php echo $row->title; ?></h4>
                     <?php endif; ?>
                     <?php if ($row->body and !$row->system): ?>
                        <div class="bottomwidget-body"><?php echo Url::out_url($row->body); ?></div>
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
               </div>
               <?php if ($this->layout->bottomCount > 1 and $this->layout->bcounter): ?>
                  </div>
               <?php endif; ?>
            <?php endforeach; ?>
            <?php unset($row); ?>
            <?php if ($this->layout->bottomCount > 1 and $this->layout->bcounter == 0): ?>
         </div>
      </div>
   <?php endif; ?>
   </div>
<?php endif; ?>