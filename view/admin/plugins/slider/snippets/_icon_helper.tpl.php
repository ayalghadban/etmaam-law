<?php
    /**
     * Icon Helper
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _icon_helper.tpl.php, v1.00 2022-01-08 10:12:05 gewa Exp $
     */
    if (!defined('_WOJO'))
        die('Direct access to this location is not allowed.');
?>
<div id="icon-helper" class="hide-all is_draggable">
  <div class="header">
    <i class="icon white pencil"></i>
    <h3 class="handle"> Icon Editor</h3>
    <a class="close-styler"><i class="icon white x"></i></a>
  </div>
  <div class="padding-small max-height400 scrollbox">
    <div class="row grid screen-6 small gutters" id="modalIcons"></div>
  </div>
</div>