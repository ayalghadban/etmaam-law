<?php
   /**
    * form
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: form.tpl.php, v1.00 12/2/2023 9:34 AM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   use Stripe\PaymentIntent;
   use Stripe\Stripe;
   use Wojo\Language\Language;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   include BASEPATH . 'gateways/stripe/vendor/autoload.php';
   Stripe::setApiKey($this->gateway->extra);
   $intent = PaymentIntent::create([
     'amount' => round($this->cart->grand * 100),
     'currency' => $this->gateway->extra2,
     'payment_method_types' => ['card'],
     'setup_future_usage' => 'off_session',
   ]);
?>
<div class="wojo small segment form" id="stripe_form">
   <div class="form-row">
      <div id="card-element" class="margin-bottom"></div>
      <button class="wojo primary fluid button" id="dostripe" data-secret="<?php echo $intent->client_secret; ?>" name="dostripe" type="submit"><?php echo Language::$word->SUBMITP; ?></button>
   </div>
   <div role="alert" id="smsgholder" class="text-color-negative"></div>
</div>
<script type="text/javascript">
   // <![CDATA[
   var stripe = Stripe('<?php echo $this->gateway->extra3;?>');
   var elements = stripe.elements();

   var style = {
      base: {
         color: '#32325d',
         fontFamily: 'Roboto, "Helvetica Neue", Helvetica, sans-serif',
         fontSmoothing: 'antialiased',
         fontSize: '16px',
         '::placeholder': {
            color: '#aab7c4'
         }
      },
      invalid: {
         color: '#fa755a',
         iconColor: '#fa755a'
      }
   };

   var card = elements.create('card', {
      style: style
   });

   card.mount('#card-element');

   card.addEventListener('change', function (event) {
      var displayError = document.getElementById('smsgholder');
      if (event.error) {
         displayError.textContent = event.error.message;
      } else {
         displayError.textContent = '';
      }
   });

   var submitButton = document.getElementById('dostripe');
   var clientSecret = submitButton.dataset.secret;

   submitButton.addEventListener('click', function () {
      $('#stripe_form').addClass('loading');
      stripe.confirmCardPayment(clientSecret, {
         payment_method: {
            card: card
         },
         setup_future_usage: 'off_session'
      }).then(function (result) {
         if (result.error) {
            $('#smsgholder').html(result.error.message);
            $('#stripe_form').removeClass('loading');
         } else {
            if (result.paymentIntent.status === 'succeeded') {
               $.ajax({
                  type: 'post',
                  dataType: 'json',
                  url: "<?php echo SITEURL, 'gateways/stripe/shop/ipn.php';?>",
                  data: {
                     processStripePayment: 1,
                     payment_method: result.paymentIntent.payment_method
                  },
                  success: function (json) {
                     $('#stripe_form').removeClass('loading');
                     if (json.type === 'success') {
                        $('body').transition('scaleOut', {
                           duration: 4000,
                           complete: function () {
                              window.location.href = '<?php echo Url::url($this->core->system_slugs->account[0]->{'slug' . Language::$lang}, 'shop');?>';
                           }
                        });
                     }
                     if (json.message) {
                        $.wNotice({
                           autoclose: 12000,
                           type: json.type,
                           title: json.title,
                           text: json.message
                        });
                     }
                  }
               });
            }
         }
      });
   });
   // ]]>
</script>
