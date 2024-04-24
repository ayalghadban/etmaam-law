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
    
    use Wojo\Core\Content;
    use Wojo\Core\Core;
    use Wojo\Core\Mailer;
    use Wojo\Core\User;
    use Wojo\Core\View;
    use Wojo\Language\Language;
    use Wojo\Module\Digishop\Digishop;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    const _WOJO = true;
    
    ini_set('log_errors', true);
    ini_set('error_log', dirname(__file__) . '/ipn_errors.log');
    
    if (isset($_POST['payment_status'])) {
        require_once '../../../init.php';
        
        require_once BASEPATH . 'gateways/payfast/pf.inc.php';
        
        $pf = $services->database->select(Core::gTable)->where('name', 'payfast', '=')->first()->run();
        $pfHost = ($pf->live) ? 'https://www.payfast.co.za' : 'https://sandbox.payfast.co.za';
        $error = false;
        
        pflog('ITN received from payfast.co.za');
        if (!pfValidIP($_SERVER['REMOTE_ADDR'])) {
            pflog('REMOTE_IP mismatch: ');
            $error = true;
            return false;
        }
        $data = pfGetData();
        
        pflog('POST received from payfast.co.za: ' . print_r($data, true));
        
        if ($data === false) {
            pflog('POST is empty');
            $error = true;
            return false;
        }
        
        if (!pfValidSignature($data, $pf->extra3)) {
            pflog('Signature mismatch on POST');
            $error = true;
            return false;
        }
        
        pflog('Signature OK');
        
        $itnPostData = array();
        $itnPostDataValuePairs = array();
        
        foreach ($_POST as $key => $value) {
            if ($key == 'signature') {
                continue;
            }
            
            $value = urlencode(stripslashes($value));
            $value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i', '${1}%0D%0A${3}', $value);
            $itnPostDataValuePairs[] = "$key=$value";
        }
        
        $itnVerifyRequest = implode('&', $itnPostDataValuePairs);
        if (!pfValidData($pfHost, $itnVerifyRequest, "$pfHost/eng/query/validate")) {
            pflog("ITN mismatch for $itnVerifyRequest\n");
            pflog('ITN not OK');
            $error = true;
            return false;
        }
        
        pflog('ITN OK');
        pflog("ITN verified for $itnVerifyRequest\n");
        
        if (!$error and $_POST['payment_status'] == 'COMPLETE') {
            $mc_gross = $_POST['amount_gross'];
            $item_ = $_POST['m_payment_id'];
            $txn_id = Validator::sanitize($_POST['pf_payment_id']);
            list($user_id, $sesid) = explode('_', $_POST['custom_int1']);
            $sesid = Validator::sanitize($sesid);
            
            $usr = $services->database->select(User::mTable)->where('id', intval($user_id), '=')->first()->run();
            $cart = Digishop::getCart($sesid);
            $totals = Digishop::getCartTotal($sesid);
            $v1 = Validator::compareNumbers($mc_gross, $totals->grand);
            
            if ($v1) {
                $dataArray = array();
                foreach ($cart as $item) {
                    $dataArray[] = array(
                        'user_id' => $usr->id,
                        'item_id' => $item->pid,
                        'txn_id' => $txn_id,
                        'tax' => $totals->tax,
                        'amount' => $totals->sub,
                        'total' => $totals->grand,
                        'token' => Utility::randomString(16),
                        'pp' => 'RazorPay',
                        'ip' => Url::getIP(),
                        'currency' => 'ZAR',
                        'status' => 1,
                    );
                }
                
                $services->database->batch(Digishop::xTable, $dataArray)->run();
                
                /* == Notify User == */
                $mailer = Mailer::sendMail();
                $sql = array('body' . Language::$lang . ' as body', 'subject' . Language::$lang . ' as subject');
                $etpl = $services->database->select(Content::eTable, $sql)->where('typeid', 'digiNotifyUser', '=')->first()->run();
                
                $tpl = new View();
                $tpl->rows = Digishop::getCartContent($sesid);
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
                    $usr->fname . ' ' . $usr->lname,
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
                    $mailer->addAddress($usr->email, $usr->fname . ' ' . $usr->lname);
                    $mailer->isHTML();
                    $mailer->send();
                    
                    $services->database->delete(Digishop::qTable)->where('user_sid', $sesid, '=')->run();
                } catch (\PHPMailer\PHPMailer\Exception) {
                }
            }
        }
    }