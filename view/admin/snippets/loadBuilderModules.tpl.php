<?php
   /**
    * loadBuilderModules
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: loadBuilderModules.tpl.php, v1.00 6/9/2023 1:14 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<?php if ($this->data): ?>
   <?php foreach ($this->data as $row): ?>
      <div class="columns">
         <a wf-type="module" wf-label="Module" data-element="modules" data-mode="readonly" data-module-id="<?php echo $row->parent_id; ?>" data-module-module_id="<?php echo $row->id; ?>" data-module-name="<?php echo $row->title; ?>" data-module-alias="<?php echo $row->modalias; ?>" data-module-group="<?php echo $row->modalias; ?>">
            <img src="<?php echo AMODULEURL . $row->icon; ?>" alt="">
         </a>
         <p class="truncate margin-mini-top center-align">
            <span class="text-size-mini"><?php echo $row->title; ?></span>
         </p>
      </div>
   <?php endforeach; ?>
<?php endif; ?>