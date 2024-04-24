<?php
   /**
    * _button
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _button.tpl.php, v1.00 5/8/2023 9:18 AM Gewa Exp $
    *
    */

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div id="b_button" class="hide-all">
   <div class="wojo small block fields">
      <div class="field">
         <label>Button Size</label>
         <div data-type="size" class="wojo wojo mini fluid buttons" id="button_size">
            <a data-class="mini" class="wojo mini primary inverted button">xs</a>
            <a data-class="small" class="wojo mini primary inverted button">sm</a>
            <a data-class="default" class="wojo mini primary inverted button">lg</a>
            <a data-class="big" class="wojo mini primary inverted  button">xl</a>
         </div>
      </div>
      <div class="field">
         <label>Button Style</label>
         <div data-type="style" class="wojo wojo mini fluid buttons" id="button_style">
            <a data-class="default" class="wojo mini primary inverted button">-</a>
            <a data-class="icon" class="wojo mini primary inverted button">icon</a>
            <a data-class="rounded" class="wojo mini primary inverted button">pill</a>
            <a data-class="circular" class="wojo mini primary inverted button">circle</a>
         </div>
      </div>
      <div class="field">
         <label>Button Width</label>
         <div data-type="width" class="wojo wojo mini fluid buttons" id="button_width">
            <a data-class="auto" class="wojo mini primary inverted button">auto</a>
            <a data-class="fluid" class="wojo mini primary inverted button">fluid</a>
         </div>
      </div>
      <div class="field center-align">
         <label>Button Icon Position</label>
         <div data-type="position" class="wojo wojo mini fluid buttons" id="button_position">
            <a data-class="default" class="wojo mini primary inverted button">left</a>
            <a data-class="right" class="wojo mini primary inverted button">right</a>
         </div>
      </div>
      <div class="field center-align">
         <label>Button Color</label>
         <div data-type="color" class="wojo vertical buttons" id="button_color">
            <?php include '_color.tpl.php'; ?>
         </div>
      </div>
   </div>
</div>
<?php include '_icon.tpl.php'; ?>