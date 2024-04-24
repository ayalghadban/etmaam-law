<?php
   /**
    * element_helper
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: element_helper.tpl.php, v1.00 5/8/2023 9:18 AM Gewa Exp $
    *
    */

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div id="element-helper" class="wojo tiny form">
   <div class="wojo accordion" data-waccordion='{"closeOther": true}'>
      <section data-helper="basic" class="is_text is_link is_icon is_label is_image is_frame">
         <h6 class="summary">
            <a>Basic</a>
         </h6>
         <div class="details">
            <?php include_once '_basic.tpl.php'; ?>
         </div>
      </section>
      <section data-helper="margins" class="is_section is_container is_text">
         <h6 class="summary">
            <a>Margins</a>
         </h6>
         <div class="details">
            <?php include_once '_margins.tpl.php'; ?>
         </div>
      </section>
      <section data-helper="paddings" class="is_section is_container is_text">
         <h6 class="summary">
            <a>Paddings</a>
         </h6>
         <div class="details"> <?php include_once '_paddings.tpl.php'; ?></div>
      </section>
      <section data-helper="text" class="is_text">
         <h6 class="summary">
            <a>Text Styles</a>
         </h6>
         <div class="details">
            <?php include_once '_text.tpl.php'; ?>
         </div>
      </section>
      <section data-helper="rows" class="is_rows">
         <h6 class="summary">
            <a>Rows</a>
         </h6>
         <div class="details">
            <?php include_once '_rows.tpl.php'; ?>
         </div>
      </section>
      <section data-helper="columns" class="is_columns">
         <h6 class="summary">
            <a>Columns</a>
         </h6>
         <div class="details"> <?php include_once '_columns.tpl.php'; ?></div>
      </section>
      <section data-helper="display" class="is_container is_text">
         <h6 class="summary">
            <a>Display</a>
         </h6>
         <div class="details"> <?php include_once '_display.tpl.php'; ?></div>
      </section>
      <section data-helper="position" class="is_section is_rows is_container is_text">
         <h6 class="summary">
            <a>Position</a>
         </h6>
         <div class="details"> <?php include_once '_position.tpl.php'; ?></div>
      </section>
      <section data-helper="border" class="is_container">
         <h6 class="summary">
            <a>Border</a>
         </h6>
         <div class="details"> <?php include_once '_border.tpl.php'; ?></div>
      </section>
      <section data-helper="visibility" class="is_rows is_columns is_container is_text">
         <h6 class="summary">
            <a>Visibility</a>
         </h6>
         <div class="details">
            <?php include_once '_visibility.tpl.php'; ?>
         </div>
      </section>
      <section data-helper="background" class="is_section is_container">
         <h6 class="summary">
            <a>Background</a>
         </h6>
         <div class="details"> <?php include_once '_background.tpl.php'; ?></div>
      </section>
      <section data-helper="advanced" class="is_section is_rows is_columns is_container">
         <h6 class="summary">
            <a>Advanced</a>
         </h6>
         <div class="details">
            <div class="wojo small block fields">
               <div class="field">
                  <label>Classes</label>
                  <div id="advanced_classes"></div>
               </div>
               <div class="field">
                  <label>ID</label>
                  <div id="advanced_id"></div>
               </div>
               <div class="field center-align" id="advanced_attributes">
                  <label>Attributes </label>
                  <div class="wojo mini primary inverted buttons" id="attributes_name">
                     <a data-value="class" class="wojo mini button active"> Class</a>
                     <a data-value="id" class="wojo mini button"> ID</a>
                  </div>
                  <div class="wojo mini action input margin-mini-top">
                     <input type="text" name="attributes_value" placeholder="Value">
                     <a class="wojo mini primary inverted icon button" id="addAttribute">
                        <i class="icon plus"></i>
                     </a>
                  </div>
               </div>
               <div class="field" id="advanced_html">
                  <label>Code</label>
                  <a class="wojo mini fluid primary button disabled">HTML Mode</a>
                  <div class="text-size-mini text-color-negative">Be careful editing html code, you might end up with broken html if not careful.
                  </div>
               </div>
            </div>
         </div>
      </section>
   </div>
</div>