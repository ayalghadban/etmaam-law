<?php
    /**
     * ipn
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @var object $services
     * @copyright 2023
     * @version 6.20 ipn.php, v1.00 6/26/2023 1:12 PM Gewa Exp $
     *
     */
    
    use Wojo\Core\Content;
    use Wojo\Core\Core;
    use Wojo\Core\Mailer;
    use Wojo\Core\Membership;
    use Wojo\Core\User;
    use Wojo\Language\Language;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    const _WOJO = true;
    
    ini_set('log_errors', true);
    ini_set('error_log', dirname(__file__) . '/ipn_errors.log');
    
    
    if (intval($_POST['status']) == 2) {
        require_once '../../init.php';
        
        // Check for mandatory fields
        $r_fields = array(
            'status',
            'md5sig',
            'merchant_id',
            'pay_to_email',
            'mb_amount',
            'mb_transaction_id',
            'currency',
            'amount',
            'transaction_id',
            'pay_from_email',
            'mb_currency'
        );
        
        $skrill = $services->database->select(Core::gTable, array('extra3'))->where('name', 'skrill', '=')->first()->run();
        
        foreach ($r_fields as $f) {
            if (!isset($_POST[$f])) {
                die;
            }
        }
        
        // Check for MD5 signature
        $md5 = strtoupper(md5($_POST['merchant_id'] . $_POST['transaction_id'] . strtoupper(md5($skrill->extra3)) . $_POST['mb_amount'] . $_POST['mb_currency'] . $_POST['status']));
        if ($md5 != $_POST['md5sig']) {
            die;
        }
        
        $mb_currency = Validator::sanitize($_POST['mb_currency']);
        $mc_gross = $_POST['amount'];
        $txn_id = Validator::sanitize($_POST['mb_transaction_id']);
        
        list($membership_id, $user_id) = explode('_', $_POST['custom']);
        
        $row = $services->database->select(Membership::mTable)->where('id', intval($membership_id), '=')->first()->run();
        $usr = $services->database->select(User::mTable)->where('id', intval($user_id), '=')->first()->run();
        $cart = Membership::getCart($usr->id);
        
        if ($cart) {
            $v1 = Validator::compareNumbers($mc_gross, $cart->totalprice);
        } else {
            $cart = new stdClass;
            $tax = Membership::calculateTax();
            $v1 = Validator::compareNumbers($mc_gross, $row->price, 'gte');
            
            $cart->originalprice = $row->price;
            $cart->total = $row->price;
            $cart->totaltax = Validator::sanitize($row->price * $tax, 'float');
            $cart->totalprice = Validator::sanitize($tax * $row->price + $row->price, 'float');
        }
        
        $data = array(
            'txn_id' => $txn_id,
            'membership_id' => $row->id,
            'user_id' => $usr->id,
            'rate_amount' => $cart->total,
            'coupon' => $cart->coupon,
            'total' => $cart->totalprice,
            'tax' => $cart->totaltax,
            'currency' => strtoupper($mb_currency),
            'ip' => Url::getIP(),
            'pp' => 'Skrill',
            'status' => 1,
        );
        
        $last_id = $services->database->insert(Membership::pTable, $data)->run();
        
        //insert user membership
        $u_data = array(
            'transaction_id' => $last_id,
            'user_id' => $usr->id,
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
        $services->database->update(User::mTable, $x_data)->where('id', $usr->id, '=')->run();
        $services->database->delete(Membership::cTable)->where('user_id', $usr->id, '=')->run();
        
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
            $usr->fname . ' ' . $usr->lname,
            $row->{'title' . Language::$lang},
            $data['total'],
            'Completed',
            'Skrill',
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
    }