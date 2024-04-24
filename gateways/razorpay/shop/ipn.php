<?php
    /**
     * ipn
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @var object $services
     * @version 6.20: ipn.php, v1.00 12/2/2023 9:37 AM Gewa Exp $
     *
     */
    
    use Razorpay\Api\Api;
    use Razorpay\Api\Errors\SignatureVerificationError;
    use Wojo\Core\Content;
    use Wojo\Core\Core;
    use Wojo\Core\Mailer;
    use Wojo\Core\View;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Module\Shop\Shop;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    const _WOJO = true;
    require_once('../../../init.php');
    
    if (!$services->auth->is_User()) {
        exit;
    }
    require BASEPATH . 'gateways/razorpay/lib/Razorpay.php';
    
    if (isset($_POST['razorpay_payment_id'])) {
        $validate = Validator::run($_POST);
        $validate
            ->set('razorpay_signature', 'Invalid Signature')->required()->string()
            ->set('razorpay_payment_id', 'Invalid Payment ID')->required()->string();
        $safe = $validate->safe();
        
        if (!$cart = Shop::getCartContent($services->auth->sesid)) {
            Message::$msgs['cart'] = Language::$word->STR_ERR;
        }
        
        if (count(Message::$msgs) === 0) {
            $apikey = $services->database->select(Core::gTable)->where('name', 'razorpay', '=')->first()->run();
            $api = new Api($apikey->extra, $apikey->extra3);
            
            try {
                $attributes = array(
                    'razorpay_order_id' => $cart->order_id,
                    'razorpay_payment_id' => $_POST['razorpay_payment_id'],
                    'razorpay_signature' => $_POST['razorpay_signature']
                );
                
                $api->utility->verifyPaymentSignature($attributes);
                $cart = Shop::getCartContent($services->auth->sesid);
                
                $totals = Shop::getCartTotal($services->auth->sesid);
                $shipping = $services->database->select(Shop::qxTable)->where('user_id', $services->auth->sesid, '=')->first()->run();
                
                $dataArray = array();
                $items = array();
                
                foreach ($cart as $k => $item) {
                    $vars = ($item->variants ? Shop::formatVariantFromJson(json_decode($item->variants)) : 'NULL');
                    //get stock
                    $stock = $services->database->select(Shop::mTable, array('subtract'))->where('id', $item->pid, '=')->first()->run();
                    
                    $dataArray[] = array(
                        'user_id' => $services->auth->uid,
                        'item_id' => $item->pid,
                        'txn_id' => $safe->razorpay_payment_id,
                        'tax' => $item->totaltax,
                        'amount' => $item->total,
                        'total' => $item->totalprice,
                        'variant' => $vars,
                        'pp' => $apikey->displayname,
                        'ip' => Url::getIP(),
                        'currency' => $apikey->extra_txt2,
                        'status' => 1,
                    );
                    
                    $items[$k]['title'] = $item->title;
                    $items[$k]['price'] = $item->totalprice;
                    $items[$k]['variant'] = $vars;
                    
                    //update stock
                    if ($stock->subtract) {
                        $services->database->rawQuery('UPDATE `' . Shop::mTable . '` SET quantity = quantity - 1 WHERE id = ?', array($item->pid))->run();
                    }
                }
                
                $services->database->batch(Shop::xTable, $dataArray)->run();
                
                //shipping data
                $xdata = array(
                    'invoice_id' => substr(time(), 5),
                    'transaction_id' => $safe->razorpay_payment_id,
                    'user_id' => $services->auth->uid,
                    'user' => $services->auth->fname . ' ' . $services->auth->lname,
                    'items' => json_encode($items),
                    'total' => $totals->grand,
                    'shipping' => $shipping->total,
                    'address' => $shipping->address,
                    'name' => $shipping->name,
                );
                
                $services->database->insert(Shop::shTable, $xdata)->run();
                
                $json['type'] = 'success';
                $json['title'] = Language::$word->SUCCESS;
                $json['message'] = Language::$word->STR_POK;
                
                /* == Notify User == */
                $mailer = Mailer::sendMail();
                $sql = array('body' . Language::$lang . ' as body', 'subject' . Language::$lang . ' as subject');
                $etpl = $services->database->select(Content::eTable, $sql)->where('typeid', 'shopNotifyUser', '=')->first()->run();
                
                $tpl = new View();
                $tpl->rows = $cart;
                $tpl->tax = $totals->tax;
                $tpl->totals = $totals;
                $tpl->shipping = $shipping;
                
                $body = str_replace(array(
                    '[LOGO]',
                    '[COMPANY]',
                    '[SITE_NAME]',
                    '[NAME]',
                    '[DATE]',
                    '[ITEMS]',
                    '[LINK]',
                    '[CEMAIL]',
                    '[FB]',
                    '[TW]',
                    '[SITEURL]'
                ), array(
                    $services->core->plogo,
                    $services->core->company,
                    $services->core->site_name,
                    $services->auth->fname . ' ' . $services->auth->lname,
                    date('Y'),
                    $tpl->snippet('_userNotifyTemplate', FMODPATH . 'shop/snippets/'),
                    Url::url($services->core->system_slugs->account[0]->{'slug' . Language::$lang}, 'shop'),
                    $services->core->site_email,
                    $services->core->social->facebook,
                    $services->core->social->twitter,
                    SITEURL
                ), $etpl->body);
                
                $mailer->Subject = $etpl->subject;
                $mailer->Body = $body;
                
                try {
                    $mailer->setFrom($services->core->site_email, $services->core->company);
                    $mailer->addAddress($services->auth->email, $services->auth->name);
                    $mailer->isHTML();
                    $mailer->send();
                    
                    $services->database->delete(Shop::qTable)->where('user_id', $services->auth->uid, '=')->run();
                    $services->database->delete(Shop::qxTable)->where('user_id', $services->auth->uid, '=')->run();
                } catch (\PHPMailer\PHPMailer\Exception) {
                }
                
            } catch (SignatureVerificationError $e) {
                $json['type'] = 'error';
                $json['title'] = Language::$word->ERROR;
                $json['message'] = $e->getMessage();
                print json_encode($json);
            }
        } else {
            Message::msgSingleStatus();
        }
    } else {
        Message::msgSingleStatus();
    }