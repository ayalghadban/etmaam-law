<?php
   /**
    * _language_grid
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _language_grid.tpl.php, v1.00 5/8/2023 9:18 AM Gewa Exp $
    *
    */

   use Wojo\Core\Router;
   use Wojo\Language\Language;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="row gutters justify-end">
   <div class="columns auto mobile-100 phone-100">
      <a href="<?php echo Url::url(Router::$path, 'new/'); ?>" class="wojo small secondary fluid button"><i
           class="icon plus alt"></i><?php echo Language::$word->LG_SUB5; ?></a>
   </div>
</div>
<div class="row justify-center grid phone-1 tablet-2 mobile-1 screen-2 gutters">
   <?php foreach ($this->data as $row): ?>
      <div class="columns" id="item_<?php echo $row->id; ?>">
         <div class="wojo framed card">
            <img src="<?php echo ADMINVIEW; ?>/images/language.svg" alt="">
            <div class="divided footer">
               <div class="row align-middle small-horizontal-gutters">
                  <div class="columns">
                     <div class="wojo positive inverted buttons">
                        <a href="<?php echo Url::url(Router::$path . '/edit', $row->id); ?>" class="wojo button">
                           <span class="flag icon <?php echo $row->abbr; ?>"></span>
                           <?php echo $row->name; ?></a>
                        <a href="<?php echo Url::url(Router::$path . '/translate', $row->id); ?>" class="wojo icon button">
                           <i class="icon chat"></i>
                        </a>
                        <?php if (!$row->home): ?>
                           <a
                             data-set='{"option":[{"action": "delete","title": "<?php echo Validator::sanitize($row->name, 'chars'); ?>","id": <?php echo $row->id; ?>}],"action":"delete","parent":"#item_<?php echo $row->id; ?>", "url":"languages/action/"}'
                             class="wojo icon button data"><i class="icon trash"></i></a>
                        <?php endif; ?>
                     </div>
                  </div>
                  <div class="columns auto">
                     <a data-id="<?php echo $row->id; ?>" class="wojo icon empty button lang-color <?php echo Utility::colorToWord($row->color); ?> colorButton"><i class="icon blank"></i></a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   <?php endforeach; ?>
</div>
