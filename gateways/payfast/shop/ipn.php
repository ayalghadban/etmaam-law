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
    use Wojo\Module\Shop\Shop;
    use Wojo\Url\Url;
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
            $cart = Shop::getCartContent($sesid);
            $totals = Shop::getCartTotal($sesid);
            $shipping = $services->database->select(Shop::qxTable)->where('user_id', intval($sesid), '=')->first()->run();
            
            $v1 = Validator::compareNumbers($mc_gross, $totals->grand);
            
            $items = array();
            $dataArray = array();
            if ($v1) {
                foreach ($cart as $k => $item) {
                    $vars = ($item->variants ? Shop::formatVariantFromJson(json_decode($item->variants)) : 'NULL');
                    //get stock
                    $stock = $services->database->select(Shop::mTable, array('subtract'))->where('id', $item->pid, '=')->first()->run();
                    
                    $dataArray[] = array(
                        'user_id' => $usr->id,
                        'item_id' => $item->pid,
                        'txn_id' => $txn_id,
                        'tax' => Validator::sanitize($item->tax, 'float'),
                        'amount' => Validator::sanitize($item->total, 'float'),
                        'total' => Validator::sanitize($item->totalprice, 'float'),
                        'variant' => $vars,
                        'pp' => $pf->displayname,
                        'ip' => Url::getIP(),
                        'currency' => $pf->extra2,
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
                    'transaction_id' => $txn_id,
                    'user_id' => $usr->id,
                    'user' => $usr->fname . ' ' . $usr->lname,
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
                    $usr->fname . ' ' . $usr->lname,
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
                    $mailer->addAddress($usr->email, $usr->fname . ' ' . $usr->lname);
                    $mailer->isHTML();
                    $mailer->send();
                    
                    $services->database->delete(Shop::qTable)->where('user_id', $usr->sesid, '=')->run();
                    $services->database->delete(Shop::qxTable)->where('user_id', $usr->sesid, '=')->run();
                } catch (\PHPMailer\PHPMailer\Exception) {
                }
            }
        }
    }