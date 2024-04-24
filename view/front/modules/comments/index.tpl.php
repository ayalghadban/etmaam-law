<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 4/29/2023 9:05 AM Gewa Exp $
    *
    */

   use Wojo\Database\Paginator;
   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Module\Comment\Comment;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   $size = ($screen ?? '60');
   $section = ($this->core->pageslug == $this->segments[0])? 'page' : $this->core->moddir[$this->segments[0]];

   $pager = Paginator::instance();
   $settings = Comment::settings();
   $comments = (new Comment())->render($section, $this->data->id ?? $this->row->id);
?>
<div class="wojo-grid" id="comments">
   <div class="row justify-center">
      <div class="columns screen-<?php echo $size;?> tablet-80 mobile-100 phone-100">
         <h5><?php echo ($pager->items_total)? $pager->items_total . ' ' . Language::$word->COMMENTS : Language::$word->_MOD_CM_SUB; ?></h5>
         <?php echo $comments; ?>
         <div class="center-align padding-small">
            <?php echo $pager->display(); ?>
         </div>
         <?php if ($settings->public_access or $this->auth->logged_in): ?>
            <?php include(BASEPATH . 'view/front/modules/comments/snippets/form.tpl.php'); ?>
         <?php else: ?>
            <?php echo Message::msgSingleAlert(Language::$word->_MOD_CM_SUB1); ?>
         <?php endif; ?>
      </div>
   </div>
</div>