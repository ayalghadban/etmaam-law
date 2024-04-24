<?php
    /**
     * _canvas_helper
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: _canvas_helper.tpl.php, v1.00 2023-01-08 10:12:05 gewa Exp $
     */
    if (!defined('_WOJO'))
        die('Direct access to this location is not allowed.');
?>
<div id="canvas-helper" class="transition hidden">
  <div class="header">
    <i class="icon white pencil"></i>
    <h3 class="handle"> Canvas Editor</h3>
    <a class="close-styler"><i class="icon white x"></i></a>
  </div>
  <div class="wojo form">
    <article class="wojo accordion">
      <div class="header"><span>Padding</span>
        <i class="icon angle down"></i></div>
      <div class="content">
        <div class="wojo block fields">
          <div class="field">
            <label>Padding Top</label>
            <input class="rangers" type="text" value="0" name="paddingTop"
                   data-ranger='{"step":4,"from":0, "to":400, "format":"px", "tip": false, "range":false}'>
          </div>
          <div class="field">
            <label>Padding Bottom</label>
            <input class="rangers" type="text" value="0" name="paddingBottom"
                   data-ranger='{"step":4,"from":0, "to":400, "format":"px", "tip": false, "range":false}'>
          </div>
        </div>
      </div>
    </article>
  </div>
</div>