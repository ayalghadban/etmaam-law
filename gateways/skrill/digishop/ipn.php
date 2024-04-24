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
        
        $cart = Digishop::getCart($sesid);
        $usr = $services->database->select(User::mTable)->where('id', intval($user_id), '=')->first()->run();
        $totals = Digishop::getCartTotal($sesid);
        
        $v1 = Validator::compareNumbers($mc_gross, $totals->grand);
        if ($cart and $v1) {
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
                    'pp' => 'Skrill',
                    'ip' => Url::getIP(),
                    'currency' => strtoupper($mb_currency),
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
        }
    }