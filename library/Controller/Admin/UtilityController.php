<?php
    /**
     * UtilityController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: UtilityController.php, v1.00 4/30/2023 11:54 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use Wojo\Container\Container;
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Membership;
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
    
    class UtilityController extends Controller
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
            $this->view->crumbs = ['admin', Language::$word->ADM_UTIL];
            $this->view->title = Language::$word->META_T19;
            $this->view->caption = Language::$word->META_T19;
            $this->view->subtitle = Language::$word->UTL_INFO;
            
            $sql = '
                SELECT COUNT(*) AS total,
                  COUNT(CASE WHEN active = ? AND type = ? THEN 1 END) AS pending,
                  COUNT(CASE WHEN active = ? AND type = ? THEN 1 END) AS banned
                  FROM `' . User::mTable . '`
                ';
            $data = $this->db->rawQuery($sql, array('t', 'member', 'b', 'member'))->first()->run();
            
            $this->view->banned = $data->banned;
            $this->view->pending = $data->pending;
            $this->view->colors = Utility::parseColors();
            
            $this->view->render('utility', 'view/admin/');
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
                    Container::$namespace = "\\Wojo\\Core\\";
                    switch ($postAction) {
                        // delete inactive users
                        case 'deleteInactive':
                            //Container::$namespace = "\\Wojo\\Core\\";
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : Container::User()->deleteInactiveUsers();
                            break;
                        
                        // delete all banned users
                        case 'deleteBanned':
                            Container::$namespace = "\\Wojo\\Core\\";
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : Container::User()->deleteBannedUsers();
                            break;
                        
                        // delete all pending users
                        case 'deletePending':
                            Container::$namespace = "\\Wojo\\Core\\";
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : Container::User()->deletePendingUsers();
                            break;
                        
                        // create site map
                        case 'siteMap':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : Content::writeSiteMap();
                            break;
                        
                        // Digishop, plugin theme install
                        case 'install':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->core->install();
                            break;
                        
                        // update system slugs
                        case 'slugs':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->core->updateSlugs();
                            break;
                        
                        // update color scheme
                        case 'colors':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->core->updateColors();
                            break;
                        
                        // empty cart data, used to store temporary user details
                        case 'emptyCart':
                            if (IS_DEMO) {
                                Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO);
                                exit;
                            }
                            $this->db->rawQuery('DELETE FROM `' . Membership::cTable . '` WHERE DATE(created) < DATE_SUB(CURDATE(), INTERVAL 1 DAY)')->run();
                            if ($this->db->exist('mod_shop')->run()) {
                                $this->db->rawQuery('DELETE FROM `mod_shop_cart` WHERE DATE(created) < DATE_SUB(CURDATE(), INTERVAL 1 DAY)')->run();
                                $this->db->rawQuery('DELETE FROM `mod_shop_cart_shipping` WHERE DATE(created) < DATE_SUB(CURDATE(), INTERVAL 1 DAY)')->run();
                            }
                            if ($this->db->exist('mod_digishop')->run()) {
                                $this->db->rawQuery('DELETE FROM `mod_digishop_cart` WHERE DATE(created) < DATE_SUB(CURDATE(), INTERVAL 1 DAY)')->run();
                            }
                            
                            $total = $this->db->affected();
                            
                            Message::msgReply($total, 'success', Message::formatSuccessMessage($total, Language::$word->UTL_DELCRT_OK));
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
    }