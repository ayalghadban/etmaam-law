<?php
   /**
    * _timeline_items
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _timeline_items.tpl.php, v1.00 5/20/2023 6:39 PM Gewa Exp $
    *
    */

   use Wojo\Date\Date;
   use Wojo\Language\Language;
   use Wojo\Url\Url;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="row gutters justify-end">
   <div class="columns auto mobile-100 phone-100">
      <a href="<?php echo Url::url('admin/modules/timeline/inew', $this->row->id); ?>"
         class="wojo small secondary fluid button">
         <i class="icon plus alt"></i><?php echo Language::$word->_MOD_TML_SUB11; ?>
      </a>
   </div>
</div>
<?php if (!$this->data): ?>
   <div class="center-align">
      <img src="<?php echo ADMINVIEW; ?>images/notfound.svg" alt="" class="wojo big inline image">
      <div class="margin-small-top">
         <p class="wojo small icon alert inverted attached compact message">
            <i class="icon exclamation triangle"></i><?php echo Language::$word->_MOD_TML_NOITM; ?></p>
      </div>
   </div>
<?php else: ?>
   <table class="wojo basic responsive table">
      <thead>
      <tr>
         <th data-sort="string"><?php echo Language::$word->TYPE; ?></th>
         <th data-sort="string"><?php echo Language::$word->NAME; ?></th>
         <th data-sort="int"><?php echo Language::$word->CREATED; ?></th>
         <th class="disabled right-align"><?php echo Language::$word->ACTIONS; ?></th>
      </tr>
      </thead>
      <?php foreach ($this->data as $row): ?>
         <tr id="item_<?php echo $row->id; ?>">
            <td>
               <span class="wojo mini basic label"><?php echo $row->type; ?></span>
            </td>
            <td>
               <a href="<?php echo Url::url('admin/modules/timeline/iedit', $this->row->id . '/' . $row->id); ?>">
                  <?php echo $row->{'title' . Language::$lang}; ?></a>
            </td>
            <td
              data-sort-value="<?php echo strtotime($row->created); ?>"><?php echo Date::doDate('short_date', $row->created); ?></td>
            <td class="auto">
               <a href="<?php echo Url::url('admin/modules/timeline/iedit', $this->row->id . '/' . $row->id); ?>"
                 class="wojo icon circular inverted primary button">
                  <i class="icon pencil"></i>
               </a>
               <a data-set='{"option":[{"action": "delete","title": "<?php echo Validator::sanitize($row->{'title' . Language::$lang}, 'chars'); ?>","id":<?php echo $row->id; ?>, "type":"custom"}],"action":"delete","parent":"#item_<?php echo $row->id; ?>","url":"modules/timeline/action/"}'
                 class="wojo negative inverted circular icon button data">
                  <i class="icon trash"></i>
               </a>
            </td>
         </tr>
      <?php endforeach; ?>
   </table>
<?php endif; ?>
<div class="row gutters align-middle">
   <div class="columns auto mobile-100 phone-100">
      <div class="text-size-small text-weight-500"><?php echo Language::$word->TOTAL . ': ' . $this->pager->items_total; ?>
         / <?php echo Language::$word->CURPAGE . ': ' . $this->pager->current_page . ' ' . Language::$word->OF . ' ' . $this->pager->num_pages; ?></div>
   </div>
   <div class="columns mobile-100 right-align"><?php echo $this->pager->display(); ?></div>
</div>