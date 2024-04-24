<?php
    /**
     * DonateController CLass
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: DonateController.php, v1.00 5/15/2023 8:50 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin\Plugin\Donate;
    
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Core;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Core\Plugin;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Exception\NotFoundException;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Plugin\Donate\Donate;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class DonateController extends Controller
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
            $this->view->data = Donate::getAllDonations();
            
            $this->view->crumbs = ['admin', 'plugins', Language::$word->_PLG_DP_TITLE];
            $this->view->caption = Language::$word->_PLG_DP_TITLE;
            $this->view->title = Language::$word->_PLG_DP_TITLE;
            $this->view->subtitle = Language::$word->_PLG_DP_INFO;
            $this->view->render('index', 'view/admin/plugins/donate/view/', true, 'view/admin/');
        }
        
        /**
         * new
         *
         * @return void
         * @throws FileNotFoundException
         * @throws NotFoundException
         */
        public function new(): void
        {
            $this->view->gateways = $this->db->select(Core::gTable)->run();
            $this->view->pagelist = Content::getPageList();
            
            $this->view->crumbs = ['admin', 'plugins', 'poll', 'new'];
            $this->view->caption = Language::$word->_PLG_DP_SUB;
            $this->view->title = Language::$word->_PLG_DP_TITLE1;
            $this->view->subtitle = null;
            $this->view->render('index', 'view/admin/plugins/donate/view/', true, 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         * @throws NotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'plugins', 'donate', 'edit'];
            $this->view->title = Language::$word->_PLG_DP_SUB4;
            $this->view->caption = Language::$word->_PLG_DP_SUB4;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Donate::mTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->gateways = $this->db->select(Core::gTable)->run();
                $this->view->pagelist = Content::getPageList();
                $this->view->render('index', 'view/admin/plugins/donate/view/', true, 'view/admin/');
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
                        
                        case 'delete':
                            IS_DEMO ? Message::msgReply(true, 'success', Language::$word->PROCESS_ERR_DEMO) : $this->_delete();
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
                    case 'export':
                        $this->export();
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
         */
        private function process(): void
        {
            $validate = Validator::run($_POST);
            $validate
                ->set('title', Language::$word->_PLG_DP_SUB1)->required()->string()->min_len(3)->max_len(80)
                ->set('target_amount', Language::$word->_PLG_DP_TARGET)->required()->float()
                ->set('redirect_page', Language::$word->_PLG_DP_SUB3)->required()->numeric();
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data_m = array();
                $data = array(
                    'title' => $safe->title,
                    'target_amount' => $safe->target_amount,
                    'redirect_page' => $safe->redirect_page,
                );
                
                $last_id = 0;
                (Filter::$id) ? $this->db->update(Donate::mTable, $data)->where('id', Filter::$id, '=')->run() : $last_id = $this->db->insert(Donate::mTable, $data)->run();
                
                $message = Filter::$id ?
                    Message::formatSuccessMessage($data['title'], Language::$word->_PLG_DP_UPDATE_OK) :
                    Message::formatSuccessMessage($data['title'], Language::$word->_PLG_DP_ADDED_OK);
                
                Message::msgReply($this->db->affected(), 'success', $message);
                Logger::writeLog($message);
                
                if (!Filter::$id) {
                    // Insert new multi plugin
                    $plugin_id = 'donate/' . Utility::randomString();
                    File::makeDirectory(FPLUGPATH . $plugin_id);
                    File::copyFile(FPLUGPATH . 'donate/master.php', FPLUGPATH . $plugin_id . '/index.tpl.php');
                    
                    $pid = $this->db->select(Plugin::mTable, array('id'))->where('plugalias', 'donate', '=')->first()->run();
                    foreach ($this->core->langlist as $lang) {
                        $data_m['title_' . $lang->abbr] = $safe->title;
                    }
                    $data_x = array(
                        'parent_id' => $pid->id,
                        'plugin_id' => $last_id,
                        'groups' => 'donate',
                        'icon' => 'donate/thumb.svg',
                        'plugalias' => $plugin_id,
                        'active' => 1,
                    );
                    $this->db->insert(Plugin::mTable, array_merge($data_m, $data_x))->run();
                }
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * export
         *
         * @return void
         */
        private function export(): void
        {
            if ($row = $this->db->select(Donate::mTable, array('id', 'title'))->where('id', Filter::$id, '=')->first()->run()) {
                header('Pragma: no-cache');
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=OrderHistory_' . Url::doSeo($row->title) . '.csv');
                
                $data = fopen('php://output', 'w');
                fputcsv($data, array(
                        Language::$word->NAME,
                        Language::$word->M_EMAIL1,
                        Language::$word->TRX_AMOUNT,
                        Language::$word->TRX_PP,
                        Language::$word->DATE
                    )
                );
                
                $array = Donate::exportDonations($row->id);
                $result = json_decode(json_encode($array), true);
                
                if ($result) {
                    foreach ($result as $row) {
                        fputcsv($data, $row);
                    }
                }
            }
        }
        
        /**
         * _delete
         *
         * @return void
         */
        private function _delete(): void
        {
            $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
            
            $res = $this->db->delete(Donate::mTable)->where('id', Filter::$id, '=')->run();
            $this->db->delete(Donate::dTable)->where('parent_id', Filter::$id, '=')->run();
            if ($row = $this->db->select(Plugin::mTable, array('id', 'plugalias'))->where('plugin_id', Filter::$id, '=')->where('groups', 'donate', '=')->first()->run()) {
                $this->db->delete(Content::lTable)->where('plug_id', $row->id, '=')->run();
                $this->db->delete(Plugin::mTable)->where('id', $row->id, '=')->run();
                
                File::deleteDirectory(FPLUGPATH . $row->plugalias);
            }
            
            $message = str_replace('[NAME]', $title, Language::$word->_PLG_DP_DEL_OK);
            Message::msgReply($res, 'success', $message);
            Logger::writeLog($message);
        }
    }