<?php
   /**
    * _validate
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _validate.tpl.php, v1.00 6/29/2023 10:02 PM Gewa Exp $
    *
    */

   use Wojo\Language\Language;
   use Wojo\Url\Url;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   $url = '';
   $link = '';
   $type = '';
   $order = '';
   $order_id = '';
   $pay_id = '';
   $sig_id = '';
?>
<div class="wojo-grid">
   <div class="wojo loading segment">
      <h4 class="center-align"><?php echo Language::$word->STR_POK1; ?></h4>
   </div>
   <?php
      if (Validator::get('order_id') or Validator::post('razorpay_payment_id')) {
         if (Validator::get('order_id')) {
            $type = 'ideal';
            $order = substr(Validator::get('order_id'), 0, 4);
            $order_id = Validator::sanitize(Validator::get('order_id'), 'db');
         }
         if (Validator::post('razorpay_payment_id')) {
            $type = 'razorpay';
            $order = substr(Validator::post('type'), 0, 4);
            $pay_id = Validator::post('razorpay_payment_id');
            $sig_id = Validator::post('razorpay_signature');
         }

         switch ($order) {
            case 'DIGI':
               $url = 'gateways/' . $type . '/digishop/ipn.php';
               $link = 'digishop';
               break;

            case 'SHOP':
               $url = 'gateways/' . $type . '/shop/ipn.php';
               $link = 'shop';
               break;

            default:
               $url = 'gateways/' . $type . '/ipn.php';
               $link = 'history';
               break;
         }
      }
   ?>
</div>
<?php if (Validator::get('order_id') or Validator::post('razorpay_payment_id')): ?>
   <script type="text/javascript">
      // <![CDATA[
      $(document).ready(function () {
         $.ajax({
            type: 'GET',
            url: "<?php echo SITEURL . $url;?>",
            dataType: 'json',
            data: {
               'order_id': "<?php echo $order_id;?>",
               'razorpay_payment_id': "<?php echo $pay_id;?>",
               'razorpay_signature': "<?php echo $sig_id;?>",
            }
         }).done(function (json) {
            if (json.type === 'success') {
               $('body').transition('scaleOut', {
                  duration: 4000,
                  onComplete: function () {
                     window.location.href = '<?php echo Url::url($this->core->system_slugs->account[0]->{'slug' . Language::$lang}, $link);?>';
                  }
               });
            }
            $.wNotice({
               autoclose: 12000,
               type: json.type,
               title: json.title,
               text: json.message
            });
         });
      });
      // ]]>
   </script>
<?php endif; ?>