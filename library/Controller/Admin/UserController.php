<?php
    /**
     * UserController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: UserController.php, v1.00 5/9/2023 8:48 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use PHPMailer\PHPMailer\Exception;
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Core;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Core\Mailer;
    use Wojo\Core\Membership;
    use Wojo\Core\Module;
    use Wojo\Core\Plugin;
    use Wojo\Core\Router;
    use Wojo\Core\User;
    use Wojo\Database\Paginator;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Exception\NotFoundException;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Stats\Stats;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class UserController extends Controller
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
            $this->view->subtitle = null;
            
            $where = match ($this->view->auth->usertype) {
                'owner' => 'WHERE (type = \'staff\' || type = \'editor\' || type = \'member\')',
                'staff' => 'WHERE (type = \'editor\' || type = \'member\')',
                'editor' => 'WHERE (type = \'member\')',
                default => null,
            };
            $lg = Language::$lang;
            $find = isset($_POST['find']) ? Validator::sanitize($_POST['find'], 'string', 20) : null;
            $counter = 0;
            $and = null;
            
            if (isset($_GET['letter']) and $find) {
                $letter = Validator::sanitize($_GET['letter'], 'string', 2);
                $counter = $this->db->count(User::mTable, "$where AND `fname` LIKE '%" . trim($find) . "%' OR `lname` LIKE '%" . trim($find) . "%' OR `email` LIKE '%" . trim($find) . "%' AND `fname` REGEXP '^" . $letter . "'")->run();
                $and = "AND `fname` LIKE '%" . trim($find) . "%' OR `lname` LIKE '%" . trim($find) . "%' OR `email` LIKE '%" . trim($find) . "%' AND `fname` REGEXP '^" . $letter . "'";
                
            } elseif (isset($_POST['find'])) {
                $counter = $this->db->count(User::mTable, "$where AND `fname` LIKE '%" . trim($find) . "%' OR `lname` LIKE '%" . trim($find) . "%' OR `email` LIKE '%" . trim($find) . "%'")->run();
                $and = "AND `fname` LIKE '%" . trim($find) . "%' OR `lname` LIKE '%" . trim($find) . "%' OR `email` LIKE '%" . trim($find) . "%'";
                
            } elseif (isset($_GET['letter'])) {
                $letter = Validator::sanitize($_GET['letter'], 'string', 2);
                $and = "AND `fname` REGEXP '^" . $letter . "'";
                $counter = $this->db->count(User::mTable, "$where AND `fname` REGEXP '^" . $letter . "' LIMIT 1")->run();
            } else {
                if (isset($_GET['type'])) {
                    switch ($_GET['type']) {
                        case 'registered':
                            $counter = $this->db->count(User::mTable, "$where AND `type` = 'member'")->run();
                            $and = "AND u.type = 'member'";
                            $this->view->subtitle = Language::$word->AD_RUSER;
                            break;
                        case 'active':
                            $counter = $this->db->count(User::mTable, "$where AND `type` = 'member' AND active = 'y'")->run();
                            $and = "AND u.type = 'member' AND u.active = 'y'";
                            $this->view->subtitle = Language::$word->AD_AUSER;
                            break;
                        case 'pending':
                            $counter = $this->db->count(User::mTable, "$where AND `type` = 'member' AND active = 't'")->run();
                            $and = "AND u.type = 'member' AND u.active = 't'";
                            $this->view->subtitle = Language::$word->AD_PUSER;
                            break;
                        
                        case 'membership':
                            $counter = $this->db->count(User::mTable, "$where AND `type` = 'member' AND membership_id <> 0")->run();
                            $and = "AND u.type = 'member' AND u.membership_id <> 0";
                            $this->view->subtitle = Language::$word->AD_AMEM;
                            break;
                    }
                } else {
                    $counter = $this->db->count(User::mTable)->run();
                }
            }
            
            if (isset($_GET['order']) and count(explode('|', $_GET['order'])) == 2) {
                list($sort, $order) = explode('|', $_GET['order']);
                $sort = Validator::sanitize($sort, 'default', 13);
                $order = Validator::sanitize($order, 'default', 5);
                if (in_array($sort, array('fname', 'email', 'membership_id'))) {
                    $ord = ($order == 'DESC') ? ' DESC' : ' ASC';
                    $sorting = $sort . $ord;
                } else {
                    $sorting = ' created DESC';
                }
            } else {
                $sorting = ' created DESC';
            }
            
            $pager = Paginator::instance();
            $pager->items_total = $counter;
            $pager->default_ipp = $this->view->core->perpage;
            $pager->path = Url::url(Router::$path, '?');
            $pager->paginate();
            
            $sql = "
            SELECT *, u.id as id,  u.active as active, CONCAT(fname,' ',lname) as fullname, m.title$lg as mtitle, m.thumb
              FROM   `" . User::mTable . '` as u
              LEFT JOIN ' . Membership::mTable . " as m on m.id = u.membership_id
              $where
              $and
              ORDER BY $sorting" . $pager->limit;
            
            $this->view->crumbs = ['admin', Language::$word->META_T2];
            $this->view->title = Language::$word->META_T2;
            $this->view->caption = Language::$word->META_T2;
            $this->view->data = $this->db->rawQuery($sql)->run();
            $this->view->pager = $pager;
            
            $this->view->render('user', 'view/admin/');
        }
        
        /**
         * new
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function new(): void
        {
            $this->view->mlist = Membership::getMembershipList();
            $this->view->clist = $this->db->select(Content::cTable)->orderBy('sorting', 'DESC')->run();
            $this->view->custom_fields = Content::renderCustomFields(0, 'profile');
            
            $this->view->crumbs = ['admin', Language::$word->M_TITLE5, 'new'];
            $this->view->caption = Language::$word->M_TITLE5;
            $this->view->title = Language::$word->M_TITLE5;
            $this->view->subtitle = [];
            $this->view->render('user', 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'users', 'edit'];
            $this->view->title = Language::$word->M_TITLE4;
            $this->view->caption = Language::$word->M_TITLE4;
            $this->view->subtitle = [];
            
            if (!$row = $this->db->select(User::mTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->mlist = Membership::getMembershipList();
                $this->view->clist = $this->db->select(Content::cTable)->orderBy('sorting', 'DESC')->run();
                $this->view->custom_fields = Content::renderCustomFields($row->id, 'profile');
                $this->view->modlist = $this->db->select(Module::mTable, array('modalias', 'title' . Language::$lang))->where('hasconfig', 1, '=')->run();
                $this->view->pluglist = $this->db->select(Plugin::mTable, array('plugalias', 'title' . Language::$lang))->where('hasconfig', 1, '=')->run();
                $this->view->render('user', 'view/admin/');
            }
        }
        
        /**
         * history
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function history(): void
        {
            $this->view->crumbs = ['admin', 'users', 'history'];
            $this->view->title = Language::$word->META_T3;
            $this->view->caption = Language::$word->META_T3;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(User::mTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->mlist = Stats::userHistory($row->id);
                $this->view->plist = Stats::userPayments($row->id);
                
                $this->view->subtitle = $row->fname . ' ' . $row->lname;
                $this->view->render('user', 'view/admin/');
            }
        }
        
        /**
         * action
         *
         * @return void
         * @throws Exception
         * @throws FileNotFoundException
         * @throws NotFoundException
         */
        public function action(): void
        {
            $postAction = Validator::post('action');
            if ($postAction) {
                if (IS_AJAX) {
                    switch ($postAction) {
                        case 'add':
                        case 'update':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->process();
                            break;
                        
                        case 'resend':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->resend();
                            break;
                        
                        case 'trash':
                            IS_DEMO ? Message::msgReply(true, 'success', Language::$word->PROCESS_ERR_DEMO) : $this->_trash();
                            break;
                        
                        default:
                            Url::invalidMethod();
                            break;
                    }
                } else {
                    Url::invalidMethod();
                }
            }
            
            $getAction = Validator::get('action');
            if ($getAction) {
                switch ($getAction) {
                    case 'exportUsers':
                        $this->exportUsers();
                        break;
                    
                    case 'exportPayments':
                        $this->exportPayments();
                        break;
                    
                    case 'chart':
                        $this->chart();
                        break;
                    
                    case 'resend':
                        $this->view->data = $this->db->select(User::mTable, array('id', 'email', "CONCAT(fname,' ',lname) as name"))->where('id', Filter::$id, '=')->first()->run();
                        $this->view->render('resendNotification', 'view/admin/snippets/', false);
                        break;
                    
                    default:
                        Url::invalidMethod();
                        break;
                }
            }
        }
        
        /**
         * process
         *
         * @return void
         * @throws Exception
         * @throws NotFoundException
         */
        private function process(): void
        {
            $validate = Validator::run($_POST);
            $validate
                ->set('fname', Language::$word->M_FNAME)->required()->string()->min_len(3)->max_len(60)
                ->set('lname', Language::$word->M_LNAME)->required()->string()->min_len(3)->max_len(60)
                ->set('email', Language::$word->M_EMAIL)->email()
                ->set('type', Language::$word->M_SUB9)->required()->alpha()
                ->set('active', Language::$word->STATUS)->required()->string()->exact_len(1)->lowercase()
                ->set('newsletter', Language::$word->M_SUB10)->required()->numeric()
                ->set('membership_id', Language::$word->M_SUB8)->numeric()
                ->set('notes', Language::$word->M_SUB11)->string()
                ->set('address', Language::$word->M_ADDRESS)->string()
                ->set('city', Language::$word->M_CITY)->string()
                ->set('state', Language::$word->M_STATE)->string()
                ->set('zip', Language::$word->M_ZIP)->string()
                ->set('country', Language::$word->STATUS)->string();
            
            if (Validator::post('extend_membership')) {
                $validate->set('mem_expire_submit', Language::$word->M_SUB15)->date();
            }
            
            (Filter::$id) ? $this->_update($validate) : $this->_add($validate);
        }
        
        /**
         * _update
         *
         * @param Validator $validate
         * @return void
         * @throws NotFoundException
         */
        private function _update(Validator $validate): void
        {
            $safe = $validate->safe();
            
            Content::verifyCustomFields('profile');
            if (count(Message::$msgs) === 0) {
                $data = array(
                    'email' => $safe->email,
                    'lname' => $safe->lname,
                    'fname' => $safe->fname,
                    'address' => $safe->address,
                    'city' => $safe->city,
                    'state' => $safe->state,
                    'zip' => $safe->zip,
                    'country' => $safe->country,
                    'type' => $safe->type,
                    'active' => $safe->active,
                    'newsletter' => $safe->newsletter,
                    'notes' => $safe->notes,
                    'modaccess' => (array_key_exists('modaccess', $_POST)) ? Utility::implodeFields($_POST['modaccess']) : 'NULL',
                    'plugaccess' => (array_key_exists('plugaccess', $_POST)) ? Utility::implodeFields($_POST['plugaccess']) : 'NULL',
                    'userlevel' => ($safe->type == 'staff' ? 8 : ($safe->type == 'editor' ? 7 : 1)),
                );
                
                if (strlen($_POST['password']) !== 0) {
                    $data['hash'] = $this->view->auth->doHash($_POST['password']);
                }
                
                if (Validator::post('update_membership')) {
                    if ($_POST['membership_id'] > 0) {
                        $data['mem_expire'] = Membership::calculateDays($safe->membership_id);
                        $data['membership_id'] = $safe->membership_id;
                    } else {
                        $data['mem_expire'] = 'NULL';
                        $data['membership_id'] = 0;
                    }
                }
                
                if (Validator::post('extend_membership')) {
                    $data['mem_expire'] = $this->db->toDate($safe->mem_expire_submit);
                }
                
                $this->db->update(User::mTable, $data)->where('id', Filter::$id, '=')->run();
                
                // Start Custom Fields
                $fl_array = Utility::array_key_exists_wildcard($_POST, 'custom_*', 'key-value');
                if ($fl_array) {
                    foreach ($fl_array as $key => $val) {
                        $cfdata['field_value'] = Validator::sanitize($val);
                        $this->db->update(Content::cfdTable, $cfdata)->where('user_id', Filter::$id, '=')->where('field_name', str_replace('custom_', '', $key), '=')->run();
                    }
                }
                
                $message = Message::formatSuccessMessage($data['fname'] . ' ' . $data['lname'], Language::$word->M_UPDATED);
                Message::msgReply(true, 'success', $message);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * _add
         *
         * @param Validator $validate
         * @return void
         * @throws Exception
         * @throws NotFoundException
         */
        private function _add(Validator $validate): void
        {
            $validate->set('password', Language::$word->M_PASSWORD)->required()->string()->min_len(6)->max_len(20);
            
            $safe = $validate->safe();
            
            if (strlen($safe->email) !== 0) {
                if ($this->view->auth->emailExists($safe->email)) {
                    Message::$msgs['email'] = Language::$word->M_EMAIL_R2;
                }
            }
            
            Content::verifyCustomFields('profile');
            
            if (count(Message::$msgs) === 0) {
                $hash = $this->view->auth->doHash($safe->password);
                $username = Utility::randomString();
                
                $data = array(
                    'username' => $username,
                    'email' => $safe->email,
                    'lname' => $safe->lname,
                    'fname' => $safe->fname,
                    'address' => $safe->address,
                    'city' => $safe->city,
                    'state' => $safe->state,
                    'zip' => $safe->zip,
                    'country' => $safe->country,
                    'hash' => $hash,
                    'type' => $safe->type,
                    'active' => $safe->active,
                    'newsletter' => $safe->newsletter,
                    'notes' => $safe->notes,
                    'userlevel' => ($safe->type == 'staff' ? 8 : ($safe->type == 'editor' ? 7 : 1)),
                );
                
                if ($_POST['membership_id'] > 0) {
                    $data['mem_expire'] = Membership::calculateDays($safe->membership_id);
                    $data['membership_id'] = $safe->membership_id;
                }
                
                if (Validator::post('extend_membership')) {
                    $data['mem_expire'] = $this->db->toDate($safe->mem_expire_submit);
                }
                
                $last_id = $this->db->insert(User::mTable, $data)->run();
                
                // Start Custom Fields
                $fl_array = Utility::array_key_exists_wildcard($_POST, 'custom_*', 'key-value');
                $dataArray = array();
                if ($fl_array) {
                    $fields = $this->db->select(Content::cfTable)->run();
                    foreach ($fields as $row) {
                        $dataArray[] = array(
                            'user_id' => $last_id,
                            'field_id' => $row->id,
                            'field_name' => $row->name,
                            'section' => 'profile',
                        );
                    }
                    $this->db->batch(Content::cfdTable, $dataArray)->run();
                    
                    foreach ($fl_array as $key => $val) {
                        $cfdata['field_value'] = Validator::sanitize($val);
                        $this->db->update(Content::cfdTable, $cfdata)->where('user_id', $last_id, '=')->where('field_name', str_replace('custom_', '', $key), '=')->run();
                    }
                }
                
                if ($last_id) {
                    $message = Message::formatSuccessMessage($data['fname'] . ' ' . $data['lname'], Language::$word->M_ADDED);
                    Message::msgReply(true, 'success', $message);
                    
                    if (Validator::post('notify') && intval($_POST['notify']) == 1) {
                        $lg = Language::$lang;
                        $tpl = $this->db->select(Content::eTable, array("body$lg, subject$lg"))->where('typeid', 'regMailAdmin', '=')->first()->run();
                        $pass = Validator::cleanOut($_POST['password']);
                        $mailer = Mailer::sendMail();
                        $core = $this->core;
                        
                        $body = str_replace(array(
                            '[LOGO]',
                            '[EMAIL]',
                            '[NAME]',
                            '[DATE]',
                            '[COMPANY]',
                            '[SITE_NAME]',
                            '[USERNAME]',
                            '[PASSWORD]',
                            '[LINK]',
                            '[CEMAIL]',
                            '[FB]',
                            '[TW]',
                            '[SITEURL]'
                        ), array(
                            $core->plogo,
                            $data['email'],
                            $data['fname'] . ' ' . $data['lname'],
                            date('Y'),
                            $core->company,
                            $core->site_name,
                            $username,
                            $pass,
                            Url::url('login'),
                            $core->site_email,
                            $core->social->facebook,
                            $core->social->twitter,
                            SITEURL
                        ), $tpl->{'body' . $lg});
                        
                        $mailer->Subject = $tpl->{'subject' . $lg};
                        $mailer->Body = $body;
                        $mailer->setFrom($core->site_email, $core->company);
                        $mailer->addAddress($data['email'], $data['fname'] . ' ' . $data['lname']);
                        $mailer->isHTML();
                        $mailer->send();
                    }
                }
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * exportUsers
         *
         * @return void
         */
        private function exportUsers(): void
        {
            header('Pragma: no-cache');
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=UserList.csv');
            
            $data = fopen('php://output', 'w');
            fputcsv($data, array(
                    Language::$word->NAME,
                    Language::$word->MEMBERSHIP,
                    Language::$word->MEM_EXP,
                    Language::$word->M_EMAIL1,
                    Language::$word->ADM_NEWSL,
                    Language::$word->CREATED
                )
            );
            
            $result = Stats::exportUsers();
            if ($result) {
                foreach ($result as $row) {
                    fputcsv($data, $row);
                }
                fclose($data);
            }
        }
        
        /**
         * exportPayments
         *
         * @return void
         */
        private function exportPayments(): void
        {
            header('Pragma: no-cache');
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=UserPayments.csv');
            
            $data = fopen('php://output', 'w');
            fputcsv($data, array(
                    'TXN ID',
                    Language::$word->NAME,
                    Language::$word->TRX_AMOUNT,
                    Language::$word->TRX_TAX,
                    Language::$word->TRX_COUPON,
                    Language::$word->TRX_TOTAMT,
                    Language::$word->CURRENCY,
                    Language::$word->TRX_PP,
                    Language::$word->CREATED
                )
            );
            
            $result = Stats::exportUserPayments(Filter::$id);
            if ($result) {
                foreach ($result as $row) {
                    fputcsv($data, $row);
                }
                fclose($data);
            }
        }
        
        /**
         * chart
         *
         * @return void
         */
        private function chart(): void
        {
            $data = Stats::getUserPaymentsChart(Filter::$id);
            print json_encode($data);
        }
        
        /**
         * resend
         *
         * @return void
         * @throws Exception
         */
        private function resend(): void
        {
            $lg = Language::$lang;
            
            $row = $this->db->select(User::mTable, array('email', 'fname', 'lname', 'token', 'id'))->where('id', Filter::$id, '=')->first()->run();
            $tpl = $this->db->select(Content::eTable, array("body$lg", "subject$lg"))->where('typeid', 'regMail', '=')->first()->run();
            
            $temp = Utility::randNumbers();
            $data['hash'] = $this->view->auth->doHash($temp);
            $this->db->update(User::mTable, $data)->where('id', $row->id, '=')->run();
            
            $mailer = Mailer::sendMail();
            $core = $this->core;
            $url = Url::url($core->system_slugs->login[0]->{'slug' . $lg}, '?token=' . $row->token);
            
            $body = str_replace(array(
                '[LOGO]',
                '[NAME]',
                '[DATE]',
                '[COMPANY]',
                '[SITE_NAME]',
                '[USERNAME]',
                '[PASSWORD]',
                '[LINK]',
                '[CEMAIL]',
                '[FB]',
                '[TW]',
                '[SITEURL]'
            ), array(
                $core->plogo,
                $row->fname . ' ' . $row->lname,
                date('Y'),
                $core->company,
                $core->site_name,
                $row->email,
                $temp,
                $url,
                $core->site_email,
                $core->social->facebook,
                $core->social->twitter,
                SITEURL
            ), $tpl->{'body' . $lg});
            
            $mailer->setFrom($core->site_email, $core->company);
            $mailer->addAddress($row->email, $row->fname . ' ' . $row->lname);
            
            $mailer->isHTML();
            $mailer->Subject = $tpl->{'subject' . $lg};
            $mailer->Body = $body;
            
            if ($mailer->send()) {
                $json['type'] = 'success';
                $json['title'] = Language::$word->SUCCESS;
                $json['message'] = Language::$word->M_INFO5;
            } else {
                $json['type'] = 'error';
                $json['title'] = Language::$word->ERROR;
                $json['message'] = Language::$word->SENDERROR;
                
            }
            print json_encode($json);
        }
        
        /**
         * _trash
         *
         * @return void
         */
        private function _trash(): void
        {
            $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
            
            if ($row = $this->db->select(User::mTable)->where('id', Filter::$id, '=')->where('type', 'owner', '<>')->first()->run()) {
                $data = array(
                    'type' => 'user',
                    'parent_id' => Filter::$id,
                    'dataset' => json_encode($row)
                );
                $this->db->insert(Core::txTable, $data)->run();
                
            }
            $json['type'] = 'success';
            $json['title'] = Language::$word->SUCCESS;
            $json['message'] = str_replace('[NAME]', $title, Language::$word->M_TRASH_OK);
            print json_encode($json);
            Logger::writeLog($json['message']);
        }
    }