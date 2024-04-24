<?php
    /**
     * EventController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: EventController.php, v1.00 5/19/2023 9:33 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin\Plugin\Event;
    
    use Wojo\Core\Controller;
    use Wojo\Core\Logger;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Module\Event\Event;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class EventController extends Controller
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
            $id = json_decode(File::loadFile(APLUGPATH . 'event/config.json'));
            $this->view->row = $id->event->event_id;
            $this->view->data = $this->db->select(Event::mTable, array('id', 'title' . Language::$lang))->where('active', 1, '=')->run();
            
            $this->view->crumbs = ['admin', 'plugins', Language::$word->_PLG_UE_TITLE1];
            $this->view->caption = Language::$word->_PLG_UE_TITLE1;
            $this->view->title = Language::$word->_PLG_UE_TITLE1;
            $this->view->subtitle = null;
            $this->view->render('index', 'view/admin/plugins/event/view/', true, 'view/admin/');
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
        private function process(): void
        {
            
            if (!array_key_exists('event_id', $_POST)) {
                Message::$msgs['event_id'] = Language::$word->_PLG_UE_ERR;
            }
            
            if (count(Message::$msgs) === 0) {
                $data = array('event' => array('event_id' => Utility::implodeFields($_POST['event_id'])));
                
                Message::msgReply(File::writeToFile(APLUGPATH . 'event/config.json', json_encode($data, JSON_PRETTY_PRINT)), 'success', Language::$word->_PLG_UE_UPDATED);
                Logger::writeLog(Language::$word->_PLG_UE_UPDATED);
            } else {
                Message::msgSingleStatus();
            }
        }
    }