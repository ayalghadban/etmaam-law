<?php
   /**
    * _rows
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _rows.tpl.php, v1.00 5/8/2023 9:18 AM Gewa Exp $
    *
    */

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="wojo small block fields">
   <div class="field center-align">
      <label>Align Items</label>
      <div class="wojo mini primary inverted buttons" id="rows_align">
         <a data-class="align-top" class="wojo icon mini button">
            <i class="icon align top"></i>
         </a>
         <a data-class="align-middle" class="wojo icon mini button">
            <i class="icon align middle"></i>
         </a>
         <a data-class="align-bottom" class="wojo icon mini button">
            <i class="icon align bottom"></i>
         </a>
      </div>
   </div>
   <div class="field center-align">
      <label>Justify Content</label>
      <div class="wojo mini primary inverted buttons" id="rows_justify">
         <a data-class="justify-start" class="wojo icon mini button">
            <i class="icon align start"></i>
         </a>
         <a data-class="justify-center" class="wojo icon mini button">
            <i class="icon align middle"></i>
         </a>
         <a data-class="justify-end" class="wojo icon mini button">
            <i class="icon align start flipped"></i>
         </a>
         <a data-class="justify-between" class="wojo icon mini button">
            <i class="icon align between"></i>
         </a>
         <a data-class="justify-around" class="wojo icon mini button">
            <i class="icon align around"></i>
         </a>
      </div>
   </div>
   <div class="field basic">
      <label>Row Gutters</label>
      <div class="wojo vertical buttons" id="rows_gutters">
         <div class="wojo wojo mini fluid buttons">
            <a data-class="none" class="wojo mini icon primary inverted button">
               <i class="icon dash"></i>
            </a>
            <a data-class="mini-gutters" class="wojo icon mini primary inverted button">8px</a>
            <a data-class="small-gutters" class="wojo icon mini primary inverted button">16px</a>
            <a data-class="gutters" class="wojo mini primary inverted  button">32px</a>
         </div>
         <div class="wojo wojo mini fluid buttons">
            <a data-class="medium-gutters" class="wojo mini primary inverted button">48px</a>
            <a data-class="large-gutters" class="wojo mini primary inverted button">64px</a>
            <a data-class="big-gutters" class="wojo mini primary inverted button">80px</a>
            <a data-class="huge-gutters" class="wojo mini primary inverted button">96px</a>
         </div>
      </div>
   </div>
</div>