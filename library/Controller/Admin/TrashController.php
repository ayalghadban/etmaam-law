<?php
    /**
     * TrashController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: TrashController.php, v1.00 5/31/2023 9:30 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Core;
    use Wojo\Core\Filter;
    use Wojo\Core\Membership;
    use Wojo\Core\Plugin;
    use Wojo\Core\User;
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
    
    class TrashController extends Controller
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
            $data = $this->db->select(Core::txTable)->run();
            $this->view->data = Utility::groupToLoop($data, 'type');
            
            $this->view->crumbs = ['admin', Language::$word->TRS_TITLE];
            $this->view->caption = Language::$word->TRS_TITLE;
            $this->view->title = Language::$word->TRS_TITLE;
            $this->view->subtitle = Language::$word->TRS_INFO;
            $this->view->render('trash', 'view/admin/');
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
                        case 'restore':
                            IS_DEMO ? Message::msgReply(true, 'success', Language::$word->PROCESS_ERR_DEMO) : $this->_restore(Validator::post('type'));
                            break;
                        
                        case 'delete':
                            IS_DEMO ? Message::msgReply(true, 'success', Language::$word->PROCESS_ERR_DEMO) : $this->_delete(Validator::post('type'));
                            break;
                        
                        case 'empty':
                            IS_DEMO ? Message::msgReply(true, 'success', Language::$word->PROCESS_ERR_DEMO) : $this->_empty();
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
         * restoreFromTrash
         *
         * @param array $array
         * @param string $table
         * @return bool
         */
        protected function restoreFromTrash(array $array, string $table): bool
        {
            if ($array) {
                $mapped = array_map(function ($k) {
                    return '`' . $k . '` = ?';
                }, array_keys($array));
                $stmt = $this->db->prepare('INSERT INTO `' . $table . '` SET ' . implode(', ', $mapped));
                $stmt->execute(array_values($array));
                
                return true;
            }
            return false;
        }
        
        /**
         * _restore
         *
         * @param string $type
         * @return void
         * @throws NotFoundException
         */
        private function _restore(string $type): void
        {
            $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
            
            $table = match ($type) {
                'menu' => Content::mTable,
                'coupon' => Content::dcTable,
                'membership' => Membership::mTable,
                'page' => Content::pTable,
                'plugin' => Plugin::mTable,
                'user' => User::mTable,
                default => throw new NotFoundException(sprintf('The requested action "%s" don\'t match any type.', $type)),
            };
            
            if ($table !== null) {
                if ($result = $this->db->select(Core::txTable, array('dataset'))->where('id', Filter::$id, '=')->first()->run()) {
                    $array = Utility::jSonToArray($result->dataset, true);
                    $this->restoreFromTrash($array, $table);
                    $this->db->delete(Core::txTable)->where('id', Filter::$id, '=')->run();
                    
                    Message::msgReply(true, 'success', $title . ' - ' . Language::$word->RESFRTR);
                }
            }
        }
        
        /**
         * _delete
         *
         * @param string $type
         * @return void
         * @throws NotFoundException
         */
        private function _delete(string $type): void
        {
            $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
            
            switch ($type) {
                case 'menu':
                case 'coupon':
                case 'membership':
                case 'page':
                case 'plugin':
                case 'user':
                    $this->db->delete(Core::txTable)->where('id', Filter::$id, '=')->run();
                    $json['type'] = 'success';
                    $json['title'] = Language::$word->SUCCESS;
                    $json['message'] = $title . ' - ' . Language::$word->DELETED;
                    print json_encode($json);
                    break;
                default:
                    throw new NotFoundException(sprintf('The requested action "%s" don\'t match any type.', $type));
                    break;
            }
        }
        
        /**
         * _empty
         *
         * @return void
         */
        private function _empty(): void
        {
            $this->db->truncate(Core::txTable)->run();
            Message::msgReply(true, 'success', Language::$word->TRS_TRS_OK);
        }
    }