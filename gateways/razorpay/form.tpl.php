<?php
   /**
    * form
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: form.tpl.php, v1.00 6/25/2023 2:52 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   require 'lib/Razorpay.php';

   use Razorpay\Api\Api;
   use Wojo\Core\Membership;
   use Wojo\Database\Database;
   use Wojo\Language\Language;
   use Wojo\Url\Url;

   $api = new Api($this->gateway->extra, $this->gateway->extra3);
   $displayCurrency = $this->gateway->extra2;

   $orderData = array(
     'receipt' => md5(time()),
     'amount' => round($this->cart->totalprice * 100),
     'currency' => $this->gateway->extra2,
     'payment_capture' => 1 // auto capture
   );

   $razorpayOrder = $api->order->create($orderData);
   $razorpayOrderId = $razorpayOrder['id'];
   $displayAmount = $amount = $orderData['amount'];

   $data = array(
     'key' => $this->gateway->extra,
     'amount' => $amount,
     'name' => $this->row->{'title' . Language::$lang},
     'description' => '',
     'image' => UPLOADURL . $this->core->logo,
     'prefill' => array(
       'name' => $this->auth->name,
       'email' => $this->auth->email,
       'contact' => '',
     ),
     'theme' => array(
       'color' => '#667eea'
     ),
     'order_id' => $razorpayOrderId,
   );

   $json = json_encode($data);

   $this->db->update(Membership::cTable, array('order_id' => $razorpayOrderId))->where('user_m_id', $this->auth->uid, '=')->run();
?>
<form name="razorpayform" action="<?php echo Url::url('/validate');?>" method="POST">
   <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
   <input type="hidden" name="razorpay_signature"  id="razorpay_signature" >
   <!-- Any extra fields to be submitted with the form but not sent to Razorpay -->
   <input type="hidden" name="type" value="MEMB">
   <input type="hidden" name="name" value="razorpay">
</form>
<script type="text/javascript">
   var options = <?php echo $json;?>;
   options.handler = function(response) {
      document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
      document.getElementById('razorpay_signature').value = response.razorpay_signature;
      document.razorpayform.submit();
   };

   options.theme.image_padding = false;

   options.modal = {
      ondismiss: function() {
         console.log("This code runs when the popup is closed");
      },
      escape: true,
      backdropclose: false
   };

   var rzp = new Razorpay(options);

   document.getElementById('rzrpay').onclick = function(e){
      rzp.open();
      e.preventDefault();
   }
</script>
<div class="center-align">
   <a id="rzrpay" class="wojo white shadow icon button">
      <img class="wojo medium image" src="<?php echo SITEURL . 'gateways/razorpay/razorpay_logo.svg'; ?>" alt="<?php echo $this->gateway->displayname; ?>">
   </a>
</div>
