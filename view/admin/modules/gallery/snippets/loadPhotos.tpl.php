<?php
   /**
    * loadPhotos
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version $Id: loadPhotos.tpl.php, v1.00 2023-03-08 10:12:05 gewa Exp $
    */

   use Wojo\Module\Gallery\Gallery;
   use Wojo\Language\Language;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<?php if ($this->photos): ?>
   <?php foreach ($this->photos as $row): ?>
      <div class="columns" id="item_<?php echo $row->id; ?>" data-id="<?php echo $row->id; ?>">
         <div class="wojo attached framed card">
            <img src="<?php echo FMODULEURL . Gallery::GALDATA . $this->data->dir . '/thumbs/' . $row->thumb; ?>"
                 class="wojo rounded image" alt="">
            <div class="content">
               <div class="center-align margin-bottom">
                  <div class="description" id="description_<?php echo $row->id; ?>">
                     <h5><?php echo $row->{'title' . Language::$lang}; ?></h5>
                     <p><?php echo $row->{'description' . Language::$lang}; ?></p>
                  </div>
               </div>
               <div class="row align-middle">
                  <div class="columns">
                     <div class="wojo small passive inverted button">
                        <?php echo Language::$word->LIKES; ?>
                        <?php echo $row->likes; ?>
                     </div>
                  </div>
                  <div class="columns auto">
                     <a data-wdropdown="#photoMenu_<?php echo $row->id; ?>" class="wojo primary inverted icon circular button">
                        <i class="icon three dots vertical"></i>
                     </a>
                     <div class="wojo small dropdown top-right pointing" id="photoMenu_<?php echo $row->id; ?>">
                        <a class="item action"
                           data-set='{"option":[{"action":"photo","id": <?php echo $row->id; ?>}], "label":"<?php echo Language::$word->UPDATE; ?>", "url":"modules/gallery/action/", "parent":"#description_<?php echo $row->id; ?>", "complete":"replace", "modalclass":"normal"}'>
                           <i class="icon pencil"></i>
                           <?php echo Language::$word->EDIT; ?></a>
                        <a class="item <?php echo ($this->data->poster == $row->thumb)? 'disabled' : 'poster'; ?>"
                           data-poster="<?php echo $row->thumb; ?>">
                           <i class="icon <?php echo ($this->data->poster == $row->thumb)? 'check' : 'image'; ?>"></i>
                           <?php echo Language::$word->_MOD_GA_POSTER; ?></a>
                        <div class="wojo basic divider"></div>
                        <a class="item data"
                           data-set='{"option":[{"action": "delete","title": "<?php echo Validator::sanitize($row->{'title' . Language::$lang}, 'chars'); ?>","id":<?php echo $row->id; ?>, "dir":"<?php echo $this->data->dir; ?>", "type":"photo"}],"action":"delete","parent":"#item_<?php echo $row->id; ?>","url":"modules/gallery/action/"}'>
                           <i class="icon trash"></i>
                           <?php echo Language::$word->DELETE; ?></a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   <?php endforeach; ?>
<?php endif; ?>