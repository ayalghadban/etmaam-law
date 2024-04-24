<?php
   /**
    * profile
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: profile.tpl.php, v1.00 6/30/2023 7:50 AM Gewa Exp $
    *
    */

   use Wojo\Date\Date;
   use Wojo\Language\Language;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<main>
   <div class="padding-big-vertical relative">
      <div class="wojo-grid relative zindex2">
         <h3><?php echo Language::$word->META_T32; ?></h3>
         <div class="row gutters">
            <div class="columns auto mobile-30 phone-100">
               <div class="wojo card center-align">
                  <div class="content">
                     <figure class="wojo medium circular image margin-small-bottom">
                        <img src="<?php echo UPLOADURL; ?>/avatars/<?php echo $this->data->avatar?: 'default.svg'; ?>" alt="">
                     </figure>
                     <p><?php echo Language::$word->M_JOINED; ?>: <?php echo Date::doDate('yyyy', $this->data->created); ?></p>
                  </div>
               </div>
            </div>
            <div class="columns mobile-70 phone-100">
               <div class="padding">
                  <h4><?php echo Language::$word->M_SUB32; ?>
                     <span class="text-color-primary"><?php echo $this->data->fname; ?>
                        <?php echo $this->data->lname; ?></span>
                  </h4>
                  <p class="text-size-small"><?php echo Language::$word->M_LASTSEEN; ?>: <?php echo Date::timesince($this->data->lastlogin); ?></p>
                  <div class="margin-bottom"><?php echo $this->data->info; ?></div>
                  <?php if ($this->custom_fields): ?>
                     <div class="wojo small relaxed celled list margin-bottom">
                        <?php echo $this->custom_fields; ?>
                     </div>
                  <?php endif; ?>
                  <a href="<?php echo $this->data->tw_link; ?>" target="_blank" class="wojo small primary icon button">
                     <i class="twitter icon"></i>
                  </a>
                  <a href="<?php echo $this->data->fb_link; ?>" target="_blank" class="wojo small primary icon button">
                     <i class="facebook icon"></i>
                  </a>
                  <a href="<?php echo $this->data->gp_link; ?>" target="_blank" class="wojo small primary icon button">
                     <i class="instagram icon"></i>
                  </a>
               </div>
            </div>
         </div>
      </div>
      <figure class="absolute zindex1 width-full position-top position-left">
         <svg viewBox="0 0 3000 1000" xmlns="http://www.w3.org/2000/svg">
            <path fill="#eff5f6" d="M-.5-.5v611.1L2999.5-.5z"/>
         </svg>
      </figure>
   </div>
</main>