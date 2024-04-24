<?php
   /**
    * _grid
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _grid.tpl.php, v1.00 5/12/2023 8:39 PM Gewa Exp $
    *
    */

   use Wojo\Core\Router;
   use Wojo\Language\Language;
   use Wojo\Url\Url;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="row gutters justify-end">
   <div class="columns auto mobile-100 phone-100">
      <a href="<?php echo Url::url(Router::$path, 'new/'); ?>" class="wojo small secondary fluid button">
         <i class="icon plus alt"></i><?php echo Language::$word->_MOD_GA_NEW; ?></a>
   </div>
   <div class="columns auto mobile-100 phone-100">
      <a class="wojo small primary inverted icon button" id="reorder">
         <i class="icon grid"></i>
      </a>
   </div>
</div>
<div class="hide-all center-align margin-small-bottom" id="dragNotice">
   <p class="wojo compact inverted icon info message">
      <i class="icon information square"></i>
      <?php echo str_replace('[ICON]', "&nbsp;&nbsp;<i class=\"icon check\"></i>", Language::$word->_MOD_GA_INFO_DRAG); ?>
   </p>
</div>
<?php if (!$this->data): ?>
   <div class="center-align">
      <img src="<?php echo ADMINVIEW; ?>images/notfound.svg" alt="" class="wojo big inline image">
      <div class="margin-small-top">
         <p class="wojo small icon alert inverted attached compact message">
            <i class="icon exclamation triangle"></i><?php echo Language::$word->_MOD_GA_NOGAL; ?></p>
      </div>
   </div>
<?php else: ?>
   <div class="wojo sortable mason" id="sortable">
      <?php foreach ($this->data as $row): ?>
         <div class="items" id="item_<?php echo $row->id; ?>" data-id="<?php echo $row->id; ?>">
            <div class="wojo attached framed card">
               <img src="<?php echo $row->poster? FMODULEURL . 'gallery/data/' . $row->dir . '/thumbs/' . $row->poster : UPLOADURL . 'blank.jpg'; ?>"
                 class="wojo rounded image" alt="">
               <div class="content">
                  <div class="center-align margin-bottom">
                     <h5><?php echo $row->{'title' . Language::$lang}; ?></h5>
                     <a class="wojo primary inverted icon button"
                        href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>">
                        <i class="icon pencil"></i>
                     </a>
                     <a data-set='{"option":[{"action": "delete","title": "<?php echo Validator::sanitize($row->{'title' . Language::$lang}, 'chars'); ?>","id":<?php echo $row->id; ?>, "dir":"<?php echo $row->dir; ?>", "type":"gallery"}],"action":"delete","parent":"#item_<?php echo $row->id; ?>","url":"modules/gallery/action/"}'
                       class="wojo negative inverted icon button data">
                        <i class="icon trash"></i>
                     </a>
                  </div>
                  <div class="row align-middle">
                     <div class="columns">
                        <a href="<?php echo Url::url(Router::$path, 'photos/' . $row->id); ?>"
                           class="wojo primary inverted button" data-width="auto"
                           data-tooltip="<?php echo Language::$word->_MOD_GA_PHOTOS; ?>">
                           <i class="icon images"></i><?php echo $row->pics; ?>
                        </a>
                     </div>
                     <div class="columns auto">
                        <div class="wojo passive inverted button">
                           <i class="icon hand thumbs up"></i><?php echo $row->likes; ?></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      <?php endforeach; ?>
   </div>
<?php endif; ?>
