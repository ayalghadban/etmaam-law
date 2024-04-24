<?php
    /**
     * ipn
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @var object $services
     * @copyright 2023
     * @version 6.20 ipn.php, v1.00 6/26/2023 8:58 AM Gewa Exp $
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
    use Wojo\Module\Digishop\Digishop;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
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
        
        if (!$cart = Digishop::getCart($services->auth->sesid)) {
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
                $totals = Digishop::getCartTotal($services->auth->sesid);
                
                $dataArray = array();
                foreach ($cart as $item) {
                    $dataArray[] = array(
                        'user_id' => $services->auth->uid,
                        'item_id' => $item->pid,
                        'txn_id' => $safe->razorpay_payment_id,
                        'tax' => $totals->tax,
                        'amount' => $totals->sub,
                        'total' => $totals->grand,
                        'token' => Utility::randomString(16),
                        'pp' => 'RazorPay',
                        'ip' => Url::getIP(),
                        'currency' => $apikey->extra2,
                        'status' => 1,
                    );
                }
                
                $services->database->batch(Digishop::xTable, $dataArray)->run();
                
                $jn['type'] = 'success';
                $jn['title'] = Language::$word->SUCCESS;
                $jn['message'] = Language::$word->STR_POK;
                print json_encode($jn);
                
                /* == Notify User == */
                $mailer = Mailer::sendMail();
                $sql = array('body' . Language::$lang . ' as body', 'subject' . Language::$lang . ' as subject');
                $etpl = $services->database->select(Content::eTable, $sql)->where('typeid', 'digiNotifyUser', '=')->first()->run();
                
                $tpl = new View();
                $tpl->rows = Digishop::getCartContent($services->auth->sesid);
                $tpl->tax = $totals->tax;
                $tpl->totals = $totals;
                $tpl->template = '_userNotifyTemplate.tpl.php';
                
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
                    $tpl->snippet('_userNotifyTemplate', FMODPATH . 'digishop/snippets/'),
                    Url::url($services->core->system_slugs->account[0]->{'slug' . Language::$lang}, 'digishop'),
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
                    
                    $services->database->delete(Digishop::qTable)->where('user_sid', $services->auth->sesid, '=')->run();
                } catch (\PHPMailer\PHPMailer\Exception) {}
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