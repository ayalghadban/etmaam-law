<?php
    /**
     * PageController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: PageController.php, v1.00 5/7/2023 8:39 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use ArrayIterator;
    use LimitIterator;
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Core;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Core\Membership;
    use Wojo\Core\Module;
    use Wojo\Core\Router;
    use Wojo\Database\Paginator;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Exception\NotFoundException;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class PageController extends Controller
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
            $pager = Paginator::instance();
            $pager->items_total = ($this->view->auth->usertype <> 'owner') ?
                $this->db->count(Content::pTable)->where('is_admin', 1, '=')->run() :
                $this->db->count(Content::pTable)->run();
            $pager->default_ipp = $this->core->perpage;
            $pager->path = Url::url(Router::$path, '?');
            $pager->paginate();
            $lg = Language::$lang;
            
            $where = ($this->view->auth->usertype <> 'owner') ? 'WHERE is_admin = < 1' : null;
            $row = $this->db->rawQuery('SELECT * FROM `' . Content::pTable . "` $where ORDER BY page_type, title$lg $pager->limit")->run();
            
            $this->view->data = $row;
            $this->view->pager = $pager;
            $this->view->crumbs = ['admin', Language::$word->ADM_PAGES];
            $this->view->caption = Language::$word->META_T8;
            $this->view->title = Language::$word->META_T8;
            $this->view->subtitle = [];
            $this->view->render('page', 'view/admin/');
        }
        
        /**
         * new
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function new(): void
        {
            $this->view->langlist = $this->core->langlist;
            
            $this->view->module_list = Module::getModuleList();
            $this->view->membership_list = Membership::getMembershipList();
            $this->view->access_list = Membership::getAccessList();
            
            $this->view->crumbs = ['admin', Language::$word->ADM_PAGES, 'new'];
            $this->view->caption = Language::$word->PAG_SUB4;
            $this->view->title = Language::$word->PAG_SUB4;
            $this->view->subtitle = [];
            $this->view->render('page', 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'pages', 'edit'];
            $this->view->title = Language::$word->META_T9;
            $this->view->caption = Language::$word->META_T9;
            $this->view->subtitle = [];
            
            if (!$row = $this->db->select(Content::pTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->langlist = $this->core->langlist;
                $this->view->membership_list = Membership::getMembershipList();
                $this->view->access_list = Membership::getAccessList();
                $this->view->image = Utility::parseImageTags($row->body_en);
                
                $this->view->render('page', 'view/admin/');
            }
        }
        
        /**
         * action
         *
         * @return void
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
                        
                        case 'copy':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->copy();
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
                if (IS_AJAX) {
                    switch ($getAction) {
                        case 'copy':
                            $this->view->data = $this->db->select(Content::pTable)->where('id', Filter::$id, '=')->first()->run();
                            $this->view->render('copyPage', 'view/admin/snippets/', false);
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
            foreach ($this->core->langlist as $lang) {
                $validate
                    ->set('title_' . $lang->abbr, Language::$word->PAG_NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>')->required()->string()->min_len(3)->max_len(80)
                    ->set('slug_' . $lang->abbr, Language::$word->PAG_SLUG)->string()
                    ->set('caption_' . $lang->abbr, Language::$word->PAG_CAPTION . ' <span class="flag icon ' . $lang->abbr . '"></span>')->string()
                    ->set('custom_bg_' . $lang->abbr, Language::$word->BGIMG . ' <span class="flag icon ' . $lang->abbr . '"></span>')->path()
                    ->set('keywords_' . $lang->abbr, Language::$word->METAKEYS)->string(true, true)
                    ->set('description_' . $lang->abbr, Language::$word->METADESC)->string(true, true);
            }
            
            $validate
                ->set('is_admin', Language::$word->PAG_PGADM)->required()->numeric()
                ->set('active', Language::$word->PUBLISHED)->required()->numeric()
                ->set('is_comments', Language::$word->PAG_MDLCOMMENT)->required()->numeric()
                ->set('show_header', Language::$word->PAG_NOHEAD)->required()->numeric()
                ->set('access', Language::$word->PAG_ACCLVL)->string()
                ->set('main_image', Language::$word->MAINIMAGE)->string();
            //->set("jscode", Language::$word->PAG_JSCODE)->text("script")
            
            if ($_POST['access'] == 'Membership' and strlen($_POST['membership_id']) === 0) {
                $validate->set('access', Language::$word->PAG_MEMLVL)->required()->numeric()->min_len(1)->max_len(2);
            }
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data_m = array();
                foreach ($this->core->langlist as $i => $lang) {
                    $slug[$i] = (strlen($safe->{'slug_' . $lang->abbr}) === 0)
                        ? Url::doSeo($safe->{'title_' . $lang->abbr})
                        : Url::doSeo($safe->{'slug_' . $lang->abbr});
                    $data_m['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
                    $data_m['slug_' . $lang->abbr] = $slug[$i];
                    $data_m['caption_' . $lang->abbr] = $safe->{'caption_' . $lang->abbr};
                    $data_m['custom_bg_' . $lang->abbr] = $safe->{'custom_bg_' . $lang->abbr};
                    $data_m['keywords_' . $lang->abbr] = $safe->{'keywords_' . $lang->abbr};
                    $data_m['description_' . $lang->abbr] = $safe->{'description_' . $lang->abbr};
                }
                
                $data_x = array(
                    'is_comments' => $safe->is_comments,
                    'membership_id' => array_key_exists('memberships', $_POST) ? Utility::implodeFields($_POST['memberships']) : 0,
                    'active' => $safe->active,
                    'is_admin' => $safe->is_admin,
                    'main_image' => $safe->main_image,
                    //'jscode' => json_encode($safe->jscode),
                    'show_header' => $safe->show_header,
                    'access' => $safe->access,
                );
                
                if (Filter::$id) {
                    $data_s = array();
                    foreach ($this->core->langlist as $lang) {
                        $data_s['page_slug_' . $lang->abbr] = $data_m['slug_' . $lang->abbr];
                    }
                    $this->db->update(Content::mTable, $data_s)->where('page_id', Filter::$id, '=')->run();
                } else {
                    $data_x['created_by'] = $this->view->auth->uid;
                    $data_x['created_by_name'] = $this->view->auth->name;
                }
                
                $data = array_merge($data_m, $data_x);
                $last_id = 0;
                
                (Filter::$id) ? $this->db->update(Content::pTable, $data)->where('id', Filter::$id, '=')->run() : $last_id = $this->db->insert(Content::pTable, $data)->run();
                //Process system page slugs
                Url::doSystemPageSlugs();
                
                if (Filter::$id) {
                    $message = Message::formatSuccessMessage($data_m['title' . Language::$lang], Language::$word->PAG_UPDATE_OK);
                    Message::msgReply(true, 'success', $message);
                    Logger::writeLog($message);
                } else {
                    if ($last_id) {
                        $message = Message::formatSuccessMessage($data_m['title' . Language::$lang], Language::$word->PAG_ADDED_OK);
                        $json = array(
                            'type' => 'success',
                            'title' => Language::$word->SUCCESS,
                            'message' => $message,
                            'redirect' => ADMINURL . '/builder/' . Core::$language . '/' . $last_id
                        );
                        Logger::writeLog($message);
                    } else {
                        $json = array(
                            'type' => 'alert',
                            'title' => Language::$word->ALERT,
                            'message' => Language::$word->NOPROCESS,
                        );
                    }
                    print json_encode($json);
                }
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * copy
         *
         * @return void
         */
        public function copy(): void
        {
            $validate = Validator::run($_POST);
            
            foreach ($this->core->langlist as $lang) {
                $validate->set('title_' . $lang->abbr, Language::$word->NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>')->required()->string()->min_len(3)->max_len(60);
            }
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $datam = array();
                $data = array();
                foreach ($this->core->langlist as $lang) {
                    $datam['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
                    $datam['slug_' . $lang->abbr] = Url::doSeo($safe->{'title_' . $lang->abbr});
                }
                
                $rows = $this->db->select(Content::pTable)->where('id', Filter::$id, '=')->first()->run();
                
                foreach (new LimitIterator(new ArrayIterator($rows), 1) as $key => $row) {
                    $data[$key] = (isset($row)) ? $row : '';
                }
                
                $last_id = $this->db->insert(Content::pTable, $data)->run();
                $this->db->update(Content::pTable, $datam)->where('id', $last_id, '=')->run();
                
                if ($last_id) {
                    $message = Message::formatSuccessMessage($datam['title' . Language::$lang], Language::$word->PAG_ADDED_OK);
                    $json = array(
                        'type' => 'success',
                        'title' => 'success',
                        'message' => $message,
                        'assets_id' => Url::url('admin/pages/edit/', $last_id)
                    );
                    Logger::writeLog($message);
                } else {
                    $json = array(
                        'type' => 'alert',
                        'title' => Language::$word->ALERT,
                        'message' => Language::$word->NOPROCCESS,
                    );
                }
                print json_encode($json);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * _trash
         *
         * @return void
         */
        private function _trash(): void
        {
            $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
            if ($row = $this->db->select(Content::pTable)->where('id', Filter::$id, '=')->first()->run()) {
                $data = array(
                    'type' => 'page',
                    'parent_id' => Filter::$id,
                    'dataset' => json_encode($row)
                );
                $this->db->insert(Core::txTable, $data)->run();
                $this->db->delete(Content::pTable)->where('id', $row->id, '=')->run();
                if ($result = $this->db->select(Content::lTable)->where('page_id', $row->id, '=')->run()) {
                    $dataBatch = array();
                    foreach ($result as $item) {
                        $dataBatch[] = array(
                            'parent' => 'page',
                            'type' => 'layout',
                            'parent_id' => $row->id,
                            'dataset' => json_encode($item),
                        );
                    }
                    $this->db->batch(Core::txTable, $dataBatch)->run();
                    $this->db->delete(Content::lTable)->where('page_id', $row->id, '=')->run();
                }
            }
            $json = array(
                'type' => 'success',
                'title' => Language::$word->SUCCESS,
                'message' => str_replace('[NAME]', $title, Language::$word->PAG_TRASH_OK)
            );
            print json_encode($json);
            Logger::writeLog($json['message']);
        }
    }