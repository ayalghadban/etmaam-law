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
    use Wojo\Module\Shop\Shop;
    use Wojo\Url\Url;
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
        
        if (!$cart = Shop::getCartContent($services->auth->sesid)) {
            Message::$msgs['cart'] = Language::$word->STR_ERR;
        }
        
        if (count(Message::$msgs) === 0) {
            require_once BASEPATH . 'gateways/stripe/vendor/autoload.php';
            $key = $services->database->select(Core::gTable)->where('name', 'stripe', '=')->first()->run();
            
            Stripe::setApiKey($key->extra);
            try {
                $totals = Shop::getCartTotal($services->auth->sesid);
                $shipping = $services->database->select(Shop::qxTable)->where('user_id', $services->auth->sesid, '=')->first()->run();
                
                $dataArray = array();
                $items = array();
                
                //Create a client
                $client = Customer::create(array(
                    'description' => $services->auth->name,
                    'payment_method' => $safe->payment_method,
                ));
                $txn_id = time();
                foreach ($cart as $k => $item) {
                    $vars = ($item->variants ? Shop::formatVariantFromJson(json_decode($item->variants)) : 'NULL');
                    //get stock
                    $stock = $services->database->select(Shop::mTable, array('subtract'))->where('id', $item->pid, '=')->first()->run();
                    
                    $dataArray[] = array(
                        'user_id' => $services->auth->uid,
                        'item_id' => $item->pid,
                        'txn_id' => $txn_id,
                        'tax' => $item->totaltax,
                        'amount' => $item->total,
                        'total' => $item->totalprice,
                        'variant' => $vars,
                        'pp' => $key->displayname,
                        'ip' => Url::getIP(),
                        'currency' => $key->extra2,
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
                    'user_id' => $services->auth->uid,
                    'user' => $services->auth->fname . ' ' . $services->auth->lname,
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
                    $services->auth->fname . ' ' . $services->auth->lname,
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
                    $mailer->addAddress($services->auth->email, $services->auth->name);
                    $mailer->isHTML();
                    $mailer->send();
                    
                    $services->database->delete(Shop::qTable)->where('user_id', $services->auth->uid, '=')->run();
                    $services->database->delete(Shop::qxTable)->where('user_id', $services->auth->uid, '=')->run();
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