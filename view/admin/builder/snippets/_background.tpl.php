<?php
    
    /**
     * Element Helper Background
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _background.tpl.php, v1.00 2022-01-08 10:12:05 gewa Exp $
     */
    if (!defined('_WOJO'))
        die('Direct access to this location is not allowed.');
?>
<div class="wojo small block fields">
  <div class="field">
    <label>Color</label>
    <div class="wojo vertical buttons" id="background_color">
        <?php include '_color.tpl.php'; ?>
    </div>
  </div>
  <div class="field">
    <label>Image</label>
    <div class="row mini-gutters align-middle">
      <div class="columns auto">
        <div id="bgImageHolder"></div>
      </div>
      <div class="columns auto" id="background_clear">
        <a class="wojo negative mini inverted icon button"><i class="icon x alt"></i></a>
      </div>
      <div class="columns right-align"><a class="wojo mini primary inverted icon button" id="background_image">
          <i class="icon folder"></i></a></div>
    </div>
  </div>
  <div class="field center-align">
    <label>Position</label>
    <div class="wojo vertical buttons" id="background_position">
      <div class="wojo mini primary inverted buttons justify-center">
        <a data-class="bg-position-top-start" class="wojo icon mini button auto">
          <i class="icon grid align top left"></i>
        </a>
        <a data-class="bg-position-top-center" class="wojo icon mini button auto">
          <i class="icon grid align top middle"></i>
        </a>
        <a data-class="bg-position-top-end" class="wojo icon mini button auto">
          <i class="icon grid align top right"></i>
        </a>
      </div>
      <div class="wojo mini primary inverted buttons justify-center">
        <a data-class="bg-position-center-start" class="wojo icon mini button auto">
          <i class="icon grid align center left"></i>
        </a>
        <a data-class="bg-position-center" class="wojo icon mini button auto">
          <i class="icon grid align center middle"></i>
        </a>
        <a data-class="bg-position-center-end" class="wojo icon mini button auto">
          <i class="icon grid align center right"></i>
        </a>
      </div>
      <div class="wojo mini primary inverted buttons justify-center">
        <a data-class="bg-position-bottom-start" class="wojo icon mini button auto">
          <i class="icon grid align bottom left"></i>
        </a>
        <a data-class="bg-position-bottom-center" class="wojo icon mini button auto">
          <i class="icon grid align bottom middle"></i>
        </a>
        <a data-class="bg-position-bottom-end" class="wojo icon mini button auto">
          <i class="icon grid align bottom right"></i>
        </a>
      </div>
    </div>
  </div>
  <div class="field">
    <label>Size</label>
    <div class="wojo vertical buttons" id="background_size">
      <div class="wojo mini primary inverted fluid buttons">
        <a data-class="bg-size-cover" class="wojo mini button">
          Cover
        </a>
        <a data-class="bg-size-contain" class="wojo mini button">
          Contain
        </a>
      </div>
      <div class="wojo mini primary inverted fluid buttons">
        <a data-class="bg-size-fit-x" class="wojo mini button">
          Fit Horizontal
        </a>
        <a data-class="bg-size-fit-y" class="wojo mini button">
          Fit Vertical
        </a>
      </div>
    </div>
  </div>
  <div class="field">
    <div class="wojo checkbox toggle fitted inline">
      <input name="background_fixed" type="checkbox" value="yes"
             id="background_fixed">
      <label for="background_fixed" class="text-size-mini">Fixed</label>
    </div>
  </div>
  <div class="field">
    <div class="wojo checkbox toggle fitted inline">
      <input name="background_repeat" type="checkbox" value="yes"
             id="background_repeat">
      <label for="background_repeat" class="text-size-mini">No Repeat</label>
    </div>
  </div>
</div>