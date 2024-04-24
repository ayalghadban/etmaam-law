<?php
   /**
    * _margins
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _margins.tpl.php, v1.00 5/8/2023 9:18 AM Gewa Exp $
    *
    */

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="wojo small block fields">
   <div class="field">
      <label>Margin Screen</label>
      <div class="wojo vertical buttons" id="margins_device">
         <div class="wojo wojo mini fluid buttons">
            <a data-type="screen" class="wojo mini primary inverted button">
               <i class="icon screen"></i>
               +1200px
            </a>
            <a data-type="tablet" class="wojo mini primary inverted button">
               <i class="icon tablet"></i>
               -1199px
            </a>
         </div>
         <div class="wojo wojo mini fluid buttons">
            <a data-type="mobile" class="wojo mini primary inverted  button">
               <i class="icon mobile"></i>
               -768px
            </a>
            <a data-type="phone" class="wojo mini primary inverted  button">
               <i class="icon phone"></i>
               -640px
            </a>
         </div>
      </div>
   </div>
   <div class="field">
      <label>Margin Size</label>
      <div class="wojo vertical buttons" id="margins_size">
         <div class="wojo wojo mini fluid buttons">
            <a data-class="mini" class="wojo mini primary inverted button">8px</a>
            <a data-class="small" class="wojo mini primary inverted button">16px</a>
            <a data-class="default" class="wojo mini primary inverted button">32px</a>
         </div>
         <div class="wojo wojo mini fluid buttons">
            <a data-class="medium" class="wojo mini primary inverted  button">48px</a>
            <a data-class="large" class="wojo mini primary inverted  button">64px</a>
            <a data-class="big" class="wojo mini primary inverted  button">80px</a>
            <a data-class="huge" class="wojo mini primary inverted  button">96px</a>
         </div>
      </div>
   </div>
   <div class="field" id="margins_directions">
      <label>Margin Directions</label>
      <div class="wojo vertical buttons">
         <div class="wojo wojo mini buttons justify-center">
            <a data-class="default" class="wojo icon mini primary inverted button auto">
               <i class="icon wysiwyg border outer"></i>
            </a>
            <a data-class="horizontal" class="wojo icon mini primary inverted button auto">
               <i class="icon wysiwyg border vertical"></i>
            </a>
            <a data-class="vertical" class="wojo icon mini primary inverted button auto">
               <i class="icon wysiwyg border horizontal"></i>
            </a>
         </div>
         <div class="wojo wojo mini buttons justify-center">
            <a data-class="top" class="wojo icon mini primary inverted button auto">
               <i class="icon wysiwyg border top"></i>
            </a>
            <a data-class="bottom" class="wojo icon mini primary inverted button auto">
               <i class="icon wysiwyg border bottom"></i>
            </a>
            <a data-class="left" class="wojo icon mini primary inverted button auto">
               <i class="icon wysiwyg border left"></i>
            </a>
            <a data-class="right" class="wojo icon mini primary inverted button auto">
               <i class="icon wysiwyg border right"></i>
            </a>
         </div>
      </div>
   </div>
   <div class="field">
      <a class="wojo mini fluid primary button" id="addMargin">Add Margin</a>
   </div>
   <div class="field basic">
      <div class="wojo vertical buttons" id="margins_container"></div>
   </div>
</div>