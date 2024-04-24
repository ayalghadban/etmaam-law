<?php
   /**
    * _faq_list
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _faq_list.tpl.php, v1.00 5/9/2023 8:52 PM Gewa Exp $
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
<div class="row gutters align-middle justify-end">
   <div class="columns auto mobile-auto phone-100">
      <a href="<?php echo Url::url(Router::$path, 'new/'); ?>" class="wojo small stacked secondary button">
         <i
           class="icon plus alt"></i><?php echo Language::$word->_MOD_FAQ_NEW; ?></a>
   </div>
   <div class="columns auto mobile-auto phone-100">
      <a href="<?php echo Url::url(Router::$path, 'categories/'); ?>" class="wojo small stacked dark inverted button">
         <i
           class="icon unordered list"></i>
         <?php echo Language::$word->CATEGORIES; ?></a>
   </div>
</div>
<?php if (!$this->data): ?>
   <div class="center-align">
      <img src="<?php echo ADMINVIEW; ?>images/notfound.svg" alt="" class="wojo big inline image">
      <div class="margin-small-top">
         <p class="wojo small icon alert inverted attached compact message">
            <i
              class="icon exclamation triangle"></i><?php echo Language::$word->_MOD_FAQ_NOFAQS; ?></p>
      </div>
   </div>
<?php else: ?>
   <div class="mason">
      <?php foreach ($this->data as $cat): ?>
         <div class="items">
            <div class="wojo small simple segment">
               <h5>
                  <?php echo $cat['name']; ?>
               </h5>
               <ul class="wojo sortable list">
                  <?php foreach ($cat['items'] as $row) : ?>
                     <li class="item" data-id="<?php echo $row['id']; ?>" id="item_<?php echo $row['id']; ?>">
                        <div class="content">
                           <div class="handle">
                              <i class="icon grip horizontal"></i>
                           </div>
                           <div class="text">
                              <a href="<?php echo Url::url(Router::$path . '/edit', $row['id']); ?>"><?php echo $row['question']; ?></a>
                           </div>
                           <div class="actions">
                              <a data-set='{"option":[{"action": "delete","title": "<?php echo Validator::sanitize($row['question'], 'chars'); ?>","id":<?php echo $row['id']; ?>, "type":"item"}],"action":"delete","parent":"#item_<?php echo $row['id']; ?>","url":"modules/faq/action/"}'
                                class="wojo small negative simple icon button data">
                                 <i class="icon x alt"></i>
                              </a>
                           </div>
                        </div>
                     </li>
                  <?php endforeach; ?>
               </ul>
            </div>
         </div>
      <?php endforeach; ?>
   </div>
<?php endif; ?>

