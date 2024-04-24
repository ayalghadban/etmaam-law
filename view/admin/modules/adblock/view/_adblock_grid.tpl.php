<?php
   /**
    * _adblock_grid
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _adblock_grid.tpl.php, v1.00 5/12/2023 14:37 PM Gewa Exp $
    */

   use Wojo\Module\Adblock\Adblock;
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
         <a href="<?php echo Url::url(Router::$path, 'new/'); ?>" class="wojo secondary fluid button">
            <i class="icon plus alt"></i><?php echo Language::$word->_MOD_AB_NEW; ?></a>
      </div>
   </div>
<?php if (!$this->data): ?>
   <div class="center-align">
      <img src="<?php echo ADMINVIEW; ?>images/notfound.svg" alt="" class="wojo big inline image">
      <div class="margin-small-top">
         <p class="wojo small icon alert inverted attached compact message">
            <i class="icon exclamation triangle"></i><?php echo Language::$word->_MOD_AB_NO_CMP; ?></p>
      </div>
   </div>
<?php else: ?>
   <div class="row grid mobile-1 tablet-2 screen-3 gutters justify-center">
      <?php foreach ($this->data as $row): ?>
         <div class="columns" id="item_<?php echo $row->id; ?>">
            <div class="wojo attached card">
               <div class="wojo top right attached simple passive button"
                    data-content="<?php echo Adblock::isOnlineStr($row); ?>">
                  <span class="wojo <?php echo Adblock::isOnline($row)? 'positive' : 'negative'; ?> ring label"></span>
               </div>
               <div class="content">
                  <div class="center-align">
                     <img src="<?php echo AMODULEURL; ?>adblock/view/images/<?php echo $row->image? 'image.png' : 'html.png'; ?>"
                          class="wojo normal inline image" alt="">
                     <h6 class="truncate margin-top"><?php echo $row->{'title' . Language::$lang}; ?></h6>
                     <div class="wojo small primary inverted label label">
                        <?php echo Language::$word->_MOD_AB_SUB9; ?>
                        <?php echo $row->total_views; ?>
                     </div>
                     <div class="wojo small primary inverted label label">
                        <?php echo Language::$word->_MOD_AB_SUB8; ?>
                        <?php echo $row->total_clicks; ?>
                     </div>
                  </div>
               </div>
               <div class="divided footer center-align">
                  <a href="<?php echo Url::url(Router::$path . '/edit', $row->id); ?>"
                     class="wojo icon primary inverted button">
                     <i class="icon pencil"></i>
                  </a>
                  <a data-set='{"option":[{"delete": "deleteCampaign","title": "<?php echo Validator::sanitize($row->{'title' . Language::$lang}, 'chars'); ?>","id":<?php echo $row->id; ?>}],"action":"delete","parent":"#item_<?php echo $row->id; ?>", "url":"modules/adblock"}'
                     class="wojo icon negative inverted button data">
                     <i class="icon trash"></i>
                  </a>
               </div>
            </div>
         </div>
      <?php endforeach; ?>
   </div>
<?php endif; ?>