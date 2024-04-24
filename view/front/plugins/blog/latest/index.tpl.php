<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 6/14/2023 11:50 AM Gewa Exp $
    *
    */

   use Wojo\Date\Date;
   use Wojo\Language\Language;
   use Wojo\Module\Blog\Blog;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<!-- Blog Latest -->
<?php $setting = Utility::findInArray($this->properties['all'], 'id', $this->properties['id']); ?>
<div class="wojo small segment<?php echo ($setting[0]->alt_class)? ' ' . $setting[0]->alt_class : null; ?>">
   <div class="scrollbox height600">
      <?php if ($setting[0]->show_title): ?>
         <h5 class="center-align"><?php echo $setting[0]->title; ?></h5>
      <?php endif; ?>
      <?php if ($setting[0]->body): ?>
         <?php echo Url::out_url($setting[0]->body); ?>
      <?php endif; ?>
      <?php if ($data = (new Blog)->LatestPlugin()): ?>
         <div class="wojo very relaxed list">
            <?php foreach ($data as $row): ?>
               <div class="item">
                  <div class="content">
                     <figure class="margin-small-bottom">
                        <img class="wojo rounded image" src="<?php echo Blog::hasThumb($row->thumb, $row->id); ?>" alt="<?php echo $row->title; ?>">
                     </figure>
                     <h6>
                        <a href="<?php echo Url::url($this->core->modname['blog'], $row->slug); ?>"><?php echo $row->title; ?></a>
                     </h6>
                     <p class="text-size-small"><?php echo Date::doDate('long_date', $row->created); ?></p>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>
         <a href="<?php echo Url::url($this->core->modname['blog']); ?>" class="wojo fluid primary button">
            <?php echo Language::$word->_MOD_AM_SUB40; ?></a>
      <?php endif; ?>
   </div>
</div>