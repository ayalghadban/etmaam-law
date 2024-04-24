<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 10/20/2023 2:26 PM Gewa Exp $
    *
    */

   use Wojo\Language\Language;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<?php switch (count($this->segments)): case 3: ?>
   <?php if (in_array($this->core->modname['blog-archive'], $this->segments)): ?>
      <div class="padding-big-vertical">
         <?php include_once BASEPATH . 'view/front/modules/blog/snippets/_layout_archive.tpl.php'; ?>
      </div>
   <?php elseif (in_array($this->core->modname['blog-tag'], $this->segments)): ?>
      <div class="padding-big-vertical">
         <?php include_once BASEPATH . 'view/front/modules/blog/snippets/_layout_tag.tpl.php'; ?>
      </div>
   <?php else: ?>
      <div class="padding-big-vertical">
         <?php switch ($this->row->layout): case 4: ?>
            <?php include_once BASEPATH . 'view/front/modules/blog/snippets/_front_layout_list.tpl.php'; ?>
            <?php break; ?>
         <?php case 3: ?>
            <?php include_once BASEPATH . 'view/front/modules/blog/snippets/_front_layout_modern.tpl.php'; ?>
            <?php break; ?>
         <?php case 2: ?>
            <?php include_once BASEPATH . 'view/front/modules/blog/snippets/_front_layout_masonry.tpl.php'; ?>
            <?php break; ?>
         <?php default: ?>
            <?php include_once BASEPATH . 'view/front/modules/blog/snippets/_front_layout_classic.tpl.php'; ?>
            <?php break; ?>
         <?php endswitch; ?>
      </div>
   <?php endif; ?>
   <?php break; ?>

   <!-- Start Blog single -->
<?php case 2: ?>
   <div class="padding-big-vertical">
      <?php switch ($this->row->layout): case 4: ?>
         <?php include_once BASEPATH . 'view/front/modules/blog/snippets/_layout_single_bottom.tpl.php'; ?>
         <?php break; ?>
      <?php case 3: ?>
         <?php include_once BASEPATH . 'view/front/modules/blog/snippets/_layout_single_top.tpl.php'; ?>
         <?php break; ?>
      <?php case 2: ?>
         <?php include_once BASEPATH . 'view/front/modules/blog/snippets/_layout_single_right.tpl.php'; ?>
         <?php break; ?>
      <?php default: ?>
         <?php include_once BASEPATH . 'view/front/modules/blog/snippets/_layout_single_left.tpl.php'; ?>
         <?php break; ?>
      <?php endswitch; ?>
   </div>
   <?php break; ?>
   <!-- Start Blog default -->
<?php default: ?>
   <div class="padding-big-vertical">
      <h5 class="margin-bottom"><?php echo Language::$word->_MOD_AM_SUB43; ?></h5>
      <?php switch ($this->settings->flayout): case 4: ?>
         <?php include_once BASEPATH . 'view/front/modules/blog/snippets/_front_layout_list.tpl.php'; ?>
         <?php break; ?>
      <?php case 3: ?>
         <?php include_once BASEPATH . 'view/front/modules/blog/snippets/_front_layout_modern.tpl.php'; ?>
         <?php break; ?>
      <?php case 2: ?>
         <?php include_once BASEPATH . 'view/front/modules/blog/snippets/_front_layout_masonry.tpl.php'; ?>
         <?php break; ?>
      <?php default: ?>
         <?php include_once BASEPATH . 'view/front/modules/blog/snippets/_front_layout_classic.tpl.php'; ?>
         <?php break; ?>
      <?php endswitch; ?>
   </div>
   <?php break; ?>
<?php endswitch; ?>

<?php //Debug::pre();
