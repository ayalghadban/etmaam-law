<?php
    /**
     * PollController CLass
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: PollController.php, v1.00 5/14/2023 8:31 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin\Plugin\Poll;
    
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Core\Plugin;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Plugin\Poll\Poll;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class PollController extends Controller
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
            $this->view->data = Poll::getAllPolls();
            
            $this->view->crumbs = ['admin', 'plugins', Language::$word->_PLG_PL_TITLE3];
            $this->view->caption = Language::$word->_PLG_PL_TITLE3;
            $this->view->title = Language::$word->_PLG_PL_TITLE3;
            $this->view->subtitle = Language::$word->_PLG_PL_SUB3;
            $this->view->render('index', 'view/admin/plugins/poll/view/', true, 'view/admin/');
        }
        
        /**
         * new
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function new(): void
        {
            $this->view->crumbs = ['admin', 'plugins', 'poll', 'new'];
            $this->view->caption = Language::$word->_PLG_PL_TITLE2;
            $this->view->title = Language::$word->_PLG_PL_TITLE2;
            $this->view->subtitle = Language::$word->_PLG_PL_SUB2;
            $this->view->render('index', 'view/admin/plugins/poll/view/', true, 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'plugins', 'poll', 'edit'];
            $this->view->title = Language::$word->_PLG_PL_TITLE1;
            $this->view->caption = Language::$word->_PLG_PL_TITLE1;
            $this->view->subtitle = Language::$word->_PLG_PL_SUB1;
            
            if (!$row = $this->db->select(Poll::qTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->options = Poll::getPollOptions($row->id);
                $this->view->render('index', 'view/admin/plugins/poll/view/', true, 'view/admin/');
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
                        
                        case 'rename':
                            IS_DEMO ? print '0' : $this->rename();
                            break;
                        
                        case 'delete':
                            IS_DEMO ? Message::msgReply(true, 'success', Language::$word->PROCESS_ERR_DEMO) : $this->_delete(Validator::post('type'));
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
        private function process(): void
        {
            $validate = Validator::run($_POST);
            $validate->set('question', Language::$word->_PLG_PL_QUESTION)->required()->string()->min_len(3)->max_len(80);
            (Filter::$id) ? $this->update($validate) : $this->add($validate);
        }
        
        /**
         * add
         *
         * @param Validator $validate
         * @return void
         */
        public function add(Validator $validate): void
        {
            $validate->set('value', Language::$word->_PLG_PL_OPTIONS)->one();
            
            $safe = $validate->safe();
            
            if (array_key_exists('value', $_POST)) {
                if (!array_filter($_POST['value'])) {
                    Message::$msgs['value'] = Language::$word->_PLG_PL_OPTIONS;
                }
            }
            
            if (count(Message::$msgs) === 0) {
                $data_m = array();
                $data = array('question' => $safe->question);
                $last_id = $this->db->insert(Poll::qTable, $data)->run();
                
                // Insert new multi plugin
                $plugin_id = 'poll/' . Utility::randomString();
                File::makeDirectory(FPLUGPATH . $plugin_id);
                File::copyFile(FPLUGPATH . 'poll/master.php', FPLUGPATH . $plugin_id . '/index.tpl.php');
                
                $pid = $this->db->select(Plugin::mTable, array('id'))->where('plugalias', 'poll', '=')->first()->run();
                foreach ($this->core->langlist as $lang) {
                    $data_m['title_' . $lang->abbr] = $safe->question;
                }
                $data_x = array(
                    'parent_id' => $pid->id,
                    'plugin_id' => $last_id,
                    'icon' => 'poll/thumb.svg',
                    'plugalias' => $plugin_id,
                    'groups' => 'poll',
                    'active' => 1,
                );
                $this->db->insert(Plugin::mTable, array_merge($data_m, $data_x))->run();
                
                if (array_key_exists('value', $_POST)) {
                    $dataArray = array();
                    foreach ($_POST['value'] as $key => $val) {
                        $key++;
                        $dataArray[] = array(
                            'question_id' => $last_id,
                            'value' => Validator::sanitize($val),
                            'position' => $key,
                        );
                    }
                    $this->db->batch(Poll::oTable, $dataArray)->run();
                }
                
                $message = Message::formatSuccessMessage($data['question'], Language::$word->_PLG_PL_ADDED);
                $json = array(
                    'type' => 'success',
                    'title' => Language::$word->SUCCESS,
                    'message' => $message,
                    'redirect' => Url::url('/admin/plugins', 'poll')
                );
                Logger::writeLog($message);
                
                print json_encode($json);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * update
         *
         * @param Validator $validate
         * @return void
         */
        private function update(Validator $validate): void
        {
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data = array('question' => $safe->question);
                
                $this->db->update(Poll::qTable, $data)->where('id', Filter::$id, '=')->run();
                
                if (array_key_exists('value', $_POST)) {
                    $dataArray = array();
                    $values = array_filter($_POST['value']);
                    $counter = count($_POST['newvalue']);
                    foreach ($values as $val) {
                        $dataArray[] = array(
                            'question_id' => Filter::$id,
                            'value' => Validator::sanitize($val),
                            'position' => $counter++,
                        );
                    }
                    $this->db->batch(Poll::oTable, $dataArray)->run();
                }
                
                $message = Message::formatSuccessMessage($data['question'], Language::$word->_PLG_PL_UPDATED);
                
                $json = array(
                    'type' => 'success',
                    'title' => Language::$word->SUCCESS,
                    'message' => $message,
                    'redirect' => Url::url('/admin/plugins', 'poll')
                );
                Logger::writeLog($message);
                
                print json_encode($json);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * rename
         *
         * @return void
         */
        private function rename(): void
        {
            if ($this->db->update(Poll::oTable, array('value' => Validator::sanitize($_POST['value'])))->where('id', Filter::$id, '=')->run()) {
                print 1;
            }
        }
        
        /**
         * _delete
         *
         * @param string $type
         * @return void
         */
        private function _delete(string $type): void
        {
            $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
            
            if ($type == 'option') {
                if ($this->db->delete(Poll::oTable)->where('id', Filter::$id, '=')->run()) {
                    $this->db->delete(Poll::vTable)->where('option_id', Filter::$id, '=')->run();
                    print 1;
                }
            } else {
                $res = $this->db->delete(Poll::qTable)->where('id', Filter::$id, '=')->run();
                $this->db->delete(Poll::oTable)->where('question_id', Filter::$id, '=')->run();
                $this->db->rawQuery('DELETE FROM `' . Poll::vTable . '` WHERE option_id IN(SELECT id FROM `' . Poll::oTable . '` WHERE question_id=' . Filter::$id . ')');
                if ($row = $this->db->select(Plugin::mTable, array('id', 'plugalias'))->where('plugin_id', Filter::$id, '=')->where('groups', 'poll', '=')->first()->run()) {
                    $this->db->delete(Content::lTable)->where('plug_id', $row->id, '=')->run();
                    $this->db->delete(Plugin::mTable)->where('id', $row->id, '=')->run();
                    
                    File::deleteDirectory(FPLUGPATH . $row->plugalias);
                }
                
                $message = str_replace('[NAME]', $title, Language::$word->_PLG_PL_DEL_OK);
                Message::msgReply($res, 'success', $message);
                Logger::writeLog($message);
            }
        }
    }