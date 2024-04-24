<?php
   /**
    * mod_index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: mod_index.tpl.php, v1.00 6/13/2023 9:34 AM Gewa Exp $
    *
    */

   use Wojo\Core\Module;
   use Wojo\File\File;
   use Wojo\Language\Language;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<main>
   <!-- Module Caption & breadcrumbs-->
   <?php if (File::is_File(FMODPATH . $this->core->moddir[$this->segments[0]] . '/themes/' . $this->core->theme . '/snippets/header.tpl.php')): ?>
      <?php include(FMODPATH . $this->core->moddir[$this->segments[0]] . '/themes/' . $this->core->theme . '/snippets/header.tpl.php'); ?>
   <?php else: ?>
      <div id="moduleCaption" class="<?php echo $this->core->moddir[$this->segments[0]]; ?>">
         <div class="wojo-grid relative">
            <div class="row gutters justify-center">
               <div class="columns screen-60 tablet-80 mobile-100 phone-100 center-align">
                  <?php if (isset($this->data->title)): ?>
                     <h1><?php echo $this->data->title; ?></h1>
                  <?php endif; ?>
                  <?php if (isset($this->data->{'info' . Language::$lang})): ?>
                     <p><?php echo $this->data->{'info' . Language::$lang}; ?></p>
                  <?php endif; ?>
               </div>
               <?php if ($this->core->showcrumbs): ?>
                  <div class="columns screen-100 tablet-100 mobile-100 phone-100 center-align align-self-bottom">
                     <div class="wojo breadcrumb">
                        <?php echo Url::crumbs($this->crumbs?: $this->segments, '/', Language::$word->HOME); ?>
                     </div>
                  </div>
               <?php endif; ?>
            </div>
            <div class="shape1">
               <img src="<?php echo THEMEURL; ?>images/shape-1-soft-light.svg" alt="Shape1">
            </div>
            <div class="shape2">
               <img src="<?php echo THEMEURL; ?>images/shape-7-soft-light.svg" alt="Shape2">
            </div>
         </div>
      </div>
   <?php endif; ?>

   <?php if ($this->layout->topWidget): ?>
      <!-- Top Widgets -->
      <div id="topwidget">
         <?php include THEMEBASE . 'top_widget.tpl.php'; ?>
      </div>
      <!-- Top Widgets /-->
   <?php endif; ?>
   <?php switch (true): case $this->layout->leftWidget and $this->layout->rightWidget: ?>
      <!-- Left and Right Layout -->
      <div class="wojo-grid">
         <div class="row horizontal-gutters">
            <div class="columns screen-20 tablet-25 mobile-100 phone-100">
               <?php include THEMEBASE . 'left_widget.tpl.php'; ?>
            </div>
            <div class="columns screen-60 tablet-50 mobile-100 phone-100">
               <?php include_once Module::render($this->segments[0], $this->core); ?>
            </div>
            <div class="columns screen-20 tablet-25 mobile-100 phone-100">
               <?php include THEMEBASE . 'right_widget.tpl.php'; ?>
            </div>
         </div>
      </div>
      <!-- Left and Right Layout /-->
      <?php break; ?>
   <?php case $this->layout->leftWidget: ?>
      <!-- Left Layout -->
      <div class="wojo-grid">
         <div class="row large-horizontal-gutters">
            <div class="columns screen-30 tablet-40 mobile-100 phone-100">
               <?php include THEMEBASE . 'left_widget.tpl.php'; ?>
            </div>
            <div class="columns screen-70 tablet-60 mobile-100 phone-100">
               <?php include_once Module::render($this->segments[0], $this->core); ?>
            </div>
         </div>
      </div>
      <!-- Left Layout /-->
      <?php break; ?>
   <?php case $this->layout->rightWidget: ?>
      <!-- Right Layout -->
      <div class="wojo-grid">
         <div class="row large-horizontal-gutters">
            <div class="columns screen-70 tablet-60 mobile-100 phone-100">
               <?php include_once Module::render($this->segments[0], $this->core); ?>
            </div>
            <div class="columns screen-30 tablet-40 mobile-100 phone-100">
               <?php include THEMEBASE . 'right_widget.tpl.php'; ?>
            </div>
         </div>
      </div>
      <!-- Right Layout /-->
      <?php break; ?>
   <?php default: ?>
      <!-- Full Layout -->
      <div class="wojo-grid">
         <?php include_once Module::render($this->segments[0], $this->core); ?>
      </div>
      <!-- Full Layout /-->
      <?php break; ?>
   <?php endswitch; ?>
   <?php if ($this->layout->bottomWidget): ?>
      <!-- Bottom Widgets -->
      <div id="bottomwidget">
         <?php include THEMEBASE . 'bottom_widget.tpl.php'; ?>
      </div>
      <!-- Bottom Widgets /-->
   <?php endif; ?>
</main>