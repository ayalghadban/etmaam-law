<?php
   /**
    * loadComment
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: loadComment.tpl.php, v1.00 4/29/2023 9:05 AM Gewa Exp $
    *
    */

   use Wojo\Date\Date;
   use Wojo\Language\Language;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   if ($this->data->uname) {
      $user = '<span class="author">' . $this->data->uname . '</span>';
      $avatar = '<div class="wojo image avatar"><img src="' . UPLOADURL . 'avatars/default.svg" alt=""></div>';
   } else {
      $profile = Url::url($this->core->system_slugs->profile[0]->{'slug' . Language::$lang}, $this->data->username);
      $user = '<a href="' . $profile . '" class="author">' . $this->data->name . '</a>';
      $avatar = '<a href="' . $profile . '" class="wojo image avatar"><img src="' . UPLOADURL . 'avatars/' . ($this->data->avatar?: 'default.svg') . '" alt=""></a>';
   }
?>
<div id="comment_<?php echo $this->data->id; ?>" data-id="<?php echo $this->data->id; ?>" class="comment"> <?php echo $avatar; ?>
   <div class="content"> <?php echo $user; ?>
      <div class="metadata">
         <span class="date"><?php echo ($this->settings->timesince)? Date::timesince($this->data->created) : Date::doDate($this->settings->dateformat, $this->data->created); ?></span>
         <?php if ($this->auth->is_Admin()): ?>
            <a class="delete">
               <i class="icon x alt"></i>
            </a>
         <?php endif; ?>
      </div>
      <div class="text"><?php echo $this->data->body; ?></div>
      <div class="wojo horizontal divided list actions">
         <?php if ($this->settings->rating): ?>
            <a class="item up" data-id="<?php echo $this->data->id; ?>" data-up="<?php echo $this->data->vote_up; ?>">
               <span class="text-color-positive"><?php echo $this->data->vote_up; ?></span>
               <i class="icon caret up"></i>
            </a>
            <a class="item down" data-id="<?php echo $this->data->id; ?>" data-down="<?php echo $this->data->vote_down; ?>">
               <span class="text-color-negative"><?php echo $this->data->vote_down; ?></span>
               <i class="icon caret down"></i>
            </a>
         <?php endif; ?>
         <?php if ($this->data->comment_id == 0): ?>
            <a data-id="<?php echo $this->data->id; ?>" class="item replay"><?php echo Language::$word->_MOD_CM_REPLAY; ?></a>
         <?php endif; ?>
      </div>
   </div>
</div>