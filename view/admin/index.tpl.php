<?php
   /**
    * Index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 4/27/2023 11:29 PM Gewa Exp $
    *
    */

   use Wojo\Auth\Auth;
   use Wojo\Date\Date;
   use Wojo\Language\Language;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="row gutters">
   <div class="columns screen-25 tablet-50 mobile-50 phone-100">
      <a href="<?php echo Url::url('admin/users', '?type=registered'); ?>" class="wojo basic attached card">
         <div class="content center-align"><span class="text-color-positive text-size-massive counter"><?php echo $this->data->users; ?></span>
            <p class="text-color-positive"><?php echo Language::$word->AD_RUSER; ?></p>
         </div>
      </a>
   </div>
   <div class="columns screen-25 tablet-50 mobile-50 phone-100">
      <a href="<?php echo Url::url('admin/users', '?type=active'); ?>" class="wojo basic attached card">
         <div class="content center-align"><span class="text-color-secondary text-size-massive counter"><?php echo $this->data->active; ?></span>
            <p class="text-color-secondary"><?php echo Language::$word->AD_AUSER; ?></p>
         </div>
      </a>
   </div>
   <div class="columns screen-25 tablet-50 mobile-50 phone-100">
      <a href="<?php echo Url::url('admin/users', '?type=pending'); ?>" class="wojo basic attached card">
         <div class="content center-align"><span class="text-color-negative text-size-massive counter"><?php echo $this->data->pending; ?></span>
            <p class="text-color-negative"><?php echo Language::$word->AD_PUSER; ?></p>
         </div>
      </a>
   </div>
   <div class="columns screen-25 tablet-50 mobile-50 phone-100">
      <a href="<?php echo Url::url('admin/users', '?type=membership'); ?>" class="wojo basic attached card">
         <div class="content center-align"><span class="text-color-primary text-size-massive counter"><?php echo $this->data->memberships; ?></span>
            <p class="text-color-primary"><?php echo Language::$word->AD_AMEM; ?></p>
         </div>
      </a>
   </div>
</div>
<?php if (Auth::checkAcl('owner')): ?>
   <h5><?php echo Language::$word->AD_TYEAR; ?></h5>
   <div class="row gutters align-bottom">
      <div class="columns screen-80 tablet-70 mobile-100">
         <div class="right-align margin-small-bottom">
            <div id="legend" class="wojo small horizontal list"></div>
         </div>
         <div id="payment_chart" class="wojo simple segment height350"></div>
      </div>
      <div class="columns screen-20 tablet-30 mobile-10">
         <div class="wojo simple segment margin-bottom">
            <p class="text-weight-500" id="totalsum">$0.00</p>
            <div id="chart1" data-values="0,0,0,0,0,0,0,0,0,0,0,0"></div>
         </div>
         <div class="wojo simple segment">
            <p class="text-weight-500" id="totalsales"><span>0</span>
               <?php echo Language::$word->TRX_SALES; ?></p>
            <div id="chart2" data-values="0,0,0,0,0,0,0,0,0,0,0,0"></div>
         </div>
      </div>
   </div>
   <h5><?php echo Language::$word->TRX_POPMEM; ?></h5>
   <div class="right-align margin-small-bottom">
      <div id="legend2" class="wojo small horizontal list"></div>
   </div>
   <div id="payment_chart2" class="wojo simple segment height350 margin-bottom"></div>
   <h5><?php echo Language::$word->AD_MEXP; ?></h5>
   <div class="wojo compact simple segment margin-top">
      <?php echo Date::calendar($this->memberships, true); ?>
   </div>
   <script type="text/javascript" src="<?php echo SITEURL; ?>assets/morris.min.js"></script>
   <script type="text/javascript" src="<?php echo SITEURL; ?>assets/raphael.min.js"></script>
   <script type="text/javascript" src="<?php echo SITEURL; ?>assets/sparkline.min.js"></script>
   <script src="<?php echo ADMINVIEW; ?>js/index.js"></script>
   <script type="text/javascript">
      // <![CDATA[
      $(document).ready(function () {
         $.Index({
            url: "<?php echo ADMINURL;?>",
         });
      });
      // ]]>
   </script>
<?php endif; ?>
