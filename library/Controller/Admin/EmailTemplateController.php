<?php
    /**
     * EmailTemplateController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: EmailTemplateController.php, v1.00 5/9/2023 7:07 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class EmailTemplateController extends Controller
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
            $lg = Language::$lang;
            $this->view->data = $this->db->select(Content::eTable)->orderBy("name$lg", 'ASC')->run();
            
            $this->view->crumbs = ['admin', Language::$word->META_T10];
            $this->view->caption = Language::$word->ET_TITLE;
            $this->view->title = Language::$word->META_T10;
            $this->view->subtitle = Language::$word->ET_SUB;
            $this->view->render('template', 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'templates', 'edit'];
            $this->view->title = Language::$word->META_T11;
            $this->view->caption = Language::$word->META_T11;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Content::eTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->langlist = $this->core->langlist;
                $this->view->render('template', 'view/admin/');
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
                    ->set('name_' . $lang->abbr, Language::$word->NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>')->required()->string()->min_len(3)->max_len(60)
                    ->set('subject_' . $lang->abbr, Language::$word->ET_SUBJECT . ' <span class="flag icon ' . $lang->abbr . '"></span>')->required()->string()->min_len(3)->max_len(100)
                    ->set('body_' . $lang->abbr, Language::$word->BGIMG)->text('advanced')
                    ->set('help_' . $lang->abbr, Language::$word->METAKEYS)->string();
            }
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data = array();
                foreach ($this->core->langlist as $lang) {
                    $data['name_' . $lang->abbr] = $safe->{'name_' . $lang->abbr};
                    $data['subject_' . $lang->abbr] = $safe->{'subject_' . $lang->abbr};
                    $data['help_' . $lang->abbr] = $safe->{'help_' . $lang->abbr};
                    $data['body_' . $lang->abbr] = str_replace(array(SITEURL, $this->core->plogo), array('[SITEURL]', '[LOGO]'), $safe->{'body_' . $lang->abbr});
                }
                
                $this->db->update(Content::eTable, $data)->where('id', Filter::$id, '=')->run();
                $message = Message::formatSuccessMessage($data['name' . Language::$lang], Language::$word->ET_UPDATED);
                Message::msgReply($this->db->affected(), 'success', $message);
                Logger::writeLog($message);
            } else {
                Message::msgSingleStatus();
            }
        }
    }