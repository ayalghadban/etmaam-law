<?php
   /**
    * _user_grid
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _user_grid.tpl.php, v1.00 5/9/2023 8:52 PM Gewa Exp $
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
      <a href="<?php echo Url::url('admin/users', 'new/'); ?>" class="wojo small secondary fluid button">
         <i class="icon plus alt"></i><?php echo Language::$word->M_TITLE5; ?></a>
   </div>
   <div class="columns auto mobile-25 phone-50">
      <a href="<?php echo Url::url('admin/users'); ?>" class="wojo small primary icon button">
         <i class="icon list"></i>
      </a>
      <a class="wojo secondary passive inverted small icon button">
         <i class="icon grid"></i>
      </a>
   </div>
   <div class="columns auto mobile-25 phone-50">
      <a href="<?php echo ADMINURL . 'users/action/?action=exportUsers'; ?>"
         class="wojo small primary inverted fluid button">
         <i class="icon wysiwyg table"></i><?php echo Language::$word->EXPORT; ?></a>
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
   <div class="mason">
      <?php foreach ($this->data as $row): ?>
         <div class="columns" id="item_<?php echo $row->id; ?>">
            <div class="wojo card attached">
               <div class="header">
                  <div class="row align-middle">
                     <div class="columns">
                        <?php if (Auth::hasPrivileges('edit_user')): ?>
                           <a class="grey" href="<?php echo Url::url('admin/users/edit', $row->id . '/'); ?>"><span
                                class="text-weight-500"><?php echo $row->fullname; ?></span>
                           </a>
                        <?php else: ?>
                           <?php echo $row->fullname; ?>
                        <?php endif; ?>
                     </div>
                     <div class="columns auto">
                        <a data-wdropdown="#userDrop_<?php echo $row->id; ?>"
                           class="wojo small primary inverted icon circular button">
                           <i class="icon three dots"></i>
                        </a>
                        <div class="wojo dropdown small pointing top-right" id="userDrop_<?php echo $row->id; ?>">
                           <a class="item" href="<?php echo Url::url('admin/users/edit', $row->id . '/'); ?>">
                              <i class="icon pencil"></i>
                              <?php echo Language::$word->EDIT; ?></a>
                           <a class="item" href="<?php echo Url::url('admin/users/history', $row->id . '/'); ?>">
                              <i class="icon time history"></i>
                              <?php echo Language::$word->HISTORY; ?></a>
                           <?php if (Auth::hasPrivileges('delete_user')): ?>
                              <div class="divider"></div>
                              <a data-set='{"option":[{"action": "trash","title": "<?php echo Validator::sanitize($row->fullname, 'chars'); ?>","id":<?php echo $row->id; ?>}],"action":"trash","subtext":"<?php echo Language::$word->DELCONFIRM3; ?>", "parent":"#item_<?php echo $row->id; ?>", "url":"users/action/"}'
                                 class="item wojo text-weight-500 data">
                                 <i class="icon negative trash"></i><?php echo Language::$word->TRASH; ?>
                              </a>
                           <?php endif; ?>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="content">
                  <div class="center-align margin-bottom">
                     <a href="<?php echo Url::url('admin/users/edit' . $row->id . '/'); ?>">
                        <img src="<?php echo UPLOADURL; ?>avatars/<?php echo $row->avatar ?? 'default.svg'; ?>" alt=""
                             class="wojo basic circular normal inline image">
                     </a>
                  </div>
                  <div class="row align-middle">
                     <div class="columns">
                        <span class="wojo small text"><?php echo Date::doDate('short_date', $row->created); ?></span>
                     </div>
                     <div class="columns"><?php echo Utility::userType($row->type); ?></div>
                     <div class="columns auto"><?php echo Utility::status($row->active, $row->id); ?>
                     </div>
                  </div>
               </div>
               <div class="footer divided">
                  <div class="wojo small list">
                     <div class="item"><?php echo Language::$word->M_EMAIL; ?>:
                        <div class="description">
                           <a href="<?php echo Url::url('admin/mailer', '?email=' . urlencode($row->email)); ?>"><?php echo $row->email; ?></a>
                        </div>
                     </div>
                     <div class="item"><?php echo Language::$word->MEMBERSHIP; ?>:
                        <div class="description"><?php echo ($row->membership_id)? '<a href="' . Url::url('admin/memberships/' . $row->membership_id) . '">' . $row->mtitle . '</a> @' . Date::doDate('short_date', $row->mem_expire) : '-/-'; ?></div>
                     </div>
                     <div class="item">ip:
                        <span class="description"><?php echo $row->lastip; ?></span>
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