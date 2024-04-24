<?php
    /**
     * Source Helper
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _source_helper.tpl.php, v1.00 2022-01-08 10:12:05 gewa Exp $
     */
    if (!defined('_WOJO'))
        die('Direct access to this location is not allowed.');
?>
<div id="editSource" class="wojo big modal">
  <div class="dialog" role="document">
    <div class="content">
      <div class="header">
        <h5>Edit Html</h5>
      </div>
      <div class="wojo form body">
        <div id="tempHtml">-- code edit --</div>
      </div>
      <div class="footer">
        <button type="button" class="wojo small simple button" data="modal:close">Cancel</button>
        <button type="button" class="wojo small positive button" data="modal:ok">Ok</button>
      </div>
    </div>
  </div>
</div>