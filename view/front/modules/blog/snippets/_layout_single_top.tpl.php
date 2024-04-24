<?php
   /**
    * _layout_single_top
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _layout_single_top.tpl.php, v1.00 10/20/2023 2:26 PM Gewa Exp $
    *
    */

   use Wojo\Date\Date;
   use Wojo\Language\Language;
   use Wojo\Module\Blog\Blog;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   $screen = 100;
?>
<?php if (Blog::getMembershipAccess($this->row->membership_id)): ?>
   <!-- Multiple Images-->
   <?php if ($this->images): ?>
      <div class="wojo carousel inside margin-bottom" data-wcarousel='{"items":1,"nav":false,"dots":true}'>
         <a href="<?php echo FMODULEURL . Blog::BLOGDATA . $this->row->id . '/' . $this->row->thumb; ?>" data-gallery="blog" class="lightbox">
            <img src="<?php echo Blog::hasThumb($this->row->thumb, $this->row->id); ?>" alt="">
         </a>
         <?php foreach ($this->images as $img): ?>
            <a href="<?php echo FMODULEURL . Blog::BLOGDATA . $this->row->id . '/' . $img->name; ?>" data-gallery="blog" class="lightbox">
               <img src="<?php echo Blog::hasThumb($img->name, $this->row->id); ?>" alt="<?php echo $this->row->{'title' . Language::$lang}; ?>" class="wojo image">
            </a>
         <?php endforeach; ?>
      </div>
   <?php else: ?>
      <figure class="wojo rounded image margin-bottom">
         <img src="<?php echo Blog::hasImage($this->row->thumb, $this->row->id); ?>" data-title="<?php echo $this->row->caption; ?>" alt="<?php echo $this->row->{'title' . Language::$lang}; ?>">
      </figure>
   <?php endif; ?>
   <div class="wojo horizontal divided list align-middle">
      <?php if ($this->row->show_author): ?>
         <div class="item">
            <span class="text-weight-500"><?php echo Language::$word->BY . ':'; ?></span>
            <a href="<?php echo Url::url($this->core->system_slugs->profile[0]->{'slug' . Language::$lang}, $this->row->username); ?>" class="description"><?php echo $this->row->user; ?></a>
         </div>
      <?php endif; ?>
      <div class="item">
         <span class="text-weight-500"><?php echo Language::$word->IN . ':'; ?></span>
         <a href="<?php echo Url::url($this->core->modname['blog'] . '/' . $this->core->modname['blog-cat'], $this->row->catslug); ?>" class="description"><?php echo $this->row->catname; ?></a>
      </div>
      <div class="item">
         <span class="text-weight-500"><?php echo Language::$word->_MOD_AM_SUB44 . ':'; ?></span>
         <?php echo $this->row->hits; ?>
      </div>
      <?php if ($this->row->file): ?>
         <div class="item">
            <a href="<?php echo FMODULEURL; ?>blog/datafiles/<?php echo $this->row->file; ?>" class="description">
               <i class="icon download"></i>
            </a>
         </div>
      <?php endif; ?>
      <?php if ($this->row->show_like): ?>
         <div class="item">
            <a class="blogLike description" data-id="<?php echo $this->row->id; ?>" data-url="<?php echo FMODULEURL; ?>" data-vote="up">
               <i class="icon hand thumbs up"></i>
               <small>(<?php echo $this->row->like_up; ?>)</small>
            </a>
         </div>
         <div class="item">
            <a class="blogLike description" data-id="<?php echo $this->row->id; ?>" data-url="<?php echo FMODULEURL; ?>" data-vote="down">
               <i class="icon hand thumbs down"></i>
               <small>(<?php echo $this->row->like_down; ?>)</small>
            </a>
         </div>
      <?php endif; ?>
      <?php if ($this->row->show_created): ?>
         <div class="item">
            <span class="text-weight-500"><?php echo Date::doDate('short_date', $this->row->created); ?></span>
         </div>
      <?php endif; ?>
   </div>
   <h3 class="margin-vertical"><?php echo $this->row->{'title' . Language::$lang}; ?></h3>
   <?php echo Url::out_url($this->row->{'body' . Language::$lang}); ?>
   <?php if ($this->row->show_sharing): ?>
      <!--Social Sharing-->
      <div class="item">
         <div class="content auto">
            <a target="_blank" data-content="<?php echo Language::$word->_MOD_AM_SUB45; ?> Facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo Url::url($this->core->modname['blog'], $this->row->slug); ?>" class="wojo small secondary icon button">
               <i class="icon facebook"></i>
            </a>
            <a data-content="<?php echo Language::$word->_MOD_AM_SUB45; ?> Twitter" href="https://twitter.com/home?status=<?php echo Url::url($this->core->modname['blog'], $this->row->slug); ?>" class="wojo small secondary icon button">
               <i class="icon x_twitter"></i>
            </a>
            <a target="_blank" data-content="<?php echo Language::$word->_MOD_AM_SUB45; ?> LinkedIn" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo Url::url($this->core->modname['blog'], $this->row->slug); ?>" class="wojo small secondary icon button">
               <i class="icon linkedin alt"></i>
            </a>
            <a target="_blank" data-content="<?php echo Language::$word->_MOD_AM_SUB45; ?> Pinterest" href="https://pinterest.com/pin/create/button/?url=&amp;media=<?php echo Url::url($this->core->modname['blog'], $this->row->slug); ?>" class="wojo small secondary icon button">
               <i class="icon pinterest"></i>
            </a>
         </div>
      </div>
   <?php endif; ?>
   <?php if ($this->row->{'tags' . Language::$lang}): ?>
      <!--Tags-->
      <?php $tags = explode(',', $this->row->{'tags' . Language::$lang}); ?>
      <div class="wojo horizontal list margin-vertical">
         <div class="item"><?php echo Language::$word->_MOD_AM_SUB79; ?>:</div>
         <?php foreach ($tags as $tag): ?>
            <div class="item">
               <a href="<?php echo Url::url('/' . $this->core->modname['blog'] . '/' . $this->core->modname['blog-tag'], $tag); ?>" class="wojo primary label"><?php echo $tag; ?></a>
            </div>
         <?php endforeach; ?>
      </div>
   <?php endif; ?>
   <?php if ($this->row->show_comments): ?>
      <?php include_once(BASEPATH . 'view/front/modules/comments/index.tpl.php'); ?>
   <?php endif; ?>
<?php endif; ?>