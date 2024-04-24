<?php
   /**
    * getFreePlugins
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: getFreePlugins.tpl.php, v1.00 5/12/2023 2:33 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="body max-height400 scrollbox">
   <?php if ($this->data): ?>
      <div data-section="<?php echo $this->section; ?>" class="wojo divided list">
         <?php foreach ($this->data as $row): ?>
            <div class="item" data-id="<?php echo $row->id; ?>">
               <a><?php echo $row->title; ?></a>
            </div>
         <?php endforeach; ?>
      </div>
   <?php endif; ?>
</div>