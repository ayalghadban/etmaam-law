<?php
    /**
     * ipn.php
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @var object $services
     * @copyright 2023
     * @version 6.20 ipn.php, v1.00 6/26/2023 8:35 AM Gewa Exp $
     *
     */
    
    use Mollie\Api\Exceptions\ApiException;
    use Mollie\Api\MollieApiClient;
    use Wojo\Auth\Auth;
    use Wojo\Core\Content;
    use Wojo\Core\Core;
    use Wojo\Core\Mailer;
    use Wojo\Core\Membership;
    use Wojo\Core\Session;
    use Wojo\Core\User;
    use Wojo\Exception\NotFoundException;
    use Wojo\Language\Language;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    const _WOJO = true;
    require_once('../../init.php');
    
    if (!$services->auth->is_User()) {
        exit;
    }
    
    if (Validator::get('order_id')) {
        require_once 'vendor/autoload.php';
        $apikey = $services->database->select(Core::gTable, array('extra'))->where('name', 'ideal', '=')->first()->run();
        
        $mollie = new MollieApiClient;
        
        try {
            $mollie->setApiKey($apikey->extra);
            
            $o = Validator::sanitize($_GET['order_id'], 'string');
            $cart = $services->database->select(Membership::cTable)->where('order_id', $o, '=')->run();
            
            if ($cart) {
                $payment = $mollie->payments->get($cart->cart_id);
                if ($payment->isPaid() and Validator::compareNumbers($payment->amount->value, $cart->totalprice)) {
                    $row = $services->database->select(Membership::mTable)->where('id', $cart->membership_id, '=')->first()->run();
                    $data = array(
                        'txn_id' => $payment->metadata->order_id,
                        'membership_id' => $row->id,
                        'user_id' => $services->auth->uid,
                        'rate_amount' => $cart->total,
                        'coupon' => $cart->coupon,
                        'total' => $cart->totalprice,
                        'tax' => $cart->totaltax,
                        'currency' => 'EUR',
                        'ip' => Url::getIP(),
                        'pp' => 'iDeal',
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
                    
                    $json['type'] = 'success';
                    $json['title'] = Language::$word->SUCCESS;
                    $json['message'] = Language::$word->STR_POK;
                    
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
                        'iDeal',
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
                } else {
                    $json['type'] = 'error';
                    $json['title'] = Language::$word->ERROR;
                    $json['message'] = Language::$word->STR_ERR1;
                }
            } else {
                $json['type'] = 'error';
                $json['title'] = Language::$word->ERROR;
                $json['message'] = Language::$word->STR_ERR1;
            }
            print json_encode($json);
        } catch (ApiException $e) {
            $json['type'] = 'error';
            $json['title'] = Language::$word->ERROR;
            $json['message'] = 'API call failed: ' . htmlspecialchars($e->getMessage());
            print json_encode($json);
        } catch (NotFoundException $e) {
        }
    } else {
        $json['type'] = 'error';
        $json['title'] = Language::$word->ERROR;
        $json['message'] = Language::$word->STR_ERR1;
        print json_encode($json);
    }