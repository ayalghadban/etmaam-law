<?php
   /**
    * loadThumb
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: loadThumb.tpl.php, v1.00 5/18/2023 9:10 PM Gewa Exp $
    *
    */

   use Wojo\Language\Language;
   use Wojo\Url\Url;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="columns" id="item_<?php echo $this->data->id; ?>" data-id="<?php echo $this->data->id; ?>">
   <div class="wojo card attached" data-mode="<?php echo $this->data->mode; ?>"
        data-color="<?php echo $this->data->color; ?>" data-image="<?php echo $this->data->image; ?>"
     <?php switch ($this->data->mode): case 'tr': ?>
        style="background-image:url(<?php echo APLUGINURL . '/slider/view/images/transbg.png'; ?>);background-repeat: repeat;"
        <?php break; ?>
     <?php case 'cl': ?>
        style="background-color:<?php echo $this->data->color; ?>"
        <?php break; ?>
     <?php default: ?>
        style="background-image:url(<?php echo UPLOADURL . '/thumbs/' . basename($this->data->image); ?>);background-size: cover; background-position: center center; background-repeat: no-repeat;"
        <?php break; ?>
     <?php endswitch; ?>
   >
      <div class="handle draggable">
         <i class="icon grip horizontal"></i>
      </div>
      <div class="content">
         <div class="margin-bottom">
            <span class="wojo white text" data-editable="true"
                  data-set='{"action": "rename", "id":<?php echo $this->data->id; ?>, "url":"plugins/slider/action/"}'><?php echo Validator::truncate($this->data->title, 20); ?></span>
         </div>
         <div class="wojo fluid white buttons eMenu">
            <a class="wojo small icon button" data-width="auto" data-tooltip="<?php echo Language::$word->PROP; ?>"
               data-set='{"mode":"prop","id":<?php echo $this->data->id; ?>,"type":"<?php echo $this->data->mode; ?>"}'>
               <i class="icon sliders horizontal"></i>
            </a>
            <a href="<?php echo Url::url('/admin/plugins/slider/builder', $this->data->id); ?>"
               class="wojo small icon button" data-width="auto" data-tooltip="<?php echo Language::$word->EDIT; ?>"
               data-set='{"mode":"edit","id":<?php echo $this->data->id; ?>}'>
               <i class="icon pencil"></i>
            </a>
            <a class="wojo small icon button" data-width="auto" data-tooltip="<?php echo Language::$word->DUPLICATE; ?>"
               data-set='{"mode":"duplicate","id":<?php echo $this->data->id; ?>}'>
               <i class="icon copy"></i>
            </a>
            <a class="wojo small icon button" data-width="auto" data-tooltip="<?php echo Language::$word->DELETE; ?>"
               data-set='{"mode":"delete","id":<?php echo $this->data->id; ?>}'>
               <i class="icon trash"></i>
            </a>
         </div>
      </div>
   </div>
</div>