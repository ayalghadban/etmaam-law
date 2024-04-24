<?php
   /**
    * _visibility
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _visibility.tpl.php, v1.00 5/8/2023 9:18 AM Gewa Exp $
    *
    */

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="wojo small block fields">
   <div class="field">
      <label>Opacity</label>
      <select name="visibility_opacity" id="visibility_opacity" class="mini">
         <option value="none">-</option>
         <option value="opacity-0">0</option>
         <option value="opacity-10">10</option>
         <option value="opacity-20">20</option>
         <option value="opacity-30">30</option>
         <option value="opacity-40">40</option>
         <option value="opacity-50">50</option>
         <option value="opacity-60">60</option>
         <option value="opacity-70">70</option>
         <option value="opacity-80">80</option>
         <option value="opacity-90">90</option>
         <option value="opacity-100">100</option>
      </select>
   </div>
   <div class="field center-align">
      <label>Overflow</label>
      <div class="wojo mini primary inverted buttons" id="visibility_overflow">
         <a data-class="none" class="wojo mini icon button">
            <i class="icon dash"></i>
         </a>
         <a data-class="overflow-hidden" class="wojo mini button"> Hidden</a>
         <a data-class="overflow-auto" class="wojo mini button"> Auto</a>
      </div>
   </div>
   <div class="field center-align">
      <label>Visibility</label>
      <div class="wojo vertical buttons" id="visibility_visibility">
         <div class="wojo wojo mini buttons">
            <a class="wojo mini secondary inverted button passive start">
               <i class="icon screen"></i>
               +1200px
            </a>
            <a data-class="screen-show" class="wojo icon mini primary inverted button auto">
               <i class="icon eye"></i>
            </a>
            <a data-class="screen-hide" class="wojo icon mini primary inverted button auto">
               <i class="icon eye slash"></i>
            </a>
            <a data-class="screen-none" class="wojo icon mini primary inverted button auto">
               <i class="icon x alt"></i>
            </a>
         </div>
         <div class="wojo wojo mini buttons">
            <a class="wojo mini secondary inverted button passive start">
               <i class="icon tablet"></i>
               -1199px
            </a>
            <a data-class="tablet-show" class="wojo icon mini primary inverted button auto">
               <i class="icon eye"></i>
            </a>
            <a data-class="tablet-hide" class="wojo icon mini primary inverted button auto">
               <i class="icon eye slash"></i>
            </a>
            <a data-class="tablet-none" class="wojo icon mini primary inverted button auto">
               <i class="icon x alt"></i>
            </a>
         </div>
         <div class="wojo wojo mini buttons">
            <a class="wojo secondary inverted button passive start">
               <i class="icon mobile"></i>
               -768px
            </a>
            <a data-class="mobile-show" class="wojo icon mini primary inverted button auto">
               <i class="icon eye"></i>
            </a>
            <a data-class="mobile-hide" class="wojo icon mini primary inverted button auto">
               <i class="icon eye slash"></i>
            </a>
            <a data-class="mobile-none" class="wojo icon mini primary inverted button auto">
               <i class="icon x alt"></i>
            </a>
         </div>
         <div class="wojo wojo mini buttons">
            <a class="wojo mini secondary inverted button passive start">
               <i class="icon phone"></i>
               -640px
            </a>
            <a data-class="phone-show" class="wojo icon mini primary inverted button auto">
               <i class="icon eye"></i>
            </a>
            <a data-class="phone-hide" class="wojo icon mini primary inverted button auto">
               <i class="icon eye slash"></i>
            </a>
            <a data-class="phone-none" class="wojo icon mini primary inverted button auto">
               <i class="icon x alt"></i>
            </a>
         </div>
      </div>
   </div>
</div>