<?php
    /**
     * ipn
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @var object $services
     * @copyright 2023
     * @version 6.20: ipn.php, v1.00 6/28/2023 10:55 AM Gewa Exp $
     *
     */
    
    use Stripe\Customer;
    use Stripe\Exception\ApiErrorException;
    use Stripe\Exception\CardException;
    use Stripe\Stripe;
    use Wojo\Core\Content;
    use Wojo\Core\Core;
    use Wojo\Core\Mailer;
    use Wojo\Core\View;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Module\Digishop\Digishop;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    const _WOJO = true;
    require_once '../../../init.php';
    
    if (!$services->auth->is_User()) {
        exit;
    }
    
    ini_set('log_errors', true);
    ini_set('error_log', dirname(__file__) . '/ipn_errors.log');
    
    if (isset($_POST['processStripePayment'])) {
        $validate = Validator::run($_POST);
        $validate->set('payment_method', 'Invalid Payment Methods')->required()->string();
        $safe = $validate->safe();
        
        if (!$cart = Digishop::getCart($services->auth->sesid)) {
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
                $totals = Digishop::getCartTotal($services->auth->sesid);
                $dataArray = array();
                foreach ($cart as $item) {
                    $dataArray[] = array(
                        'user_id' => $services->auth->uid,
                        'item_id' => $item->pid,
                        'txn_id' => time(),
                        'tax' => $totals->tax,
                        'amount' => $totals->sub,
                        'total' => $totals->grand,
                        'token' => Utility::randomString(16),
                        'pp' => 'Stripe',
                        'ip' => Url::getIP(),
                        'currency' => $key->extra2,
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
                } catch (\PHPMailer\PHPMailer\Exception) {
                
                }
                
            } catch (CardException $e) {
                $json['type'] = 'error';
                Message::$msgs['msg'] = 'Message is: ' . $e->getError()->message() . "\n";
                Message::msgSingleStatus();
            } catch (ApiErrorException|FileNotFoundException $e) {
            }
        } else {
            Message::msgSingleStatus();
        }
    }