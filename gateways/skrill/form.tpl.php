<?php
   /**
    * form
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: form.tpl.php, v1.00 6/25/2023 2:46 PM Gewa Exp $
    *
    */

   use Wojo\Core\Membership;
   use Wojo\Language\Language;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<form action="https://www.skrill.com/app/payment.pl" method="post" id="mb_form" name="mb_form" class="center-align">
   <a id="gSubmit" class="wojo white shadow icon button">
      <img class="wojo medium image" src="<?php echo SITEURL . 'gateways/skrill/skrill_logo.svg'; ?>" alt="<?php echo $this->gateway->displayname; ?>">
   </a>
   <input type="hidden" name="pay_to_email" value="<?php echo $this->gateway->extra; ?>">
   <input type="hidden" name="return_url" value="<?php echo Url::url($this->core->system_slugs->account[0]->{'slug' . Language::$lang}, 'history'); ?>">
   <input type="hidden" name="cancel_url" value="<?php echo Url::url($this->core->system_slugs->account[0]->{'slug' . Language::$lang}, 'history'); ?>">
   <input type="hidden" name="status_url" value="<?php echo SITEURL . 'gateways/' . $this->gateway->dir; ?>/ipn.php"/>
   <input type="hidden" name="merchant_fields" value="session_id, item, custom"/>
   <input type="hidden" name="item" value="<?php echo $this->row->{'title' . Language::$lang}; ?>"/>
   <input type="hidden" name="session_id" value="<?php echo md5(time()) ?>"/>
   <input type="hidden" name="custom" value="<?php echo $this->row->id . '_' . $this->auth->uid; ?>"/>
   <?php if ($this->row->recurring == 1): ?>
      <input type="hidden" name="rec_amount" value="<?php echo $this->cart->totalprice; ?>"/>
      <input type="hidden" name="rec_period" value="<?php echo Membership::calculateDays($this->row->id); ?>"/>
      <input type="hidden" name="rec_cycle" value="day"/>
   <?php else: ?>
      <input type="hidden" name="amount" value="<?php echo $this->cart->totalprice; ?>"/>
   <?php endif; ?>
   <input type="hidden" name="currency" value="<?php echo ($this->gateway->extra2)?: $this->core->currency; ?>"/>
   <input type="hidden" name="detail1_description" value="<?php echo $this->row->{'title' . Language::$lang}; ?>"/>
   <input type="hidden" name="detail1_text" value="<?php echo $this->row->{'description' . Language::$lang}; ?>"/>
</form>
<script>
   $('#gSubmit').on('click', function () {
      $('#mb_form').submit();
   });
</script>