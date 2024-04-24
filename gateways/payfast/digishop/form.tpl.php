<?php
   /**
    * form
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: form.tpl.php, v1.00 6/27/2023 1:46 PM Gewa Exp $
    *
    */

   use Wojo\Language\Language;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<?php $url = ($this->gateway->live)? 'www.payfast.co.za' : 'sandbox.payfast.co.za'; ?>
<form action="https://<?php echo $url; ?>/eng/process" class="center aligned" method="post" id="pf_form" name="pf_form">
   <a id="gSubmit" class="wojo white fluid shadow icon button">
      <img class="wojo medium image" src="<?php echo SITEURL . 'gateways/payfast/payfast_logo.svg'; ?>" alt="<?php echo $this->gateway->displayname; ?>">
   </a>
   <?php
      $html = '';
      $string = '';

      $array = array(
        'merchant_id' => $this->gateway->extra,
        'merchant_key' => $this->gateway->extra2,
        'return_url' => Url::url($this->core->system_slugs->account[0]->{'slug' . Language::$lang}, 'digishop'),
        'cancel_url' => Url::url($this->core->system_slugs->account[0]->{'slug' . Language::$lang}, 'digishop'),
        'notify_url' => SITEURL . 'gateways/' . $this->gateway->dir . '/digishop/ipn.php',
        'name_first' => $this->auth->fname,
        'name_last' => $this->auth->lname,
        'email_address' => $this->auth->email,
        'm_payment_id' => time(),
        'amount' => Utility::formatNumber($this->cart->grand),
        'item_name' => $this->core->company . ' - ' . Language::$word->CHECKOUT,
         //'item_description' => $this->row->{'description' . Lang::$lang},
        'custom_int1' => $this->auth->uid . '_' . $this->auth->sesid,
      );

      foreach ($array as $k => $v) {
         $html .= '<input type="hidden" name="' . $k . '" value="' . $v . '" />';
         $string .= $k . '=' . urlencode($v) . '&';
      }
      $string = substr($string, 0, -1);
      if ($this->gateway->extra3) {
         $string .= '&passphrase=' . urlencode(trim($this->gateway->extra3));
      }
      $sig = md5($string);
      $html .= '<input type="hidden" name="signature" value="' . $sig . '" />';

      print $html;
   ?>
</form>
<script>
   $('#gSubmit').on('click', function () {
      $('#pf_form').submit();
   });
</script>