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
    
    if (intval($_POST['status']) == 2) {
        require_once '../../../init.php';
        
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
        
        $skrill = $services->database->select(Core::gTable)->where('name', 'skrill', '=')->first()->run();
        
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
        list($user_id, $sesid) = explode('_', $_POST['custom']);
        
        
        $usr = $services->database->select(User::mTable)->where('id', intval($user_id), '=')->first()->run();
        $cart = Shop::getCartContent($sesid);
        $totals = Shop::getCartTotal($sesid);
        $shipping = $services->database->select(Shop::qxTable)->where('user_id', $user_id, '=')->first()->run();
        
        $v1 = Validator::compareNumbers($mc_gross, $totals->grand);
        
        if ($cart and $v1) {
            $dataArray = array();
            $items = array();
            
            foreach ($cart as $k => $item) {
                $vars = ($item->variants ? Shop::formatVariantFromJson(json_decode($item->variants)) : 'NULL');
                //get stock
                $stock = $services->database->select(Shop::mTable, array('subtract'))->where('id', $item->pid, '=')->first()->run();
                
                $dataArray[] = array(
                    'user_id' => $usr->id,
                    'item_id' => $item->pid,
                    'txn_id' => $txn_id,
                    'tax' => $item->totaltax,
                    'amount' => $item->total,
                    'total' => $item->totalprice,
                    'variant' => $vars,
                    'pp' => $skrill->displayname,
                    'ip' => Url::getIP(),
                    'currency' => $skrill->extra2,
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
                
                $services->database->delete(Shop::qTable)->where('user_id', $usr->id, '=')->run();
                $services->database->delete(Shop::qxTable)->where('user_id', $usr->id, '=')->run();
            } catch (\PHPMailer\PHPMailer\Exception) {
            }
        } else {
            error_log('Process IPN failed: payment or paypal email mismatch', 3, 'pp_errorlog.log');
        }
    }