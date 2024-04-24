<?php
    /**
     * ModuleController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: ModuleController.php, v1.00 5/12/2023 7:53 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Core\Module;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class ModuleController extends Controller
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
            $this->view->data = $this->db->select(Module::mTable)->where('parent_id', 1, '<')->orderBy('title' . Language::$lang, 'ASC')->run();
            
            $this->view->crumbs = ['admin', Language::$word->MDL_TITLE];
            $this->view->caption = Language::$word->MDL_TITLE;
            $this->view->title = Language::$word->MDL_TITLE;
            $this->view->subtitle = Language::$word->MDL_SUB;
            $this->view->render('module', 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'modules', 'edit'];
            $this->view->title = Language::$word->META_T30;
            $this->view->caption = Language::$word->META_T30;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Module::mTable)->where('id', $this->view->matches, '=')->where('parent_id', 1, '<')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->langlist = $this->core->langlist;
                
                $this->view->render('module', 'view/admin/');
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
                        case 'update':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->process();
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
                    ->set('title_' . $lang->abbr, Language::$word->NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>')->required()->string()->min_len(3)->max_len(80)
                    ->set('info_' . $lang->abbr, Language::$word->DESCRIPTION)->string()
                    ->set('keywords_' . $lang->abbr, Language::$word->METAKEYS)->string(true, true)
                    ->set('description_' . $lang->abbr, Language::$word->METADESC)->string(true, true);
            }
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data = array();
                foreach ($this->core->langlist as $lang) {
                    $data['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
                    $data['info_' . $lang->abbr] = $safe->{'info_' . $lang->abbr};
                    $data['keywords_' . $lang->abbr] = $safe->{'keywords_' . $lang->abbr};
                    $data['description_' . $lang->abbr] = $safe->{'description_' . $lang->abbr};
                }
                
                $this->db->update(Module::mTable, $data)->where('id', Filter::$id, '=')->run();
                
                $message = Message::formatSuccessMessage($data['title' . Language::$lang], Language::$word->MDL_UPDATE_OK);
                
                Message::msgReply($this->db->affected(), 'success', $message);
                Logger::writeLog($message);
            } else {
                Message::msgSingleStatus();
            }
        }
    }