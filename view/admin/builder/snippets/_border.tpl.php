<?php
   /**
    * _border
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _border.tpl.php, v1.00 5/8/2023 9:18 AM Gewa Exp $
    *
    */

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="wojo small block fields">
   <div class="field center-align">
      <label>Border Type</label>
      <div class="wojo mini primary inverted buttons" id="border_type">
         <a data-class="none" class="wojo icon mini button">
            <i class="icon dash"></i>
         </a>
         <a data-class="border-full" class="wojo icon mini button">
            <i class="icon wysiwyg border outer"></i>
         </a>
         <a data-class="border-top" class="wojo icon mini button">
            <i class="icon wysiwyg border top"></i>
         </a>
         <a data-class="border-bottom" class="wojo icon mini button">
            <i class="icon wysiwyg border bottom"></i>
         </a>
         <a data-class="border-left" class="wojo icon mini button">
            <i class="icon wysiwyg border left"></i>
         </a>
         <a data-class="border-right" class="wojo icon mini button">
            <i class="icon wysiwyg border right"></i>
         </a>
      </div>
   </div>
   <div class="field center-align">
      <label>Border Size</label>
      <div class="wojo mini fluid buttons" id="border_size">
         <a data-class="border-1" class="wojo mini primary inverted button">
            1px
         </a>
         <a data-class="border-2" class="wojo mini primary inverted button">
            2px
         </a>
         <a data-class="border-3" class="wojo mini primary inverted button">
            3px
         </a>
         <a data-class="border-4" class="wojo mini primary inverted button">
            4px
         </a>
         <a data-class="border-5" class="wojo mini primary inverted button">
            5px
         </a>
      </div>
   </div>
   <div class="field center-align">
      <label>Border Radius</label>
      <div class="wojo mini primary inverted buttons" id="border_radius">
         <a data-class="none" class="wojo icon mini button">
            <i class="icon dash"></i>
         </a>
         <a data-class="rounded-full" class="wojo icon mini button">
            <i class="icon wysiwyg border outer"></i>
         </a>
         <a data-class="rounded-top" class="wojo icon mini button">
            <i class="icon wysiwyg border top"></i>
         </a>
         <a data-class="rounded-bottom" class="wojo icon mini button">
            <i class="icon wysiwyg border bottom"></i>
         </a>
         <a data-class="rounded-left" class="wojo icon mini button">
            <i class="icon wysiwyg border left"></i>
         </a>
         <a data-class="rounded-right" class="wojo icon mini button">
            <i class="icon wysiwyg border right"></i>
         </a>
      </div>
   </div>
   <div class="field basic center-align">
      <label>Border Color</label>
      <div class="wojo vertical buttons" id="border_color">
         <?php include '_color.tpl.php'; ?>
      </div>
   </div>
</div>