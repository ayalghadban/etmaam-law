<?php
    /**
     * CommentController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: CommentController.php, v1.00 5/20/2023 8:09 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin\Module\Comment;
    
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Core\Router;
    use Wojo\Core\User;
    use Wojo\Database\Paginator;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Module\Comment\Comment;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class CommentController extends Controller
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
            $counter = $this->db->count(Comment::mTable)->where('active', 1, '<')->run();
            $pager = Paginator::instance();
            $pager->items_total = $counter;
            $pager->default_ipp = $this->core->perpage;
            $pager->path = Url::url(Router::$path, '?');
            $pager->paginate();
            
            $sql = "
            SELECT c.id, c.user_id, c.comment_id, c.parent_id, c.section, c.body, c.created, c.username as uname, u.username, CONCAT(u.fname, ' ', u.lname) as name
              FROM `" . Comment::mTable . '` as c
              LEFT JOIN `' . User::mTable . '` as u ON u.id = c.user_id
              WHERE c.active = ?
              ORDER BY c.created DESC ' . $pager->limit;
            
            $rows = $this->db->rawQuery($sql, array(0))->run();
            
            $this->view->data = $rows;
            $this->view->pager = $pager;
            
            $this->view->crumbs = ['admin', 'modules', Language::$word->_MOD_CM_TITLE2];
            $this->view->caption = Language::$word->_MOD_CM_TITLE2;
            $this->view->title = Language::$word->_MOD_CM_TITLE2;
            $this->view->subtitle = [];
            $this->view->render('index', 'view/admin/modules/comments/view/', true, 'view/admin/');
        }
        
        /**
         * settings
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function settings(): void
        {
            $row = json_decode(File::loadFile(AMODPATH . 'comments/config.json'));
            $this->view->data = $row->comments;
            
            $this->view->crumbs = ['admin', 'modules', 'comments', Language::$word->_MOD_CM_TITLE1];
            $this->view->caption = Language::$word->_MOD_CM_TITLE1;
            $this->view->title = Language::$word->_MOD_CM_TITLE1;
            $this->view->subtitle = [];
            $this->view->render('index', 'view/admin/modules/comments/view/', true, 'view/admin/');
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
                        case 'configuration':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->process();
                            break;
                        
                        case 'approve':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->approve();
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
                ->set('auto_approve', Language::$word->_MOD_CM_AA)->required()->numeric()
                ->set('rating', Language::$word->_MOD_CM_RATING)->required()->numeric()
                ->set('char_limit', Language::$word->_MOD_CM_CHAR)->required()->numeric()
                ->set('notify_new', Language::$word->_MOD_CM_NOTIFY)->required()->numeric()
                ->set('perpage', Language::$word->_MOD_CM_PERPAGE)->required()->numeric()
                ->set('public_access', Language::$word->_MOD_CM_REG_ONLY)->required()->numeric()
                ->set('show_captcha', Language::$word->_MOD_CM_CAPTCHA)->required()->numeric()
                ->set('sorting', Language::$word->_MOD_CM_SORTING)->required()->string()
                ->set('dateformat', Language::$word->_MOD_CM_DATE)->required()->string()
                ->set('username_req', Language::$word->_MOD_CM_UNAME_R)->required()->numeric()
                ->set('blacklist_words', Language::$word->_MOD_AM_SUB39)->string();
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data = array(
                    'comments' => array(
                        'auto_approve' => $safe->auto_approve,
                        'rating' => $safe->rating,
                        'char_limit' => $safe->char_limit,
                        'notify_new' => $safe->notify_new,
                        'perpage' => $safe->perpage,
                        'public_access' => $safe->public_access,
                        'show_captcha' => $safe->show_captcha,
                        'sorting' => $safe->sorting,
                        'dateformat' => $safe->dateformat,
                        'timesince' => (strlen($_POST['timesince']) === 0 ? 0 : 1),
                        'username_req' => $safe->username_req,
                        'blacklist_words' => $safe->blacklist_words,
                    )
                );
                
                Message::msgReply(File::writeToFile(AMODPATH . 'comments/config.json', json_encode($data, JSON_PRETTY_PRINT)), 'success', Language::$word->_MOD_CM_CUPDATED);
                Logger::writeLog(Language::$word->_MOD_CM_CUPDATED);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * approve
         *
         * @return void
         */
        private function approve(): void
        {
            if ($this->db->update(Comment::mTable, array('active' => 1))->where('id', Filter::$id)->run()) {
                $json['type'] = 'success';
                print json_encode($json);
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
            
            $this->db->delete(Comment::mTable)->where('id', Filter::$id)->run();
            
            $message = str_replace('[NAME]', $title, Language::$word->_MOD_CM_DEL_OK);
            Message::msgReply(true, 'success', $message);
            Logger::writeLog($message);
        }
    }