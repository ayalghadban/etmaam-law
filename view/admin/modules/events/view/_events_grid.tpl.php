<?php
   /**
    * _events_grid
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _events_grid.tpl.php, v1.00 5/9/2023 8:52 PM Gewa Exp $
    *
    */

   use Wojo\Core\Router;
   use Wojo\Date\Date;
   use Wojo\Language\Language;
   use Wojo\Url\Url;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<form method="get" id="wojo_form" action="<?php echo Url::url(Router::$path); ?>" name="wojo_form" class="wojo form">
   <div class="row gutters align-middle">
      <div class="columns tablet-30 mobile-100">
         <div class="wojo small icon input">
            <input id="fromdate" name="fromdate" type="text" placeholder="<?php echo Language::$word->FROM; ?>" readonly>
            <i class="icon calendar range"></i>
         </div>
      </div>
      <div class="columns tablet-30 mobile-100">
         <div class="wojo small action icon input">
            <i class="icon calendar range"></i>
            <input id="enddate" name="enddate" type="text" placeholder="<?php echo Language::$word->TO; ?>" readonly>
            <button id="doDates" class="wojo primary inverted icon button">
               <i class="icon search"></i>
            </button>
         </div>
      </div>
      <div class="columns mobile-hide phone-hide"></div>
      <div class="columns auto mobile-50 phone-50">
         <a href="<?php echo Url::url('admin/modules/events', 'new/'); ?>" class="wojo small secondary fluid button">
            <i class="icon plus alt"></i><?php echo Language::$word->_MOD_EM_SUB; ?></a>
      </div>
      <div class="columns auto mobile-50 phone-50 right-align">
         <a href="<?php echo Url::url('admin/modules/events'); ?>" class="wojo primary small icon button">
            <i class="icon list"></i>
         </a>
         <a class="wojo small secondary passive inverted icon button">
            <i class="icon grid"></i>
         </a>
      </div>
   </div>
</form>
<div class="center-align">
   <div class="wojo small divided horizontal list">
      <div class="disabled item text-weight-700">
         <?php echo Language::$word->SORTING_O; ?>
      </div>
      <a href="<?php echo Url::url(Router::$path); ?>" class="item<?php echo Url::setActive('order', false); ?>">
         <?php echo Language::$word->RESET; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, '?order=title|DESC'); ?>"
         class="item<?php echo Url::setActive('order', 'title'); ?>">
         <?php echo Language::$word->NAME; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, '?order=venue|DESC'); ?>"
         class="item<?php echo Url::setActive('order', 'venue'); ?>">
         <?php echo Language::$word->_MOD_EM_SUB1; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, '?order=contact|DESC'); ?>"
         class="item<?php echo Url::setActive('order', 'contact'); ?>">
         <?php echo Language::$word->_MOD_EM_SUB2; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, '?order=ending|DESC'); ?>"
         class="item<?php echo Url::setActive('order', 'ending'); ?>">
         <?php echo Language::$word->_MOD_EM_SUB23; ?>
      </a>
      <a href="<?php echo Url::sortItems(Url::url(Router::$path), 'order'); ?>" class="item">
         <i
           class="icon caret <?php echo Url::ascDesc('order'); ?> link"></i>
      </a>
   </div>
</div>
<div class="center-align margin-vertical">
   <?php echo Validator::alphaBits(Url::url(Router::$path)); ?>
</div>
<?php if (!$this->data): ?>
   <div class="center-align">
      <img src="<?php echo ADMINVIEW; ?>/images/notfound.svg" alt="" class="wojo big inline image">
      <div class="margin-small-top">
         <p class="wojo small icon alert inverted attached compact message">
            <i class="icon exclamation triangle"></i><?php echo Language::$word->_MOD_EM_NOEVENTS; ?></p>
      </div>
   </div>
<?php else: ?>
   <div class="wojo full cards screen-4 tablet-3 mobile-2 phone-1">
      <?php foreach ($this->data as $row): ?>
         <div class="card" id="item_<?php echo $row->id; ?>">
            <div class="content">
               <div class="center-align">
                  <div class="text-size-huge"><?php echo Date::doDate('dd', $row->date_start); ?></div>
                  <p class="text-size-small text-weight-700"><?php echo Date::doDate('MMMM', $row->date_start); ?>
                     , <?php echo Date::doDate('yyyy', $row->date_start); ?></p>
                  <h5>
                     <a href="<?php echo Url::url('admin/modules/events/edit', $row->id); ?>"><?php echo $row->title; ?></a>
                  </h5>
                  <p class="basic"><?php echo $row->venue; ?></p>
                  <p><?php echo $row->contact?: '-/-'; ?></p>
                  <span class="text-size-small text-weight-700 text-color-grey"><?php echo Language::$word->_MOD_EM_TIME_S; ?>
                  <?php echo Date::doTime($row->time_start) . '/' . Date::doTime($row->time_end); ?></span>
               </div>
            </div>
            <div class="footer center-align">
               <a class="wojo primary inverted icon button"
                  href="<?php echo Url::url('admin/modules/events/edit', $row->id); ?>">
                  <i class="icon pencil"></i>
               </a>
               <a data-set='{"option":[{"action": "delete","title": "<?php echo Validator::sanitize($row->title, 'chars'); ?>","id":<?php echo $row->id; ?>}],"action":"delete","parent":"#item_<?php echo $row->id; ?>","url":"modules/events/action/"}'
                 class="wojo negative inverted icon button action">
                  <i class="icon trash"></i>
               </a>
            </div>
         </div>
      <?php endforeach; ?>
   </div>
<?php endif; ?>
<div class="padding-small-horizontal">
   <div class="row gutters align-middle">
      <div class="columns mobile-100 phone-100">
         <div class="text-size-small text-weight-500"><?php echo Language::$word->TOTAL . ': ' . $this->pager->items_total; ?>
            / <?php echo Language::$word->CURPAGE . ': ' . $this->pager->current_page . ' ' . Language::$word->OF . ' ' . $this->pager->num_pages; ?></div>
      </div>
      <div class="columns mobile-100 phone-100 auto"><?php echo $this->pager->display(); ?></div>
   </div>
</div>