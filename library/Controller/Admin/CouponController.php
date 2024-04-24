<?php
    /**
     * CouponController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: CouponController.php, v1.00 5/8/2023 8:41 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Core;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Core\Membership;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class CouponController extends Controller
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
            $this->view->data = $this->db->select(Content::dcTable)->where('ctype', 'membership', '=')->run();
            
            $this->view->crumbs = ['admin', Language::$word->ADM_COUPONS];
            $this->view->caption = Language::$word->DC_TITLE;
            $this->view->title = Language::$word->META_T25;
            $this->view->subtitle = Language::$word->DC_SUB;
            $this->view->render('coupon', 'view/admin/');
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
            
            $this->view->crumbs = ['admin', 'coupons', 'new'];
            $this->view->caption = Language::$word->META_T27;
            $this->view->title = Language::$word->META_T27;
            $this->view->subtitle = [];
            $this->view->render('coupon', 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'coupons', 'edit'];
            $this->view->title = Language::$word->META_T26;
            $this->view->caption = Language::$word->META_T26;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Content::dcTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->mlist = Membership::getMembershipList();
                $this->view->render('coupon', 'view/admin/');
            }
        }
        
        /**
         * action
         *
         * @return void
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
                        
                        case 'status':
                            IS_DEMO ? print '0' : $this->status();
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
        }
        
        /**
         * process
         *
         * @return void
         */
        public function process(): void
        {
            $validate = Validator::run($_POST);
            $validate
                ->set('title', Language::$word->NAME)->required()->string()->min_len(3)->max_len(60)
                ->set('code', Language::$word->DC_CODE)->required()->string()
                ->set('discount', Language::$word->DC_DISC)->required()->numeric()->min_numeric(1)->max_numeric(100)
                ->set('type', Language::$word->DC_TYPE)->required()->string()
                ->set('active', Language::$word->PUBLISHED)->required()->numeric();
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data = array(
                    'title' => $safe->title,
                    'code' => $safe->code,
                    'discount' => $safe->discount,
                    'type' => $safe->type,
                    'ctype' => 'membership',
                    'membership_id' => Validator::post('membership_id') ? Utility::implodeFields($_POST['membership_id']) : 0,
                    'active' => $safe->active,
                );
                
                (Filter::$id) ? $this->db->update(Content::dcTable, $data)->where('id', Filter::$id, '=')->run() : $this->db->insert(Content::dcTable, $data)->run();
                
                $message = Filter::$id ?
                    Message::formatSuccessMessage($data['title'], Language::$word->DC_UPDATE_OK) :
                    Message::formatSuccessMessage($data['title'], Language::$word->DC_ADDED_OK);
                
                Message::msgReply($this->db->affected(), 'success', $message);
                Logger::writeLog($message);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * status
         *
         * @return void
         */
        private function status(): void
        {
            $this->db->update(Content::dcTable, array('active' => intval($_POST['active'])))->where('id', Filter::$id, '=')->run();
        }
        
        /**
         * _trash
         *
         * @return void
         */
        private function _trash(): void
        {
            $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
            
            if ($row = $this->db->select(Content::dcTable)->where('id', Filter::$id, '=')->first()->run()) {
                $data = array(
                    'type' => 'coupon',
                    'parent_id' => Filter::$id,
                    'dataset' => json_encode($row)
                );
                $this->db->insert(Core::txTable, $data)->run();
                $this->db->delete(Content::dcTable)->where('id', $row->id, '=')->run();
            }
            $json = array(
                'type' => 'success',
                'title' => Language::$word->SUCCESS,
                'message' => str_replace('[NAME]', $title, Language::$word->DC_TRASH_OK),
            );
            print json_encode($json);
            Logger::writeLog($json['message']);
        }
    }