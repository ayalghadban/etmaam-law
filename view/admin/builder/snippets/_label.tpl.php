<?php
   /**
    * _label
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _label.tpl.php, v1.00 5/8/2023 9:18 AM Gewa Exp $
    *
    */

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div id="b_label" class="hide-all">
   <div class="wojo small block fields">
      <div class="field">
         <label>Label Size</label>
         <div data-type="size" class="wojo mini fluid buttons" id="label_size">
            <a data-class="mini" class="wojo mini primary inverted button">xs</a>
            <a data-class="small" class="wojo mini primary inverted button">sm</a>
            <a data-class="default" class="wojo mini primary inverted button">lg</a>
            <a data-class="big" class="wojo mini primary inverted  button">xl</a>
         </div>
      </div>
      <div class="field center-align">
         <label>Label Color</label>
         <div data-type="color" class="wojo vertical buttons" id="label_color">
            <?php include '_color.tpl.php'; ?>
         </div>
      </div>
   </div>
</div>