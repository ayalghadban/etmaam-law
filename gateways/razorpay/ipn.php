<?php
    /**
     * ipn.php
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
    use Wojo\Auth\Auth;
    use Wojo\Core\Content;
    use Wojo\Core\Core;
    use Wojo\Core\Mailer;
    use Wojo\Core\Membership;
    use Wojo\Core\Session;
    use Wojo\Core\User;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    const _WOJO = true;
    require_once('../../init.php');
    
    if (!$services->auth->is_User()) {
        exit;
    }
    
    require 'lib/Razorpay.php';
    
    if (isset($_POST['razorpay_payment_id'])) {
        $validate = Validator::run($_POST);
        $validate
            ->set('razorpay_signature', 'Invalid Signature')->required()->string()
            ->set('razorpay_payment_id', 'Invalid Payment ID')->required()->string();
        $safe = $validate->safe();
        
        if (!$cart = Membership::getCart($services->auth->uid)) {
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
                
                // insert payment record
                $row = $services->database->select(Membership::mTable)->where('id', $cart->membership_id, '=')->first()->run();
                $data = array(
                    'txn_id' => time(),
                    'membership_id' => $row->id,
                    'user_id' => $services->auth->uid,
                    'rate_amount' => $cart->total,
                    'coupon' => $cart->coupon,
                    'total' => $cart->totalprice,
                    'tax' => $cart->totaltax,
                    'currency' => $apikey->extra2,
                    'ip' => Url::getIP(),
                    'pp' => 'RazorPay',
                    'status' => 1,
                );
                
                $last_id = $services->database->insert(Membership::pTable, $data)->run();
                
                //insert user membership
                $u_data = array(
                    'transaction_id' => $last_id,
                    'user_id' => $services->auth->uid,
                    'membership_id' => $row->id,
                    'expire' => Membership::calculateDays($row->id),
                    'recurring' => $row->recurring,
                    'active' => 1,
                );
                
                //update user record
                $x_data = array(
                    'membership_id' => $row->id,
                    'mem_expire' => $u_data['expire'],
                );
                
                $services->database->insert(Membership::umTable, $u_data)->run();
                $services->database->update(User::mTable, $x_data)->where('id', $services->auth->uid, '=')->run();
                $services->database->delete(Membership::cTable)->where('user_m_id', $services->auth->uid, '=')->run();
                
                //update membership status
                Auth::$udata->membership_id = Session::set('membership_id', $row->id);
                Auth::$udata->mem_expire = Session::set('mem_expire', $x_data['mem_expire']);
                
                $jn['type'] = 'success';
                $jn['title'] = Language::$word->SUCCESS;
                $jn['message'] = Language::$word->STR_POK;
                print json_encode($jn);
                
                /* == Notify Administrator == */
                $mailer = Mailer::sendMail();
                $sql = array('body' . Language::$lang . ' as body', 'subject' . Language::$lang . ' as subject');
                $tpl = $services->database->select(Content::eTable, $sql)->where('typeid', 'payComplete', '=')->first()->run();
                
                $body = str_replace(array(
                    '[LOGO]',
                    '[COMPANY]',
                    '[SITE_NAME]',
                    '[DATE]',
                    '[SITEURL]',
                    '[NAME]',
                    '[ITEMNAME]',
                    '[PRICE]',
                    '[STATUS]',
                    '[PP]',
                    '[IP]',
                    '[CEMAIL]',
                    '[FB]',
                    '[TW]'
                ), array(
                    $services->core->plogo,
                    $services->core->company,
                    $services->core->site_name,
                    date('Y'),
                    SITEURL,
                    $services->auth->fname . ' ' . $services->auth->lname,
                    $row->{'title' . Language::$lang},
                    $data['total'],
                    'Completed',
                    'RazorPay',
                    Url::getIP(),
                    $services->core->site_email,
                    $services->core->social->facebook,
                    $services->core->social->twitter
                ), $tpl->body);
                
                $mailer->Subject = $tpl->subject;
                $mailer->Body = $body;
                
                try {
                    $mailer->setFrom($services->core->site_email, $services->core->company);
                    $mailer->addAddress($services->core->site_email, $services->core->company);
                    $mailer->isHTML();
                    $mailer->send();
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
    }