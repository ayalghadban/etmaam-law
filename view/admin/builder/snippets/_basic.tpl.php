<?php
   /**
    * _basic
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _basic.tpl.php, v1.00 5/8/2023 9:18 AM Gewa Exp $
    *
    */

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
   <div class="wojo small block fields">
      <div class="field disabled">
         <label>Name in editor</label>
         <input type="text" name="basic_name" disabled="disabled">
      </div>
      <div id="b_text" class="hide-all">
         <div class="field">
            <label>Text</label>
            <textarea name="basic_text" class="small"></textarea>
         </div>
      </div>
   </div>
   <div id="frame_link" class="hide-all">
      <div class="wojo small block fields">
         <div class="field">
            <label>Link</label>
            <input name="frame_url" placeholder="https://" type="text">
         </div>
         <div class="field">
            <button id="frame_update" type="button" class="wojo mini fluid primary button">Update</button>
         </div>
      </div>
   </div>
   <div id="b_link" class="hide-all">
      <div class="wojo small block fields">
         <div id="b_url_text">
            <div class="field">
               <label>Text</label>
               <input id="basic_url_text" type="text">
            </div>
         </div>
         <div class="field">
            <label>Link</label>
            <input id="basic_url" placeholder="https://" type="text">
         </div>
         <div class="field">
            <select name="basic_links" id="basic_links"></select>
         </div>
      </div>
   </div>
<?php include_once '_button.tpl.php'; ?>
<?php include_once '_image.tpl.php'; ?>
<?php include_once '_label.tpl.php'; ?>