<?php
   /**
    * form
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: form.tpl.php, v1.00 6/27/2023 1:50 PM Gewa Exp $
    *
    */

   use Wojo\Language\Language;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<form action="https://www.skrill.com/app/payment.pl" method="post" id="mb_form" name="mb_form" class="center-align">
   <a id="gSubmit" class="wojo white fluid shadow icon button">
      <img class="wojo medium image" src="<?php echo SITEURL . 'gateways/skrill/skrill_logo.svg'; ?>" alt="<?php echo $this->gateway->displayname; ?>">
   </a>
   <input type="hidden" name="pay_to_email" value="<?php echo $this->gateway->extra; ?>">
   <input type="hidden" name="return_url" value="<?php echo Url::url($this->core->system_slugs->account[0]->{'slug' . Language::$lang}, 'digishop'); ?>">
   <input type="hidden" name="cancel_url" value="<?php echo Url::url($this->core->system_slugs->account[0]->{'slug' . Language::$lang}, 'digishop'); ?>">
   <input type="hidden" name="status_url" value="<?php echo SITEURL . 'gateways/' . $this->gateway->dir; ?>/digishop/ipn.php"/>
   <input type="hidden" name="merchant_fields" value="session_id, item, custom"/>
   <input type="hidden" name="item" value="<?php echo $this->core->company . ' - ' . Language::$word->CHECKOUT; ?>"/>
   <input type="hidden" name="session_id" value="<?php echo md5(time()) ?>"/>
   <input type="hidden" name="custom" value="<?php echo $this->auth->uid . '_' . $this->auth->sesid; ?>"/>
   <input type="hidden" name="amount" value="<?php echo Utility::formatNumber($this->cart->grand); ?>"/>
   <input type="hidden" name="currency" value="<?php echo ($this->gateway->extra2)?: $this->core->currency; ?>"/>
</form>
<script>
   $('#gSubmit').on('click', function () {
      $('#mb_form').submit();
   });
</script>