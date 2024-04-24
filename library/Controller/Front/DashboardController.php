<?php
    
    /**
     * DashboardController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: DashboardController.php, v1.00 6/22/2023 1:17 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Front;
    
    use Exception;
    use JetBrains\PhpStorm\NoReturn;
    use Mpdf\Mpdf;
    use Mpdf\MpdfException;
    use Wojo\Auth\Auth;
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Core;
    use Wojo\Core\Filter;
    use Wojo\Core\Membership;
    use Wojo\Core\Session;
    use Wojo\Core\User;
    use Wojo\Debug\Debug;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Exception\NotFoundException;
    use Wojo\File\File;
    use Wojo\Image\Image;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Stats\Stats;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class DashboardController extends Controller
    {
        /**
         * @param $request
         * @param $response
         * @param $services
         */
        public function __construct($request, $response, $services)
        {
            parent::__construct($request, $response, $services);
        }
        
        /**
         * index
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function index(): void
        {
            $this->view->title = str_replace('[COMPANY]', $this->core->company, Language::$word->META_T31);
            $this->view->keywords = null;
            $this->view->description = null;
            $this->view->meta = null;
            $this->view->crumbs = null;
            
            $this->view->user = $this->db->select(User::mTable, array('mem_expire', 'membership_id'))->where('id', $this->auth->uid, '=')->first()->run();
            $this->view->memberships = $this->db->select(Membership::mTable)->where('active', 1, '=')->where('private', 1, '<')->orderBy('price', 'DESC')->run();
            $this->auth->membership_id = $this->view->user->membership_id;
            $this->auth->mem_expire = $this->view->user->mem_expire;
            $this->view->url = $this->core->system_slugs->account[0]->{'slug' . Language::$lang};
            
            $this->view->render('dashboard', 'view/front/themes/' . $this->core->theme . '/');
        }
        
        /**
         * history
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function history(): void
        {
            $this->view->title = str_replace('[COMPANY]', $this->core->company, Language::$word->META_T31);
            $this->view->keywords = null;
            $this->view->description = null;
            $this->view->meta = null;
            $this->view->crumbs = null;
            
            $this->view->history = Stats::userHistory($this->auth->uid, 'expire');
            $this->view->url = $this->core->system_slugs->account[0]->{'slug' . Language::$lang};
            
            $this->view->render('dashboard', 'view/front/themes/' . $this->core->theme . '/');
        }
        
        /**
         * settings
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function settings(): void
        {
            $lg = Language::$lang;
            $this->view->title = str_replace('[COMPANY]', $this->core->company, Language::$word->META_T31);
            $this->view->keywords = null;
            $this->view->description = null;
            $this->view->meta = null;
            $this->view->crumbs = null;
            
            $sql = "SELECT u.*, m.title$lg as title FROM `" . User::mTable . '` as u LEFT JOIN `' . Membership::mTable . '` as m ON u.membership_id = u.id WHERE u.id = ?';
            $this->view->user = $this->db->rawQuery($sql, array($this->auth->uid))->first()->run();
            $this->view->custom_fields = Content::renderCustomFields($this->auth->uid, 'profile');
            $this->view->countries = $this->core->enable_tax ? $this->db->select(Content::cTable)->orderBy('sorting', 'DESC')->run() : null;
            $this->view->url = $this->core->system_slugs->account[0]->{'slug' . Language::$lang};
            
            $this->view->render('dashboard', 'view/front/themes/' . $this->core->theme . '/');
        }
        
        /**
         * validate
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function validate(): void
        {
            $this->view->title = str_replace('[COMPANY]', $this->core->company, Language::$word->META_T31);
            $this->view->keywords = null;
            $this->view->description = null;
            $this->view->meta = null;
            $this->view->crumbs = null;
            
            $this->view->url = $this->core->system_slugs->account[0]->{'slug' . Language::$lang};
            
            $this->view->render('dashboard', 'view/front/themes/' . $this->core->theme . '/');
        }
        
        /**
         * action
         *
         * @return void
         * @throws FileNotFoundException
         * @throws MpdfException
         * @throws NotFoundException
         */
        public function action(): void
        {
            $postAction = Validator::post('action');
            $getAction = Validator::get('action');
            
            if ($postAction or $getAction) {
                if ($postAction) {
                    if (IS_AJAX) {
                        switch ($postAction) {
                            case 'profile':
                                IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->profile();
                                break;
                            
                            case 'avatar':
                                IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->avatar();
                                break;
                            
                            case 'membership':
                                $this->membership();
                                break;
                            
                            case 'coupon':
                                $this->coupon();
                                break;
                            
                            case 'gateway':
                                $this->gateway();
                                break;
                            
                            case 'activateCoupon':
                                $this->activateCoupon();
                                break;
                            
                            default:
                                Url::invalidMethod();
                                break;
                        }
                    } else {
                        Url::invalidMethod();
                    }
                }
                if ($getAction) {
                    switch ($getAction) {
                        case 'invoice':
                            $this->invoice();
                            break;
                        
                        default:
                            Url::invalidMethod();
                            break;
                    }
                }
            } else {
                Url::invalidMethod();
            }
        }
        
        /**
         * profile
         *
         * @return void
         */
        private function profile(): void
        {
            $validate = Validator::run($_POST);
            $validate
                ->set('fname', Language::$word->M_FNAME)->required()->string()->min_len(2)->max_len(60)
                ->set('lname', Language::$word->M_LNAME)->required()->string()->min_len(2)->max_len(60)
                ->set('email', Language::$word->M_EMAIL)->required()->email()
                ->set('fb_link', 'Facebook')->string()
                ->set('tw_link', 'Twitter')->string()
                ->set('gp_link', 'Instagram')->string()
                ->set('newsletter', 'Instagram')->numeric()
                ->set('info', Language::$word->ABOUT)->string(true, true);
            
            if ($this->core->enable_tax) {
                $validate
                    ->set('address', Language::$word->M_ADDRESS)->required()->string()->min_len(3)->max_len(80)
                    ->set('city', Language::$word->M_CITY)->required()->string()->min_len(2)->max_len(60)
                    ->set('zip', Language::$word->M_ZIP)->required()->string()->min_len(3)->max_len(30)
                    ->set('state', Language::$word->M_STATE)->required()->string()->min_len(2)->max_len(60)
                    ->set('country', Language::$word->M_COUNTRY)->required()->string()->exact_len(2);
            }
            
            Content::verifyCustomFields('profile');
            $safe = $validate->safe();
            if (count(Message::$msgs) === 0) {
                $data = array(
                    'email' => $safe->email,
                    'lname' => $safe->lname,
                    'fname' => $safe->fname,
                    'newsletter' => (strlen($safe->newsletter) ? 1 : 0),
                    'info' => $safe->info,
                    'fb_link' => $safe->fb_link,
                    'tw_link' => $safe->tw_link,
                    'gp_link' => $safe->gp_link,
                );
                
                if ($this->core->enable_tax) {
                    $data['address'] = $safe->address;
                    $data['city'] = $safe->city;
                    $data['zip'] = $safe->zip;
                    $data['state'] = $safe->state;
                    $data['country'] = $safe->country;
                }
                
                if (strlen($_POST['password'])) {
                    $data['hash'] = Auth::doHash($_POST['password']);
                }
                
                $this->db->update(User::mTable, $data)->where('id', $this->auth->uid, '=')->run();
                
                // Start Custom Fields
                $fl_array = Utility::array_key_exists_wildcard($_POST, 'custom_*', 'key-value');
                if ($fl_array) {
                    foreach ($fl_array as $key => $val) {
                        $cfdata['field_value'] = Validator::sanitize($val);
                        $this->db->update(Content::cfdTable, $cfdata)->where('user_id', $this->auth->uid, '=')->where('field_name', str_replace('custom_', '', $key), '=')->run();
                    }
                }
                
                Message::msgReply(true, 'success', str_replace('[NAME]', '', Language::$word->M_UPDATED));
                if ($this->db->affected()) {
                    Auth::$udata->email = Session::set('email', $data['email']);
                    Auth::$udata->fname = Session::set('fname', $data['fname']);
                    Auth::$udata->lname = Session::set('lname', $data['lname']);
                    Auth::$udata->name = Session::set('name', $data['fname'] . ' ' . $data['lname']);
                    if ($this->core->enable_tax) {
                        Auth::$udata->country = Session::set('country', $data['country']);
                    }
                }
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * avatar
         *
         * @return void
         */
        private function avatar(): void
        {
            if ($avatar = File::upload('avatar', 2097152, 'png,jpg,jpeg')) {
                $a_path = UPLOADS . 'avatars/';
                $img = File::process($avatar, $a_path, 'AVT_', false, false);
                
                File::deleteFile($a_path . Auth::$udata->avatar);
                try {
                    $image = new Image($a_path . $img['fname']);
                    $image->crop($this->core->avatar_w, $this->core->avatar_h)->save($a_path . $img['fname']);
                    
                    $this->db->update(User::mTable, array('avatar' => $img['fname']))->where('id', $this->auth->uid, '=')->run();
                    Auth::$udata->avatar = Session::set('avatar', $img['fname']);
                } catch (Exception $e) {
                    Debug::addMessage('errors', '<i>Error</i>', $e->getMessage(), 'session');
                }
            }
        }
        
        /**
         * membership
         *
         * @return void
         * @throws FileNotFoundException
         * @throws NotFoundException
         */
        private function membership(): void
        {
            if ($row = $this->db->select(Membership::mTable)->where('id', Filter::$id, '=')->where('private', 1, '<')->first()->run()) {
                $gaterows = $this->db->select(Core::gTable)->where('active', 1, '=')->run();
                
                if ($row->price == 0) {
                    $data = array(
                        'membership_id' => $row->id,
                        'mem_expire' => Membership::calculateDays($row->id),
                    );
                    
                    $this->db->update(User::mTable, $data)->where('id', $this->auth->uid, '=')->run();
                    Auth::$udata->membership_id = Session::set('membership_id', $row->id);
                    Auth::$udata->mem_expire = Session::set('mem_expire', $data['mem_expire']);
                    
                    $json['message'] = Message::msgSingleOk(str_replace('[NAME]', $row->{'title' . Language::$lang}, Language::$word->M_INFO12));
                } else {
                    $this->db->delete(Membership::cTable)->where('user_id', $this->auth->uid, '=')->run();
                    $tax = Membership::calculateTax();
                    
                    $data = array(
                        'user_id' => $this->auth->uid,
                        'user_m_id' => $this->auth->uid,
                        'membership_id' => $row->id,
                        'originalprice' => Validator::sanitize($row->price, 'float'),
                        'tax' => Validator::sanitize($tax, 'float'),
                        'totaltax' => Validator::sanitize($row->price * $tax, 'float'),
                        'total' => Validator::sanitize($row->price, 'float'),
                        'totalprice' => Validator::sanitize($tax * $row->price + $row->price, 'float'),
                    );
                    $this->db->insert(Membership::cTable, $data)->run();
                    
                    $this->view->row = $row;
                    $this->view->enable_tax = $this->core->enable_tax;
                    $this->view->gateways = $gaterows;
                    $this->view->cart = Membership::getCart($this->auth->uid);
                    $json['message'] = $this->view->snippet('membershipSummary', 'view/front/themes/' . $this->core->theme . '/snippets/');
                }
            } else {
                $json['type'] = 'error';
            }
            print json_encode($json);
        }
        
        /**
         * coupon
         *
         * @return void
         */
        private function coupon(): void
        {
            $sql = 'SELECT * FROM `' . Content::dcTable . '` WHERE FIND_IN_SET(' . Filter::$id . ', membership_id) AND code = ? AND ctype = ? AND active = ?';
            if ($row = $this->db->rawQuery($sql, array(Validator::sanitize($_POST['code'], 'alphanumeric'), 'membership', 1))->first()->run()) {
                $row2 = $this->db->select(Membership::mTable)->where('id', Filter::$id, '=')->first()->run();
                
                $this->db->delete(Membership::cTable)->where('user_id', $this->auth->uid, '=')->run();
                $tax = Membership::calculateTax();
                
                if ($row->type == 'p') {
                    $disc = Validator::sanitize($row2->price / 100 * $row->discount, 'float');
                } else {
                    $disc = Validator::sanitize($row->discount, 'float');
                }
                $grand_total = Validator::sanitize($row2->price - $disc, 'float');
                
                $data = array(
                    'user_id' => $this->auth->uid,
                    'user_m_id' => $this->auth->uid,
                    'membership_id' => $row2->id,
                    'coupon_id' => $row->id,
                    'tax' => Validator::sanitize($tax, 'float'),
                    'totaltax' => Validator::sanitize($grand_total * $tax, 'float'),
                    'coupon' => $disc,
                    'total' => $grand_total,
                    'originalprice' => Validator::sanitize($row2->price, 'float'),
                    'totalprice' => Validator::sanitize($tax * $grand_total + $grand_total, 'float'),
                );
                $this->db->insert(Membership::cTable, $data)->run();
                $json = array(
                    'type' => 'success',
                    'is_full' => $row->discount,
                    'disc' => '- ' . Utility::formatMoney($disc),
                    'tax' => Utility::formatMoney($data['totaltax']),
                    'grand_total' => Utility::formatMoney($data['totalprice'])
                );
            } else {
                $json['type'] = 'error';
            }
            print json_encode($json);
        }
        
        /**
         * activateCoupon
         *
         * @return void
         */
        private function activateCoupon(): void
        {
            $cart = Membership::getCart($this->auth->uid);
            if ($row = $this->db->select(Membership::mTable)->where('id', $cart->membership_id, '=')->first()->run()) {
                // insert payment record
                $data = array(
                    'txn_id' => time(),
                    'membership_id' => $row->id,
                    'user_id' => $this->auth->uid,
                    'rate_amount' => $cart->total,
                    'coupon' => $cart->coupon,
                    'total' => $cart->totalprice,
                    'tax' => $cart->totaltax,
                    'currency' => $this->core->currency,
                    'ip' => Url::getIP(),
                    'pp' => 'Coupon',
                    'status' => 1,
                );
                $last_id = $this->db->insert(Membership::pTable, $data)->run();
                
                //insert user membership
                $u_data = array(
                    'transaction_id' => $last_id,
                    'user_id' => $this->auth->uid,
                    'membership_id' => $row->id,
                    'expire' => Membership::calculateDays($row->id),
                    'recurring' => 0,
                    'active' => 1,
                );
                
                //update user record
                $x_data = array(
                    'membership_id' => $row->id,
                    'mem_expire' => $u_data['expire'],
                );
                
                $this->db->insert(Membership::umTable, $u_data)->run();
                $this->db->update(User::mTable, $x_data)->where('id', $this->auth->uid, '=')->run();
                $this->db->delete(Membership::cTable)->where('user_id', $this->auth->uid, '=')->run();
                
                $json['type'] = 'success';
            } else {
                $json['type'] = 'error';
            }
            print json_encode($json);
        }
        
        /**
         * gateway
         *
         * @return void
         * @throws FileNotFoundException
         */
        private function gateway(): void
        {
            if ($cart = Membership::getCart($this->auth->uid)) {
                $gateway = $this->db->select(Core::gTable)->where('id', Filter::$id, '=')->where('active', 1, '=')->first()->run();
                $row = $this->db->select(Membership::mTable)->where('id', $cart->membership_id, '=')->first()->run();
                $this->view->cart = $cart;
                $this->view->gateway = $gateway;
                $this->view->row = $row;
                $json = array(
                    'gateway' => $gateway->name,
                    'message' => $this->view->snippet('form', 'gateways/' . $gateway->dir . '/')
                );
            } else {
                $json['message'] = Message::msgSingleError(Language::$word->SYSERROR);
            }
            print json_encode($json);
        }
        
        /**
         * invoice
         *
         * @return void
         * @throws FileNotFoundException
         * @throws MpdfException
         */
        #[NoReturn] private function invoice(): void
        {
            if ($row = User::userInvoice(Filter::$id, $this->auth->uid)) {
                $this->view->row = $row;
                $this->view->user = Auth::$userdata;
                $this->view->core = $this->core;
                
                $title = Validator::sanitize($row->title, 'alpha');
                
                require_once(BASEPATH . 'vendor/mPdf/vendor/autoload.php');
                $mpdf = new Mpdf(['mode' => 'utf-8']);
                $mpdf->SetTitle($title);
                $mpdf->WriteHTML($this->view->snippet('invoice', 'view/front/themes/' . $this->core->theme . '/snippets/'));
                $mpdf->Output($title . '.pdf', 'D');
            }
            exit;
        }
    }