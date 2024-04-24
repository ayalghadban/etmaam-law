<?php
   /**
    * _pages_list
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _page_list.tpl.php, v1.00 5/7/2023 8:59 AM Gewa Exp $
    *
    */

   use Wojo\Core\Content;
   use Wojo\Core\Router;
   use Wojo\Language\Language;
   use Wojo\Url\Url;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="row gutters align-middle justify-between">
   <div class="columns screen-40 tablet-50 mobile-100">
      <div class="wojo form">
         <div class="wojo small icon ajax input">
            <input name="find" placeholder="<?php echo Language::$word->SEARCH; ?>" type="text" data-page="Page" data-type="page">
            <i class="icon search"></i>
            <div class="results"></div>
         </div>
      </div>
   </div>
   <div class="columns auto mobile-100 phone-100">
      <a href="<?php echo Url::url(Router::$path, 'new/'); ?>" class="wojo small secondary fluid button">
         <i class="icon plus alt"></i><?php echo Language::$word->PAG_SUB4; ?></a>
   </div>
</div>
<?php if (!$this->data): ?>
   <div class="center-align">
      <img src="<?php echo ADMINVIEW; ?>images/notfound.svg" alt="" class="wojo big inline image">
      <div class="margin-small-top">
         <p class="wojo small icon alert inverted attached compact message">
            <i
              class="icon exclamation triangle"></i><?php echo Language::$word->ET_INFO; ?></p>
      </div>
   </div>
<?php else: ?>
   <table class="wojo responsive table">
      <thead>
      <tr>
         <th class="auto"></th>
         <th><?php echo Language::$word->PAG_NAME; ?></th>
         <th><?php echo Language::$word->TYPE; ?></th>
         <th class="right-align"><?php echo Language::$word->ACTIONS; ?></th>
      </tr>
      </thead>
      <?php foreach ($this->data as $row): ?>
         <tr id="item_<?php echo $row->id; ?>">
            <td>
               <span class="wojo small simple label"><?php echo $row->id; ?></span>
            </td>
            <td>
               <a href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>"><?php echo $row->{'title' . Language::$lang}; ?></a>
            </td>
            <td><?php echo Content::pageType($row->page_type); ?></td>
            <td class="auto">
               <a href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>" class="wojo icon primary inverted circular button">
                  <i class="icon pencil"></i>
               </a>
               <?php if ($row->page_type == 'normal'): ?>
                  <a data-set='{"option":[{"action":"copy","id":<?php echo $row->id; ?>}], "label":"<?php echo Language::$word->COPY; ?>", "url":"pages/action/", "parent":"#item_<?php echo $row->id; ?>", "complete":"append", "modalclass":"normal", "redirect":true}' class="wojo circular secondary inverted icon button action">
                     <i class="icon copy"></i>
                  </a>
                  <a data-set='{"option":[{"action": "trash","title": "<?php echo Validator::sanitize($row->{'title' . Language::$lang}, 'chars'); ?>","id": "<?php echo $row->id; ?>"}],"action":"trash","parent":"#item_<?php echo $row->id; ?>", "url":"pages/action/"}' class="wojo icon simple button data">
                     <i class="icon negative trash"></i>
                  </a>
               <?php else: ?>
                  <a class="wojo icon simple disabled button">
                     <i class="icon x alt"></i>
                  </a>
               <?php endif; ?>
            </td>
         </tr>
      <?php endforeach; ?>
   </table>
<?php endif; ?>
<div class="margin-top padding-small-horizontal">
   <div class="row gutters align-middle">
      <div class="columns mobile-100 phone-100">
         <div class="text-size-small text-weight-500"><?php echo Language::$word->TOTAL . ': ' . $this->pager->items_total; ?>
            / <?php echo Language::$word->CURPAGE . ': ' . $this->pager->current_page . ' ' . Language::$word->OF . ' ' . $this->pager->num_pages; ?></div>
      </div>
      <div class="columns mobile-100 phone-100 auto"><?php echo $this->pager->display(); ?></div>
   </div>
</div>