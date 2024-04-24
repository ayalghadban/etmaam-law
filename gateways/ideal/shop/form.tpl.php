<?php
   /**
    * form
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: form.tpl.php, v1.00 12/2/2023 9:22 AM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   
   
   use Mollie\Api\Exceptions\ApiException;
   use Mollie\Api\MollieApiClient;
   use Mollie\Api\Types\PaymentMethod;
   use Wojo\Debug\Debug;
   use Wojo\Language\Language;
   use Wojo\Module\Shop\Shop;
   use Wojo\Url\Url;
   
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   
   require BASEPATH . 'gateways/ideal/vendor/autoload.php';
   
   $mollie = new MollieApiClient();
   
   try {
      $mollie->setApiKey($this->gateway->extra);
      $order_id = 'SHOP_' . md5(time());
      $payment = $mollie->payments->create(array(
        'amount' => array(
          'currency' => $this->gateway->extra2,
          'value' => $this->cart->grand,
        ),
        'method' => PaymentMethod::IDEAL,
        'description' => $this->core->company . ' - ' . Language::$word->CHECKOUT,
        'redirectUrl' => Url::url($this->core->system_slugs->account[0]->{'slug' . Language::$lang} . '/validate', '?ideal=1&order_id=' . $order_id),
        'metadata' => array('order_id' => $order_id, 'user_id' => $this->auth->sesid),
      ));
      $this->db->update(Shop::qTable, array('cart_id' => $payment->id, 'order_id' => $order_id))->where('user_id', $this->auth->sesid, '=')->run();
      
      echo '
         <a href="' . $payment->getPaymentUrl() . '" class="wojo white shadow fluid icon button">
            <img class="wojo medium image" src="' . SITEURL . 'gateways/ideal/ideal_logo.svg" alt="' . $this->gateway->displayname . '">
         </a>
      ';
   } catch (ApiException $e) {
      Debug::addMessage('errors', 'API call failed:', $e->getMessage(), 'session');
   }