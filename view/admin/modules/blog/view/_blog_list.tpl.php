<?php
   /**
    * _blog_list
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _blog_list.tpl.php, v1.00 5/8/2023 12:17 AM Gewa Exp $
    *
    */

   use Wojo\Module\Blog\Blog;
   use Wojo\Core\Router;
   use Wojo\Language\Language;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="row gutters align-middle">
   <div class="columns screen-40 tablet-40 mobile-100">
      <form method="post" id="wojo_form" name="wojo_form" class="wojo form">
         <div class="wojo small action input">
            <input name="find" placeholder="<?php echo Language::$word->SEARCH; ?>" type="text">
            <button class="wojo icon primary inverted button">
               <i class="icon search"></i>
            </button>
         </div>
      </form>
   </div>
   <div class="columns mobile-hide phone-hide"></div>
   <div class="columns auto mobile-50 phone-80">
      <a href="<?php echo Url::url(Router::$path, 'new/'); ?>" class="wojo small secondary fluid button">
         <i class="icon plus alt"></i><?php echo Language::$word->_MOD_AM_NEW; ?></a>
   </div>
   <div class="columns auto mobile-25">
      <a data-wdropdown="#dropdown-blogMenu" class="wojo small basic primary icon button">
         <i class="icon three dots vertical"></i>
      </a>
      <div class="wojo small dropdown menu top-right" id="dropdown-blogMenu">
         <a class="item" href="<?php echo Url::url(Router::$path, 'settings/'); ?>">
            <i class="icon sliders horizontal"></i>
            <?php echo Language::$word->SETTINGS; ?></a>
         <a class="item" href="<?php echo Url::url(Router::$path, 'categories/'); ?>">
            <i class="icon unordered list"></i>
            <?php echo Language::$word->CATEGORIES; ?></a>
      </div>
   </div>
</div>
<div class="center-align">
   <div class="wojo small divided horizontal list">
      <div class="disabled item text-weight-700">
         <?php echo Language::$word->SORTING_O; ?>
      </div>
      <a href="<?php echo Url::url(Router::$path); ?>" class="item<?php echo Url::setActive('order', false); ?>">
         <?php echo Language::$word->RESET; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, '?order=hits|DESC'); ?>"
         class="item<?php echo Url::setActive('order', 'hits'); ?>">
         <?php echo Language::$word->HITS; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, '?order=category_id|DESC'); ?>"
         class="item<?php echo Url::setActive('order', 'category_id'); ?>">
         <?php echo Language::$word->CATEGORY; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, '?order=memberships|DESC'); ?>"
         class="item<?php echo Url::setActive('order', 'memberships'); ?>">
         <?php echo Language::$word->MEMBERSHIP; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, '?order=active|DESC'); ?>"
         class="item<?php echo Url::setActive('order', 'active'); ?>">
         <?php echo Language::$word->PUBLISHED; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, '?order=title|DESC'); ?>"
         class="item<?php echo Url::setActive('order', 'title'); ?>">
         <?php echo Language::$word->NAME; ?>
      </a>
      <a href="<?php echo Url::sortItems(Url::url(Router::$path), 'order'); ?>" class="item">
         <i class="icon caret <?php echo Url::ascDesc('order'); ?> link"></i>
      </a>
   </div>
</div>
<div class="center-align margin-vertical">
   <?php echo Validator::alphaBits(Url::url(Router::$path)); ?>
</div>
<?php if (!$this->data): ?>
   <div class="center-align">
      <img src="<?php echo ADMINVIEW; ?>images/notfound.svg" alt="" class="wojo big inline image">
      <div class="margin-small-top">
         <p class="wojo small icon alert inverted attached compact message">
            <i class="icon exclamation triangle"></i><?php echo Language::$word->_MOD_PF_NOPROJ; ?></p>
      </div>
   </div>
<?php else: ?>
   <?php foreach ($this->data as $row): ?>
      <div class="wojo framed card" id="item_<?php echo $row->id; ?>">
         <div class="header compact">
            <div class="row gutters">
               <div class="columns auto phone-100 mobile-100">
                  <img src="<?php echo Blog::hasThumb($row->thumb, $row->id); ?>"
                       alt="" class="wojo normal responsive rounded image">
               </div>
               <div class="columns">
                  <div class="padding-horizontal-phone padding-horizontal-mobile">
                     <p class="text-size-small">
                        <a class="grey" href="<?php echo Url::url('admin/modules/blog/category', $row->category_id); ?>"><?php echo $row->name; ?></a>
                     </p>
                     <h5>
                        <a href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>"><?php echo $row->title; ?></a>
                     </h5>
                  </div>
               </div>
            </div>
         </div>
         <div class="footer divided compact">
            <div class="row gutters align-middle">
               <div class="columns">
                  <div class="wojo horizontal small divided list">
                     <div class="item">
                        <?php echo Language::$word->COMMENTS; ?>
                        <span class="description"><?php echo $row->comments; ?></span>
                     </div>
                     <div class="item">
                        <?php echo Language::$word->HITS; ?>
                        <span class="description"><?php echo $row->hits; ?></span>
                     </div>
                     <div class="item">
                        <?php echo Language::$word->LIKES; ?>
                        <span class="description">+<?php echo $row->like_up; ?> -<?php echo $row->like_down; ?></span>
                     </div>
                     <div class="item">
                        <?php echo Language::$word->MEMBERSHIP; ?>
                        <span class="description"><?php echo $row->memberships?: '-/-'; ?></span>
                     </div>
                     <div class="item">
                        <?php echo Language::$word->PUBLISHED; ?>
                        <span class="description"><?php echo Utility::isPublished($row->active); ?></span>
                     </div>
                  </div>
               </div>
               <div class="columns auto center-align phone-100 mobile-100">
                  <a class="wojo primary inverted icon button"
                     href="<?php echo Url::url('admin/modules/blog/edit', $row->id); ?>">
                     <i class="icon pencil"></i>
                  </a>
                  <a data-set='{"option":[{"action": "delete","title": "<?php echo Validator::sanitize($row->title, 'chars'); ?>","id":<?php echo $row->id; ?>, "type":"post"}],"action":"delete","parent":"#item_<?php echo $row->id; ?>","url":"modules/blog/action/"}'
                     class="wojo inverted negative icon button data">
                     <i class="icon trash"></i>
                  </a>
               </div>
            </div>
         </div>
      </div>
   <?php endforeach; ?>
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