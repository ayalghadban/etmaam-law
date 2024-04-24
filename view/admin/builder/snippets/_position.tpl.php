<?php
   /**
    * _position
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _position.tpl.php, v1.00 5/8/2023 9:18 AM Gewa Exp $
    *
    */

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="wojo small block fields">
   <div class="field">
      <label>Position</label>
      <div class="wojo vertical buttons" id="position_type">
         <div class="wojo mini primary inverted buttons">
            <a data-class="static" class="wojo mini button">
               Static
            </a>
            <a data-class="relative" class="wojo mini button">
               Relative
            </a>
         </div>
         <div class="wojo mini primary inverted buttons">
            <a data-class="absolute" class="wojo mini button">
               Absolute
            </a>
            <a data-class="fixed" class="wojo mini button">
               Fixed
            </a>
         </div>
      </div>
   </div>
</div>
<div id="p_index" class="hide-all">
   <div class="wojo small block fields">
      <div class="field" id="position_index">
         <label>z-Index</label>
         <div class="wojo vertical buttons">
            <div class="wojo mini primary inverted buttons">
               <a data-class="none" class="wojo mini button">
                  -
               </a>
               <a data-class="zindex-1" class="wojo mini button">
                  -1
               </a>
               <a data-class="zindex0" class="wojo mini button">
                  0
               </a>
               <a data-class="zindex1" class="wojo mini button">
                  1
               </a>
            </div>
            <div class="wojo mini primary inverted buttons">
               <a data-class="zindex2" class="wojo mini button">
                  2
               </a>
               <a data-class="zindex3" class="wojo mini button">
                  3
               </a>
               <a data-class="zindex4" class="wojo mini button">
                  4
               </a>
               <a data-class="zindex5" class="wojo mini button">
                  5
               </a>
            </div>
         </div>
      </div>
   </div>
</div>
<div id="p_place" class="hide-all">
   <div class="wojo small block fields">
      <div class="field basic" id="position_place">
         <label>Position Placement</label>
         <div class="wojo vertical buttons">
            <div class="wojo mini buttons" id="position_place_top">
               <a data-class="top" class="wojo icon mini secondary inverted passive button start">
                  <i class="icon wysiwyg border top"></i>
               </a>
               <a data-class="top-none" class="wojo mini primary inverted icon button auto">
                  <i class="icon dash"></i>
               </a>
               <a data-class="top-zero" class="wojo mini primary inverted icon button auto">
                  0
               </a>
               <a data-class="top-50" class="wojo mini primary inverted icon button  auto">
                  50%
               </a>
               <a data-class="top-100" class="wojo mini primary inverted icon button  auto">
                  100%
               </a>
            </div>
            <div class="wojo mini buttons" id="position_place_bottom">
               <a data-class="bottom" class="wojo icon mini secondary inverted passive button start">
                  <i class="icon wysiwyg border bottom"></i>
               </a>
               <a data-class="bottom-none" class="wojo mini primary inverted icon button auto">
                  <i class="icon dash"></i>
               </a>
               <a data-class="bottom-zero" class="wojo mini primary inverted icon button auto">
                  0
               </a>
               <a data-class="bottom-50" class="wojo mini primary inverted icon button  auto">
                  50%
               </a>
               <a data-class="bottom-100" class="wojo mini primary inverted icon button  auto">
                  100%
               </a>
            </div>
            <div class="wojo mini buttons" id="position_place_left">
               <a data-class="left" class="wojo icon mini secondary inverted passive button start">
                  <i class="icon wysiwyg border left"></i>
               </a>
               <a data-class="left-none" class="wojo mini primary inverted icon button auto">
                  <i class="icon dash"></i>
               </a>
               <a data-class="left-zero" class="wojo mini primary inverted icon button auto">
                  0
               </a>
               <a data-class="left-50" class="wojo mini primary inverted icon button  auto">
                  50%
               </a>
               <a data-class="left-100" class="wojo mini primary inverted icon button  auto">
                  100%
               </a>
            </div>
            <div class="wojo mini buttons" id="position_place_right">
               <a data-class="right" class="wojo icon mini secondary inverted passive button start">
                  <i class="icon wysiwyg border right"></i>
               </a>
               <a data-class="right-none" class="wojo mini primary inverted icon button auto">
                  <i class="icon dash"></i>
               </a>
               <a data-class="right-zero" class="wojo mini primary inverted icon button auto">
                  0
               </a>
               <a data-class="right-50" class="wojo mini primary inverted icon button  auto">
                  50%
               </a>
               <a data-class="right-100" class="wojo mini primary inverted icon button  auto">
                  100%
               </a>
            </div>
         </div>
      </div>
   </div>
</div>