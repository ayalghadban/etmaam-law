<?php
    /**
     * ipn
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @var object $services
     * @copyright 2023
     * @version 6.20 ipn.php, v1.00 6/24/2023 6:20 PM Gewa Exp $
     *
     */
    
    use Stripe\Customer;
    use Stripe\Exception\CardException;
    use Stripe\Stripe;
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
    require_once '../../init.php';
    
    if (!$services->auth->is_User()) {
        exit;
    }
    
    ini_set('log_errors', true);
    ini_set('error_log', dirname(__file__) . '/ipn_errors.log');
    
    if (isset($_POST['processStripePayment'])) {
        $validate = Validator::run($_POST);
        $validate->set('payment_method', 'Invalid Payment Methods')->required()->string();
        $safe = $validate->safe();
        
        if (!$cart = Membership::getCart($services->auth->uid)) {
            Message::$msgs['cart'] = Language::$word->STR_ERR;
        }
        
        if (count(Message::$msgs) === 0) {
            require_once BASEPATH . 'gateways/stripe/vendor/autoload.php';
            $key = $services->database->select(Core::gTable)->where('name', 'stripe', '=')->first()->run();
            
            Stripe::setApiKey($key->extra);
            try {
                //Create a client
                $client = Customer::create(array(
                    'description' => $services->auth->name,
                    'payment_method' => $safe->payment_method,
                ));
                
                $row = $services->database->select(Membership::mTable)->where('id', $cart->membership_id, '=')->first()->run();
                
                // insert payment record
                $data = array(
                    'txn_id' => time(),
                    'membership_id' => $row->id,
                    'user_id' => $services->auth->uid,
                    'rate_amount' => $cart->total,
                    'coupon' => $cart->coupon,
                    'total' => $cart->totalprice,
                    'tax' => $cart->totaltax,
                    'currency' => $key->extra2,
                    'ip' => Url::getIP(),
                    'pp' => 'Stripe',
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
                    'stripe_cus' => $client['id'],
                    'membership_id' => $row->id,
                    'mem_expire' => $u_data['expire'],
                );
                
                $services->database->insert(Membership::umTable, $u_data)->run();
                $services->database->update(User::mTable, $x_data)->where('id', $services->auth->uid, '=')->run();
                
                //insert cron record
                if ($row->recurring) {
                    $cdata = array(
                        'user_id' => $services->auth->uid,
                        'membership_id' => $row->id,
                        'amount' => $cart->totalprice,
                        'stripe_customer' => $client['id'],
                        'stripe_pm' => $safe->payment_method,
                        'renewal' => $u_data['expire'],
                    );
                    $services->database->insert(Core::cjTable, $cdata)->run();
                }
                
                $services->database->delete(Membership::cTable)->where('user_id', $services->auth->uid, '=')->run();
                
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
                    'Stripe',
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
                
            } catch (CardException $e) {
                $json['type'] = 'error';
				$json['title'] = 'ERROR';
                Message::$msgs['msg'] = 'Message is: ' . $e->getError()->message() . "\n";
                Message::msgSingleStatus();
            }
        } else {
            Message::msgSingleStatus();
        }
    }