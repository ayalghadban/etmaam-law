<?php
   /**
    * _image
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _image.tpl.php, v1.00 5/8/2023 9:18 AM Gewa Exp $
    *
    */

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div id="b_image" class="hide-all">
   <div class="wojo small block fields">
      <div class="field">
         <label>Title</label>
         <input id="basic_image_alt" type="text">
      </div>
      <div class="field">
         <label>Image Style</label>
         <div data-type="style" class="wojo wojo mini fluid buttons" id="image_style">
            <a data-class="default" class="wojo mini primary inverted button">-</a>
            <a data-class="rounded" class="wojo mini primary inverted button">round</a>
            <a data-class="circular" class="wojo mini primary inverted button">circle</a>
         </div>
      </div>
      <div class="field">
         <label>Image</label>
         <div class="row mini-gutters align-middle">
            <div class="columns auto">
               <div id="imageHolder"></div>
            </div>
            <div class="columns right-align">
               <a class="wojo mini primary inverted icon button" id="basic_image">
                  <i class="icon folder"></i>
               </a>
            </div>
         </div>
      </div>
   </div>
</div>