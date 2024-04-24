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
   use Wojo\Module\Blog\Blog;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;
   use Wojo\Validator\Validator;

?>
<!-- Blog Latest -->
<?php $setting = Utility::findInArray($this->properties['all'], 'id', $this->properties['id']); ?>
<div class="wojo plugin<?php echo ($setting[0]->alt_class)? ' ' . $setting[0]->alt_class : null; ?>">
   <?php if ($setting[0]->body): ?>
      <?php echo Url::out_url($setting[0]->body); ?>
   <?php endif; ?>
   <?php if ($data = (new Blog)->LatestPlugin()): ?>
      <div class="wojo carousel" data-wcarousel='{"stageClass": "owl-stage flex","margin":32,"items":2,"loop":false,"nav":false,"dots":true,"responsive": {"0": {"items": 1},"769": {"items": 3},"1024": {"items": 4}}}'>
         <?php foreach ($data as $row): ?>
            <div class="wojo full attached simple card">
               <div class="content">
                  <span class="text-size-mini dimmed-text-more"><?php echo Date::doDate('short_date', $row->created); ?></span>
                  <h5 class="vertical margin">
                     <a href="<?php echo Url::url($this->core->modname['blog'], $row->slug); ?>" class="black"><?php echo $row->title; ?></a>
                  </h5>
                  <p class="text-size-small"><?php echo Validator::sanitize($row->body, 'default', 50); ?></p>
               </div>
               <div class="margin-horizontal">
                  <div class="wojo basic divider"></div>
               </div>
               <div class="footer">
                  <a href="<?php echo Url::url('/profile', $row->username); ?>" class="black">
                     <img src="<?php echo UPLOADURL; ?>/avatars/<?php echo $row->avatar? : 'blank.png'; ?>" alt="" class="wojo mini inline circular image">
                     <span class="wojo small text left margin"><?php echo $row->name; ?></span>
                  </a>
               </div>
            </div>
         <?php endforeach; ?>
      </div>
   <?php endif; ?>
</div>