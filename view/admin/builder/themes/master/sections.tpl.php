<?php
   /**
    * sections
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: sections.tpl.php, v1.00 6/1/2023 10:19 AM Gewa Exp $
    *
    */

   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="wojo small form">
   <select id="blockFilter" class="small">
      <option value="all" selected="selected">All Blocks</option>
      <option value="headers">Headers</option>
      <option value="sections">Content Sections</option>
      <option value="iblocks">Icon Blocks</option>
      <option value="info">Info Blocks</option>
      <option value="testimonials">Testimonials</option>
      <option value="ptables">Pricing Tables</option>
      <option value="cta">Call to Action</option>
      <option value="media">Media Blocks</option>
   </select>
</div>
<div id="builder-elements" class="grid-blocks">
   <div class="item">
      <a data-element="section" data-type="headers" data-html="elements/header_1">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/header_1.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="headers" data-html="elements/header_2">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/header_2.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="headers" data-html="elements/header_3">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/header_3.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="headers" data-html="elements/header_4">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/header_4.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="headers" data-html="elements/header_5">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/header_5.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="headers" data-html="elements/header_6">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/header_6.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="headers" data-html="elements/header_7">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/header_7.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="headers" data-html="elements/header_8">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/header_8.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="headers" data-html="elements/header_9">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/header_9.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="headers" data-html="elements/header_10">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/header_10.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="headers" data-html="elements/header_11">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/header_11.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="headers" data-html="elements/header_12">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/header_12.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="headers" data-html="elements/header_13">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/header_13.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="headers" data-html="elements/header_14">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/header_14.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="headers" data-html="elements/header_15">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/header_15.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="headers" data-html="elements/header_16">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/header_16.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="headers" data-html="elements/header_17">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/header_17.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="sections" data-html="elements/content_1">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/content_1.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="sections" data-html="elements/content_2">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/content_2.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="sections" data-html="elements/content_3">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/content_3.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="sections" data-html="elements/content_4">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/content_4.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="sections" data-html="elements/content_5">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/content_5.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="sections" data-html="elements/content_6">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/content_6.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="sections" data-html="elements/content_7">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/content_7.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="sections" data-html="elements/content_8">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/content_8.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="sections" data-html="elements/content_9">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/content_9.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="sections" data-html="elements/content_10">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/content_10.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="sections" data-html="elements/content_11">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/content_11.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="sections" data-html="elements/content_12">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/content_12.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="sections" data-html="elements/content_13">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/content_13.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="sections" data-html="elements/content_14">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/content_14.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="sections" data-html="elements/content_15">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/content_15.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="sections" data-html="elements/content_16">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/content_16.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="sections" data-html="elements/content_17">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/content_17.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="sections" data-html="elements/content_18">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/content_18.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="sections" data-html="elements/content_19">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/content_19.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="sections" data-html="elements/content_20">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/content_20.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="sections" data-html="elements/content_21">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/content_21.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="sections" data-html="elements/content_22">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/content_22.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="iblocks" data-html="elements/iblocks_1">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/iblocks_1.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="iblocks" data-html="elements/iblocks_2">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/iblocks_2.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="iblocks" data-html="elements/iblocks_3">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/iblocks_3.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="iblocks" data-html="elements/iblocks_4">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/iblocks_4.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="iblocks" data-html="elements/iblocks_5">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/iblocks_5.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="iblocks" data-html="elements/iblocks_6">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/iblocks_6.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="iblocks" data-html="elements/iblocks_7">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/iblocks_7.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="iblocks" data-html="elements/iblocks_8">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/iblocks_8.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="iblocks" data-html="elements/iblocks_9">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/iblocks_9.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="iblocks" data-html="elements/iblocks_10">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/iblocks_10.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="info" data-html="elements/info_1">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/info_1.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="info" data-html="elements/info_2">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/info_2.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="info" data-html="elements/info_3">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/info_3.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="info" data-html="elements/info_4">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/info_4.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="info" data-html="elements/info_5">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/info_5.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="info" data-html="elements/info_6">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/info_6.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="info" data-html="elements/info_7">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/info_7.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="info" data-html="elements/info_8">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/info_8.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="info" data-html="elements/info_9">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/info_9.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="info" data-html="elements/info_10">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/info_10.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="testimonials" data-html="elements/testimonials_1">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/testimonials_1.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="testimonials" data-html="elements/testimonials_2">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/testimonials_2.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="testimonials" data-html="elements/testimonials_3">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/testimonials_3.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="testimonials" data-html="elements/testimonials_4">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/testimonials_4.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="testimonials" data-html="elements/testimonials_5">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/testimonials_5.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="testimonials" data-html="elements/testimonials_6">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/testimonials_6.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="testimonials" data-html="elements/testimonials_7">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/testimonials_7.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="testimonials" data-html="elements/testimonials_8">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/testimonials_8.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="ptables" data-html="elements/ptables_1">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/ptables_1.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="ptables" data-html="elements/ptables_2">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/ptables_2.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="ptables" data-html="elements/ptables_3">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/ptables_3.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="ptables" data-html="elements/ptables_4">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/ptables_4.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="ptables" data-html="elements/ptables_5">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/ptables_5.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="ptables" data-html="elements/ptables_6">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/ptables_6.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="ptables" data-html="elements/ptables_7">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/ptables_7.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="cta" data-html="elements/cta_1">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/cta_1.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="cta" data-html="elements/cta_2">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/cta_2.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="cta" data-html="elements/cta_3">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/cta_3.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="cta" data-html="elements/cta_4">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/cta_4.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="cta" data-html="elements/cta_5">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/cta_5.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="cta" data-html="elements/cta_6">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/cta_6.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="cta" data-html="elements/cta_7">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/cta_7.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="media" data-html="elements/media_1">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/media_1.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="media" data-html="elements/media_2">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/media_2.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="media" data-html="elements/media_3">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/media_3.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="media" data-html="elements/media_4">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/media_4.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="media" data-html="elements/media_5">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/media_5.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="media" data-html="elements/media_6">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/media_6.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="media" data-html="elements/media_7">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/media_7.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="media" data-html="elements/media_8">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/media_8.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="media" data-html="elements/media_9">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/media_9.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="media" data-html="elements/media_10">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/media_10.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="media" data-html="elements/media_11">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/media_11.jpg" alt="">
      </a>
   </div>
   <div class="item">
      <a data-element="section" data-type="media" data-html="elements/media_12">
         <img src="<?php echo Url::builderUrl($this->core->theme); ?>/thumbs/media_12.jpg" alt="">
      </a>
   </div>
</div>
