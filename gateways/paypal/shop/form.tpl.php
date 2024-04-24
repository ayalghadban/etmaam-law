<?php
   /**
    * form
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: form.tpl.php, v1.00 12/1/2023 3:20 PM Gewa Exp $
    *
    */

   use Wojo\Language\Language;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<?php $url = ($this->gateway->live)? 'www.paypal.com' : 'www.sandbox.paypal.com'; ?>
<form action="https://<?php echo $url; ?>/cgi-bin/webscr" method="post" id="pp_form" name="pp_form" class="center-align">
   <a id="gSubmit" class="wojo white shadow fluid icon button">
      <img class="wojo medium image" src="<?php echo SITEURL . 'gateways/paypal/paypal_logo.svg'; ?>" alt="<?php echo $this->gateway->displayname; ?>">
   </a>
   <input type="hidden" name="cmd" value="_xclick"/>
   <input type="hidden" name="amount" value="<?php echo Utility::numberParse($this->cart->grand); ?>">
   <input type="hidden" name="business" value="<?php echo $this->gateway->extra; ?>">
   <input type="hidden" name="item_name" value="<?php echo $this->core->company . ' - ' . Language::$word->CHECKOUT; ?>">
   <input type="hidden" name="item_number" value="<?php echo $this->auth->uid . '_' . $this->auth->sesid; ?>">
   <input type="hidden" name="return" value="<?php echo Url::url($this->core->system_slugs->account[0]->{'slug' . Language::$lang}, 'shop'); ?>">
   <input type="hidden" name="rm" value="2"/>
   <input type="hidden" name="notify_url" value="<?php echo SITEURL . 'gateways/' . $this->gateway->dir . '/digishop/ipn.php;' ?>">
   <input type="hidden" name="cancel_return" value="<?php echo Url::url($this->core->system_slugs->account[0]->{'slug' . Language::$lang}, 'shop'); ?>">
   <input type="hidden" name="no_note" value="1"/>
   <input type="hidden" name="currency_code" value="<?php echo ($this->gateway->extra2)? : $this->core->currency; ?>">
</form>
<script>
   $('#gSubmit').on('click', function () {
      $('#pp_form').submit();
   });
</script>