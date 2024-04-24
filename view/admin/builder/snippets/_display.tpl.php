<?php
   /**
    * _display
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _display.tpl.php, v1.00 5/8/2023 9:18 AM Gewa Exp $
    *
    */

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div id="b_display">
   <div class="wojo small block fields">
      <div class="field">
         <label>Type</label>
         <div class="wojo vertical buttons" id="display_type">
            <a data-class="none" class="wojo mini primary inverted button"><i class="icon dash"></i></a>
            <a data-class="display-inline" class="wojo mini primary inverted button">Inline</a>
            <a data-class="display-inline-block" class="wojo mini primary inverted button">Inline Block</a>
            <a data-class="display-block" class="wojo mini primary inverted button">Block</a>
            <a data-class="display-flex" class="wojo mini primary inverted  button">Flex</a>
            <a data-class="display-inline-flex" class="wojo mini primary inverted  button">Inline Flex</a>
         </div>
      </div>
   </div>
</div>