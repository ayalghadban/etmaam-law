<?php
   /**
    * _user_list
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _user_list.tpl.php, v1.00 5/9/2023 8:52 PM Gewa Exp $
    *
    */

   use Wojo\Auth\Auth;
   use Wojo\Core\Router;
   use Wojo\Date\Date;
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
   <div class="columns auto mobile-50 phone-100">
      <a href="<?php echo Url::url(Router::$path, 'new/'); ?>" class="wojo small secondary fluid button">
         <i class="icon plus alt"></i><?php echo Language::$word->M_TITLE5; ?></a>
   </div>
   <div class="columns auto mobile-25 phone-50">
      <a class="wojo secondary passive inverted small icon button">
         <i class="icon list"></i>
      </a>
      <a href="<?php echo Url::url(Router::$path, 'grid/'); ?>" class="wojo small small primary icon button">
         <i class="icon grid"></i>
      </a>
   </div>
   <div class="columns auto mobile-25 phone-50">
      <a href="<?php echo ADMINURL . 'users/action/?action=exportUsers'; ?>" class="wojo small primary inverted fluid button">
         <i class="icon wysiwyg table"></i> <?php echo Language::$word->EXPORT; ?>
      </a>
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
      <a href="<?php echo Url::url(Router::$path, '?order=membership_id|DESC'); ?>"
         class="item<?php echo Url::setActive('order', 'membership_id'); ?>">
         <?php echo Language::$word->MEMBERSHIP; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, '?order=email|DESC'); ?>"
         class="item<?php echo Url::setActive('order', 'email'); ?>">
         <?php echo Language::$word->M_EMAIL1; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, '?order=fname|DESC'); ?>"
         class="item<?php echo Url::setActive('order', 'fname'); ?>">
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
            <i class="icon exclamation triangle"></i><?php echo Language::$word->M_INFO6; ?></p>
      </div>
   </div>
<?php else: ?>
   <div class="row grid screen-2 tablet-1 mobile-1 phone-1 gutters">
      <?php foreach ($this->data as $row): ?>
         <div class="columns" id="item_<?php echo $row->id; ?>">
            <div class="wojo attached card">
               <div class="divided header">
                  <div class="row horizontal-gutters align-middle">
                     <div class="columns auto">
                        <img src="<?php echo UPLOADURL; ?>avatars/<?php echo $row->avatar ?? 'default.svg'; ?>"
                             alt="" class="wojo small circular image">
                     </div>
                     <div class="columns">
                        <h5>
                           <?php if (Auth::hasPrivileges('edit_user')): ?>
                              <a href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>"><?php echo $row->fullname; ?></a>
                           <?php else: ?>
                              <?php echo $row->fullname; ?>
                           <?php endif; ?>
                        </h5>
                        <?php echo Utility::status($row->active, $row->id); ?>
                        <?php echo Utility::userType($row->type); ?>
                     </div>
                     <div class="columns auto">
                        <a class="wojo icon circular primary inverted button"
                           data-wdropdown="#userDrop_<?php echo $row->id; ?>">
                           <i class="icon three dots"></i>
                        </a>
                        <div class="wojo dropdown small pointing top-right" id="userDrop_<?php echo $row->id; ?>">
                           <?php if (Auth::hasPrivileges('edit_user')): ?>
                              <a class="item" href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>">
                                 <i class="icon pencil"></i><?php echo Language::$word->EDIT; ?>
                              </a>
                           <?php endif; ?>
                           <a class="item" href="<?php echo Url::url(Router::$path, 'history/' . $row->id); ?>">
                              <i class="icon time history"></i><?php echo Language::$word->HISTORY; ?></a>
                           <?php if (Auth::hasPrivileges('delete_user')): ?>
                              <div class="wojo basic divider"></div>
                              <a data-set='{"option":[{"action":"trash","title": "<?php echo Validator::sanitize($row->fullname, 'chars'); ?>","id": "<?php echo $row->id; ?>"}],"action":"trash","parent":"#item_<?php echo $row->id; ?>", "url":"users/action/"}'
                                 class="item text-weight-500 data">
                                 <i class="icon negative trash"></i>
                                 <?php echo Language::$word->TRASH; ?>
                              </a>
                           <?php endif; ?>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="footer">
                  <div class="wojo small divided horizontal block list justify-center">
                     <div class="item">
                        <?php echo Language::$word->M_EMAIL1; ?>:
                        <span class="description">
                           <a href="<?php echo Url::url('admin/mailer', '?email=' . urlencode($row->email)); ?>"><?php echo $row->email; ?></a>
                        </span>
                     </div>
                     <div class="item">
                        <?php echo Language::$word->CREATED; ?>:
                        <span class="description"><?php echo Date::doDate('short_date', $row->created); ?></span>
                     </div>
                  </div>
                  <div class="wojo small divided horizontal block list justify-center">
                     <div class="item">
                        <?php echo Language::$word->MEMBERSHIP; ?>:
                        <span class="description"><?php echo ($row->membership_id)? '<a href="' . Url::url('admin/memberships/edit/' . $row->membership_id) . '">' . $row->mtitle . '</a>' : '-/-'; ?></span>
                     </div>
                     <div class="item">
                        <?php echo Language::$word->MEM_EXP; ?>:
                        <span class="description"><?php echo ($row->mem_expire)? Date::doDate('short_date', $row->mem_expire) : '-/-'; ?></span>
                     </div>
                  </div>
               </div>
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