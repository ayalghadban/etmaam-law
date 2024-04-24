<?php
   /**
    * Code Helper
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version $Id: code_helper.tpl.php, v1.00 2023-01-08 10:12:05 gewa Exp $
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div id="editSource" class="hidden" data-duration="200">
   <div id="tempHtml">-- code edit --</div>
   <div class="action">
      <button type="button" class="wojo mini simple light button cancel">Cancel</button>
      <button type="button" class="wojo mini simple primary button ok">Apply</button>
   </div>
</div>