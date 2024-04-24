<?php
   /**
    * sitemap
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: sitemap.tpl.php, v1.00 6/21/2023 8:54 PM Gewa Exp $
    *
    */

   use Wojo\Core\Content;
   use Wojo\File\File;
   use Wojo\Language\Language;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<?php if($this->data->show_header):?>
   <!-- Page Caption & breadcrumbs-->
   <div id="pageCaption"<?php echo Content::pageHeading();?>>
      <div class="wojo-grid">
         <div class="row gutters">
            <div class="columns screen-100 tablet-100 mobile-100 phone-100 center-align">
               <h1><?php echo $this->data->{'title' . Language::$lang};?></h1>
            </div>
            <?php if($this->core->showcrumbs):?>
               <div class="columns screen-100 tablet-100 mobile-100 phone-100 center-align">
                  <div class="wojo breadcrumb">
                     <?php echo Url::crumbs($this->crumbs ?: $this->segments, '/', Language::$word->HOME);?>
                  </div>
               </div>
            <?php endif;?>
         </div>
      </div>
      <figure class="absolute">
         <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" viewBox="126 -26 300 100" width="100%" height="100px">
            <path fill="#FFFFFF" d="M381 3c-33.3 2.7-69.3 39.8-146.6 4.7-44.6-20.3-87.5 14.2-87.5 14.2V74H426V6.8c-11-3.2-25.9-5.3-45-3.8z" opacity=".4"/>
            <path fill="#FFFFFF" d="M384.3 19.9S363.1-.4 314.4 3.7c-33.3 2.7-69.3 39.8-146.6 4.7C153.2 1.8 138.8 1 126 3v71h258.3V19.9z" opacity=".4"/>
            <path fill="#FFFFFF" d="M426 24.4c-19.8-12.8-48.5-25-77.8-15.9-35.2 10.9-64.8 27.4-146.6 4.7-28.1-7.8-54.6-3.5-75.6 3.9V74h300V24.4z"/>
         </svg>
      </figure>
   </div>
<?php endif;?>
<main>
   <?php if (File::is_File(FMODPATH . 'portfolio/_sitemap.tpl.php')): ?>
      <?php include_once FMODPATH . 'portfolio/_sitemap.tpl.php'; ?>
   <?php endif; ?>
   <?php if (File::is_File(FMODPATH . 'digishop/_sitemap.tpl.php')): ?>
      <?php include_once FMODPATH . 'digishop/_sitemap.tpl.php'; ?>
   <?php endif; ?>
   <?php if (File::is_File(FMODPATH . 'blog/_sitemap.tpl.php')): ?>
      <?php include_once FMODPATH . 'blog/_sitemap.tpl.php'; ?>
   <?php endif; ?>
   <?php if (File::is_File(FMODPATH . 'shop/_sitemap.tpl.php')): ?>
      <?php include_once FMODPATH . 'shop/_sitemap.tpl.php'; ?>
   <?php endif; ?>
   <div class="wojo-grid">
      <div class="margin-big-vertical">
         <p><?php echo $this->data->{'caption' . Language::$lang}; ?></p>

         <div class="mason two">
            <?php if ($this->pagedata): ?>
               <div class="items">
                  <h5><?php echo Language::$word->ADM_PAGES; ?> </h5>
                  <!-- Page -->
                  <div class="wojo divided list margin-vertical">
                     <?php foreach ($this->pagedata as $row): ?>
                        <div class="item align-middle">
                           <i class="icon small chevron right"></i>
                           <div class="content">
                              <a href="<?php echo Url::url($this->core->pageslug, $row->slug); ?>"><?php echo $row->title; ?></a>
                           </div>
                        </div>
                     <?php endforeach; ?>
                     <?php unset($row); ?>
                  </div>
               </div>
            <?php endif; ?>

            <?php if ($this->portadata): ?>
               <!-- Portfolio -->
               <div class="items">
                  <h5><?php echo ucfirst($this->core->modname['portfolio']); ?></h5>

                  <div class="wojo divided list margin-vertical">
                     <?php foreach ($this->portadata as $row): ?>
                        <div class="item align-middle">
                           <i class="icon small chevron right"></i>
                           <div class="content">
                              <a href="<?php echo Url::url($this->core->modname['portfolio'], $row->slug); ?>"><?php echo $row->title; ?></a>
                           </div>
                        </div>
                     <?php endforeach; ?>
                     <?php unset($row); ?>
                  </div>
               </div>
            <?php endif; ?>

            <?php if ($this->blogdata): ?>
               <!-- Blog -->
               <div class="items">
                  <h5><?php echo ucfirst($this->core->modname['blog']); ?></h5>
                  <div class="wojo divided list margin-vertical">
                     <?php foreach ($this->blogdata as $row): ?>
                        <div class="item align-middle">
                           <i class="icon small chevron right"></i>
                           <div class="content">
                              <a href="<?php echo Url::url($this->core->modname['blog'], $row->slug); ?>"><?php echo $row->title; ?></a>
                           </div>
                        </div>
                     <?php endforeach; ?>
                     <?php unset($row); ?>
                  </div>
               </div>
            <?php endif; ?>

            <?php if ($this->digidata): ?>
               <!-- Digishop -->
               <div class="items">
                  <h5><?php echo ucfirst($this->core->modname['digishop']); ?></h5>
                  <div class="wojo divided list margin-vertical">
                     <?php foreach ($this->digidata as $row): ?>
                        <div class="item align middle">
                           <i class="icon small chevron right"></i>
                           <div class="content">
                              <a href="<?php echo Url::url($this->core->modname['digishop'], $row->slug); ?>"><?php echo $row->title; ?></a>
                           </div>
                        </div>
                     <?php endforeach; ?>
                     <?php unset($row); ?>
                  </div>
               </div>
            <?php endif; ?>

            <?php if ($this->shopdata): ?>
               <!-- Shop -->
               <div class="items">
                  <h5><?php echo ucfirst($this->core->modname['shop']); ?></h5>
                  <div class="wojo divided list margin-vertical">
                     <?php foreach ($this->shopdata as $row): ?>
                        <div class="item align-middle">
                           <i class="icon small chevron right"></i>
                           <div class="content">
                              <a href="<?php echo Url::url($this->core->modname['shop'], $row->slug); ?>"><?php echo $row->title; ?></a>
                           </div>
                        </div>
                     <?php endforeach; ?>
                     <?php unset($row); ?>
                  </div>
               </div>
            <?php endif; ?>
         </div>
      </div>
   </div>
</main>