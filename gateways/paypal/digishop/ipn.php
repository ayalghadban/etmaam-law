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
        require_once BASEPATH . 'gateways/paypal/PayPal.php.php';
        
        $pp = $services->database->select(Core::gTable)->where('name', 'paypal', '=')->first()->run();
        
        $listener = new PayPal();
        $listener->use_live = $pp->live;
        
        try {
            $listener->requirePostMethod();
            $pp_ver = $listener->processIpn();
        } catch (Exception $e) {
            error_log('Process IPN failed: ' . $e->getMessage() . ' [' . $_SERVER['REMOTE_ADDR'] . "] \n" . $listener->getResponse(), 3, 'pp_errorlog.log');
            exit(0);
        }
        
        $payment_status = $_POST['payment_status'];
        $receiver_email = $_POST['receiver_email'];
        $mc_currency = Validator::sanitize($_POST['mc_currency'], 'string', 4);
        list($user_id, $sesid) = explode('_', $_POST['item_number']);
        $mc_gross = $_POST['mc_gross'];
        $sesid = Validator::sanitize($sesid);
        $txn_id = Validator::sanitize($_POST['txn_id']);
        
        if ($pp_ver) {
            if ($_POST['payment_status'] == 'Completed') {
                $usr = $services->database->select(User::mTable)->where('id', intval($user_id), '=')->first()->run();
                $cart = Digishop::getCart($sesid);
                $totals = Digishop::getCartTotal($sesid);
                
                $v1 = Validator::compareNumbers($mc_gross, $totals->grand);
                
                if ($cart and $v1 and $receiver_email == strtolower($pp->extra)) {
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
                            'currency' => strtoupper($mc_currency),
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
                        $mailer->addAddress($usr->email, $usr->name);
                        $mailer->isHTML();
                        $mailer->send();
                        
                        $services->database->delete(Digishop::qTable)->where('user_sid', $sesid, '=')->run();
                    } catch (\PHPMailer\PHPMailer\Exception) {
                    }
                } else {
                    error_log('Process IPN failed: payment or paypal email mismatch', 3, 'pp_errorlog.log');
                }
            }
        }
    }