<?php
   /**
    * Element Helper
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2022
    * @version $Id: _element_helper.tpl.php, v1.00 2022-01-08 10:12:05 gewa Exp $
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div id="section-helper" class="hide-all is_draggable">
   <div class="header">
      <i class="icon white pencil"></i>
      <h3 class="handle"> Section Editor</h3>
      <a class="close-styler">
         <i class="icon white x"></i>
      </a>
   </div>
   <div class="wojo form">
      <div class="padding">
         <div class="wojo block fields">
            <div class="field">
               <label>Margin Top</label>
               <input class="rangers" name="marginTop" type="text" min="0" max="400" step="5" value="0" hidden
                      data-suffix=" px">
            </div>
            <div class="field">
               <label>Margin Bottom</label>
               <input class="rangers" name="marginBottom" type="text" min="0" max="400" step="5" value="0" hidden
                      data-suffix=" px">
            </div>
            <div class="field">
               <label>Margin Left</label>
               <input class="rangers" name="marginLeft" type="text" min="0" max="100" step="4" value="0" hidden
                      data-suffix=" px">
            </div>
            <div class="field">
               <label>Margin Right</label>
               <input class="rangers" name="marginRight" type="text" min="0" max="100" step="4" value="0" hidden
                      data-suffix=" px">
            </div>
         </div>
      </div>
   </div>
</div>