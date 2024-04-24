<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 6/13/2023 9:28 AM Gewa Exp $
    *
    */


   use Wojo\Language\Language;
   use Wojo\Module\Gallery\Gallery;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;


   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<!-- Start Gallery -->
<?php if (isset($this->properties['module_id'])): ?>
   <?php if ($rows = Gallery::getGallery($this->properties['id'])): ?>
      <?php if ($data = Gallery::renderSingle($rows->id)): ?>
         <div class="padding-big-vertical">
            <h4><?php echo $rows->title; ?></h4>
            <p><?php echo $rows->description; ?></p>
            <div class="mason <?php echo Utility::numberToWords($rows->cols); ?>">
               <?php foreach ($data as $row): ?>
                  <?php $is_watermark = ($rows->watermark)? SITEURL . 'gallery/action/?action=watermark&dir=' . $rows->dir . '&thumb=' . $row->thumb : FMODULEURL . Gallery::GALDATA . $rows->dir . '/' . $row->thumb; ?>
                  <div class="items">
                     <div class="wojo attached card">
                        <a href="<?php echo $is_watermark; ?>" data-title="<?php echo $row->{'description' . Language::$lang}; ?>" data-gallery="gallery" class="lightbox">
                           <img src="<?php echo FMODULEURL . Gallery::GALDATA . $rows->dir . '/thumbs/' . $row->thumb; ?>" alt="<?php echo $row->{'description' . Language::$lang}; ?>" class="rounded-top">
                        </a>
                        <div class="content">
                           <?php if ($row->{'title' . Language::$lang}): ?>
                              <h5 class="center-align"><?php echo $row->{'title' . Language::$lang}; ?></h5>
                           <?php endif; ?>
                           <?php if ($row->{'description' . Language::$lang}): ?>
                              <p class="text-size-small"><?php echo $row->{'description' . Language::$lang}; ?></p>
                           <?php endif; ?>
                        </div>
                        <?php if ($rows->likes): ?>
                           <div class="footer" data-gallery-like="<?php echo $row->id; ?>" data-gallery-total="<?php echo $row->likes; ?>">
                              <div class="row align-middle">
                                 <div class="columns">
                                    <span class="galleryTotal"><?php echo $row->likes; ?></span>
                                    <?php echo Language::$word->LIKES; ?></div>
                                 <div class="columns right-align">
                                    <a class="wojo small primary button galleryLike">
                                       <i class="icon hand thumbs up"></i>
                                       <?php echo Language::$word->LIKE; ?>
                                    </a>
                                 </div>
                              </div>
                           </div>
                        <?php endif; ?>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         </div>
         <?php unset($row); ?>
      <?php endif; ?>
   <?php endif; ?>
<?php else: ?>
   <!-- Start Galleries -->
   <?php switch (Url::segment($this->segments, 1)): case $this->core->modname['gallery-album']: ?>
      <!-- Start photos -->
      <?php if ($this->photos): ?>
         <div class="padding-big-vertical">
            <h4><?php echo $this->row->{'title' . Language::$lang}; ?></h4>
            <p><?php echo $this->row->{'description' . Language::$lang}; ?></p>
            <div class="mason <?php echo Utility::numberToWords($this->row->cols); ?>">
               <?php foreach ($this->photos as $row): ?>
                  <?php $is_watermark = ($this->row->watermark)? SITEURL . 'gallery/action/?action=watermark&dir=' . $this->row->dir . '&thumb=' . $row->thumb : FMODULEURL . Gallery::GALDATA . $this->row->dir . '/' . $row->thumb; ?>
                  <div class="items">
                     <div class="wojo attached card">
                        <a href="<?php echo $is_watermark; ?>" data-title="<?php echo $row->{'description' . Language::$lang}; ?>" data-gallery="gallery" class="lightbox wojo zoom image">
                           <img src="<?php echo FMODULEURL . Gallery::GALDATA . $this->row->dir . '/thumbs/' . $row->thumb; ?>" alt="<?php echo $row->{'title' . Language::$lang}; ?>" class="rounded-top">
                        </a>
                        <div class="content">
                           <h6 class="center-align"><?php echo $row->{'title' . Language::$lang}; ?></h6>
                           <?php if ($row->{'description' . Language::$lang}): ?>
                              <p class="text-size-small">
                                 <?php echo $row->{'description' . Language::$lang}; ?>
                              </p>
                           <?php endif; ?>
                        </div>
                        <?php if ($this->row->likes): ?>
                           <div class="footer" data-gallery-like="<?php echo $row->id; ?>" data-gallery-total="<?php echo $row->likes; ?>">
                              <div class="row align-middle">
                                 <div class="columns">
                                    <span class="galleryTotal"><?php echo $row->likes; ?></span>
                                    <?php echo Language::$word->LIKES; ?></div>
                                 <div class="columns right-align">
                                    <a class="wojo small primary button galleryLike">
                                       <i class="icon hand thumbs up"></i>
                                       <?php echo Language::$word->LIKE; ?>
                                    </a>
                                 </div>
                              </div>
                           </div>
                        <?php endif; ?>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         </div>
      <?php endif; ?>
      <?php break; ?>
      <!-- Start default -->
   <?php default: ?>
      <?php if ($this->rows): ?>
         <div class="padding-big-vertical">
            <div class="mason two">
               <?php foreach ($this->rows as $row): ?>
                  <div class="items">
                     <figure class="wojo hover rounded">
                        <img src="<?php echo $row->poster? FMODULEURL . 'gallery/data/' . $row->dir . '/thumbs/' . $row->poster : UPLOADURL . '/blank.jpg'; ?>" class="wojo rounded" alt="<?php echo $row->poster; ?>">
                        <figcaption class="center-align rounded">
                           <?php if ($row->{'title' . Language::$lang}): ?>
                              <h5><?php echo $row->{'title' . Language::$lang}; ?></h5>
                           <?php endif; ?>
                           <?php if ($row->{'description' . Language::$lang}): ?>
                              <p class="text-size-small"><?php echo $row->{'description' . Language::$lang}; ?></p>
                           <?php endif; ?>
                           <p>
                              <a href="<?php echo Url::url($this->core->modname['gallery'] . '/' . $this->core->modname['gallery-album'], $row->{'slug' . Language::$lang}); ?>" class="wojo icon primary button">
                                 <i class="icon link alt"></i>
                              </a>
                           </p>
                           <div class="wojo mini statistics align-center">
                              <div class="statistic">
                                 <div class="value">
                                    <i class="icon hand thumbs up"></i>
                                    <?php echo $row->likes; ?>
                                 </div>
                                 <div class="label">
                                    <?php echo Language::$word->LIKES; ?>
                                 </div>
                              </div>
                              <div class="statistic">
                                 <div class="value">
                                    <i class="icon image"></i>
                                    <?php echo $row->pics; ?>
                                 </div>
                                 <div class="label">
                                    <?php echo Language::$word->_MOD_GA_PHOTOS; ?>
                                 </div>
                              </div>
                           </div>
                        </figcaption>
                     </figure>
                  </div>
               <?php endforeach; ?>
            </div>
         </div>
      <?php endif; ?>
      <?php break; ?>
   <?php endswitch; ?>
<?php endif; ?>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $('.mason').on('click', '.galleryLike', function () {
         let id = $(this).closest('.footer').attr('data-gallery-like');
         let total = $(this).closest('.footer').attr('data-gallery-total');
         let score = $(this).closest('.footer').find('.galleryTotal');
         score.html(parseInt(total) + 1);
         $(this).transition('scaleIn', {
            duration: 1000,
            complete: function () {
               $(this).children('.icon').removeClass('hand thumbs up').addClass('check');
               $(this).removeClass('galleryLike').addClass('passive');
               $.post("<?php echo SITEURL . 'gallery/action/';?>", {
                  action: 'like',
                  id: id
               });
            }
         });
      });
   });
   // ]]>
</script>