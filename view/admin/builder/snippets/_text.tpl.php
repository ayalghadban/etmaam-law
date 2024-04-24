<?php
   /**
    * _text
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _text.tpl.php, v1.00 5/8/2023 9:18 AM Gewa Exp $
    *
    */

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="wojo small block fields">
   <div class="field center-align" id="text_decoration">
      <label>Decoration</label>
      <div class="wojo wojo mini primary inverted buttons justify-center">
         <a data-class="underline-text" class="wojo icon mini button auto">
            <i class="icon wysiwyg type underline"></i>
         </a>
         <a data-class="text-weight-700" class="wojo icon mini button auto">
            <i class="icon wysiwyg type bold"></i>
         </a>
         <a data-class="strike-text" class="wojo icon mini button auto">
            <i class="icon wysiwyg type strikethrough"></i>
         </a>
         <a data-class="italic-text" class="wojo icon mini button auto">
            <i class="icon wysiwyg type italic"></i>
         </a>
      </div>
   </div>
   <div class="field center-align" id="text_transform">
      <label>Transform</label>
      <div class="wojo wojo mini primary inverted buttons justify-center">
         <a data-class="none" class="wojo mini icon button">
            <i class="icon dash"></i>
         </a>
         <a data-class="uppercase-text" class="wojo icon mini button">
            <i class="icon wysiwyg uppercase"></i>
         </a>
         <a data-class="capitalize-text" class="wojo icon mini button">
            <i class="icon wysiwyg type"></i>
         </a>
         <a data-class="lowercase-text" class="wojo icon mini button">
            <i class="icon wysiwyg lowercase"></i>
         </a>
      </div>
   </div>
   <div class="field center-align">
      <label>Text Align</label>
      <div class="wojo vertical buttons" id="text_align">
         <div class="wojo wojo mini buttons justify-end">
            <a data-class="left-align" class="wojo icon mini primary inverted button auto">
               <i class="icon wysiwyg text left"></i>
            </a>
            <a data-class="center-align" class="wojo icon mini primary inverted button auto">
               <i class="icon wysiwyg text center"></i>
            </a>
            <a data-class="right-align" class="wojo icon mini primary inverted button auto">
               <i class="icon wysiwyg text right"></i>
            </a>
            <a data-class="none-align" class="wojo icon mini primary inverted button auto">
               <i class="icon x alt"></i>
            </a>
         </div>
         <div class="wojo wojo mini buttons">
            <a class="wojo mini icon secondary inverted button passive start">
               <i class="icon screen"></i>
            </a>
            <a data-class="screen-left-align" class="wojo icon mini primary inverted button auto">
               <i class="icon wysiwyg text left"></i>
            </a>
            <a data-class="screen-center-align" class="wojo icon mini primary inverted button auto">
               <i class="icon wysiwyg text center"></i>
            </a>
            <a data-class="screen-right-align" class="wojo icon mini primary inverted button auto">
               <i class="icon wysiwyg text right"></i>
            </a>
            <a data-class="screen-none" class="wojo icon mini primary inverted button auto">
               <i class="icon x alt"></i>
            </a>
         </div>
         <div class="wojo wojo mini buttons">
            <a class="wojo mini icon secondary inverted button passive start">
               <i class="icon tablet"></i>
            </a>
            <a data-class="tablet-left-align" class="wojo icon mini primary inverted button auto">
               <i class="icon wysiwyg text left"></i>
            </a>
            <a data-class="tablet-center-align" class="wojo icon mini primary inverted button auto">
               <i class="icon wysiwyg text center"></i>
            </a>
            <a data-class="tablet-right-align" class="wojo icon mini primary inverted button auto">
               <i class="icon wysiwyg text right"></i>
            </a>
            <a data-class="tablet-none" class="wojo icon mini primary inverted button auto">
               <i class="icon x alt"></i>
            </a>
         </div>
         <div class="wojo wojo mini buttons">
            <a class="wojo mini icon secondary inverted button passive start">
               <i class="icon mobile"></i>
            </a>
            <a data-class="mobile-left-align" class="wojo icon mini primary inverted button auto">
               <i class="icon wysiwyg text left"></i>
            </a>
            <a data-class="mobile-center-align" class="wojo icon mini primary inverted button auto">
               <i class="icon wysiwyg text center"></i>
            </a>
            <a data-class="mobile-right-align" class="wojo icon mini primary inverted button auto">
               <i class="icon wysiwyg text right"></i>
            </a>
            <a data-class="mobile-none" class="wojo icon mini primary inverted button auto">
               <i class="icon x alt"></i>
            </a>
         </div>
         <div class="wojo wojo mini buttons">
            <a class="wojo mini icon secondary inverted button passive start">
               <i class="icon phone"></i>
            </a>
            <a data-class="phone-left-align" class="wojo icon mini primary inverted button auto">
               <i class="icon wysiwyg text left"></i>
            </a>
            <a data-class="phone-center-align" class="wojo icon mini primary inverted button auto">
               <i class="icon wysiwyg text center"></i>
            </a>
            <a data-class="phone-right-align" class="wojo icon mini primary inverted button auto">
               <i class="icon wysiwyg text right"></i>
            </a>
            <a data-class="phone-none" class="wojo icon mini primary inverted button auto">
               <i class="icon x alt"></i>
            </a>
         </div>
      </div>
   </div>
   <div class="field center-align">
      <label>Size</label>
      <div class="wojo vertical buttons" id="text_size">
         <div class="wojo wojo mini buttons">
            <a data-class="text-size-tiny" class="wojo mini primary inverted button"> 11px</a>
            <a data-class="text-size-mini" class="wojo mini primary inverted button"> 12px</a>
            <a data-class="text-size-small" class="wojo mini primary inverted button"> 14px</a>
         </div>
         <div class="wojo wojo mini buttons">
            <a data-class="text-size-normal" class="wojo mini primary inverted button"> 16px</a>
            <a data-class="text-size-medium" class="wojo mini primary inverted button"> 18px</a>
            <a data-class="text-size-large" class="wojo mini primary inverted button"> 26px</a>
         </div>
      </div>
   </div>
   <div class="field center-align">
      <label>Text Weight</label>
      <div class="wojo vertical buttons" id="text_weight">
         <div class="wojo wojo mini buttons">
            <a data-class="none" class="wojo mini primary inverted button">
               <i class="icon dash"></i>
            </a>
            <a data-class="text-weight-200" class="wojo mini primary inverted button"> 200</a>
            <a data-class="text-weight-300" class="wojo mini primary inverted button"> 300</a>
            <a data-class="text-weight-400" class="wojo mini primary inverted button"> 400</a>
         </div>
         <div class="wojo wojo mini buttons">
            <a data-class="text-weight-500" class="wojo mini primary inverted button"> 500</a>
            <a data-class="text-weight-600" class="wojo mini primary inverted button"> 600</a>
            <a data-class="text-weight-700" class="wojo mini primary inverted button"> 700</a>
         </div>
      </div>
   </div>
   <div class="field center-align">
      <label>Text Color</label>
      <div class="wojo vertical buttons" id="text_color">
         <?php include '_color.tpl.php'; ?>
      </div>
   </div>
</div>