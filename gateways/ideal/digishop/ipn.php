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
    
    use Mollie\Api\Exceptions\ApiException;
    use Mollie\Api\MollieApiClient;
    use Wojo\Core\Content;
    use Wojo\Core\Core;
    use Wojo\Core\Mailer;
    use Wojo\Core\View;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Language\Language;
    use Wojo\Module\Digishop\Digishop;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    const _WOJO = true;
    require_once('../../../init.php');
    
    if (!$services->auth->is_User()) {
        exit;
    }
    
    if (Validator::get('order_id')) {
        require_once BASEPATH . 'gateways/ideal/vendor/autoload.php';
        $apikey = $services->database->select(Core::gTable, array('extra'))->where('name', 'ideal', '=')->first()->run();
        
        $mollie = new MollieApiClient;
        
        $o = Validator::sanitize($_GET['order_id'], 'string');
        $cart = Digishop::getCart($services->auth->sesid);
        
        $totals = Digishop::getCartTotal($services->auth->sesid);
        
        if ($cart) {
            try {
                $payment = $mollie->payments->get($totals->cart_id);
                if ($payment->isPaid() and Validator::compareNumbers($payment->amount->value, $totals->grand)) {
                    $dataArray = array();
                    foreach ($cart as $item) {
                        $dataArray[] = array(
                            'user_id' => $services->auth->uid,
                            'item_id' => $item->pid,
                            'txn_id' => $payment->metadata->order_id,
                            'tax' => $totals->tax,
                            'amount' => $totals->sub,
                            'total' => $totals->grand,
                            'token' => Utility::randomString(16),
                            'pp' => 'iDeal',
                            'ip' => Url::getIP(),
                            'currency' => $apikey->extra2,
                            'status' => 1,
                        );
                    }
                    
                    $services->database->batch(Digishop::xTable, $dataArray)->run();
                    
                    $json['type'] = 'success';
                    $json['title'] = Language::$word->SUCCESS;
                    $json['message'] = Language::$word->STR_POK;
                    
                    
                    /* == Notify User == */
                    $mailer = Mailer::sendMail();
                    $sql = array('body' . Language::$lang . ' as body', 'subject' . Language::$lang . ' as subject');
                    $etpl = $services->database->select(Content::eTable, $sql)->where('typeid', 'digiNotifyUser', '=')->first()->run();
                    
                    $tpl = new View();
                    $tpl->rows = Digishop::getCartContent($services->auth->sesid);
                    $tpl->tax = $totals->tax;
                    $tpl->totals = $totals;
                    //$tpl->template = '_userNotifyTemplate.tpl.php';
                    
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
                    
                } else {
                    $json['type'] = 'error';
                    $json['title'] = Language::$word->ERROR;
                    $json['message'] = Language::$word->STR_ERR1;
                    print json_encode($json);
                }
            } catch (ApiException $e) {
                $json['type'] = 'error';
                $json['title'] = Language::$word->ERROR;
                $json['message'] = 'API call failed: ' . htmlspecialchars($e->getMessage());
                print json_encode($json);
            } catch (FileNotFoundException $e) {
            }
        }
    } else {
        $json['type'] = 'error';
        $json['title'] = Language::$word->ERROR;
        $json['message'] = Language::$word->STR_ERR1;
        print json_encode($json);
    }