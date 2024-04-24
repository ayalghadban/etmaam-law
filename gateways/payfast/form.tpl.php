<?php
   /**
    * form
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: form.tpl.php, v1.00 6/25/2023 2:28 PM Gewa Exp $
    *
    */

   use Wojo\Language\Language;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<?php if (!$this->row->recurring): ?>
   <?php $url = ($this->gateway->live)? 'www.payfast.co.za' : 'sandbox.payfast.co.za'; ?>
   <form action="https://<?php echo $url; ?>/eng/process" class="center-align" method="post" id="pf_form" name="pf_form">
      <a id="gSubmit" class="wojo white shadow icon button">
         <img class="wojo medium image" src="<?php echo SITEURL . 'gateways/payfast/payfast_logo.svg'; ?>" alt="<?php echo $this->gateway->displayname; ?>">
      </a>
      <?php
         $html = '';
         $string = '';

         $array = array(
           'merchant_id' => $this->gateway->extra,
           'merchant_key' => $this->gateway->extra2,
           'return_url' => Url::url($this->core->system_slugs->account[0]->{'slug' . Language::$lang}, 'history'),
           'cancel_url' => Url::url($this->core->system_slugs->account[0]->{'slug' . Language::$lang}, 'history'),
           'notify_url' => SITEURL . 'gateways/' . $this->gateway->dir . '/ipn.php',
           'name_first' => $this->auth->fname,
           'name_last' => $this->auth->lname,
           'email_address' => $this->auth->email,
           'm_payment_id' => $this->row->id,
           'amount' => $this->cart->totalprice,
           'item_name' => $this->row->{'title' . Language::$lang},
            //'item_description' => $this->row->{'description' . Lang::$lang},
           'custom_int1' => $this->auth->uid,
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
<?php endif; ?>