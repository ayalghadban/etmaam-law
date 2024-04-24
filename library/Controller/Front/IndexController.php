<?php
    /**
     * IndexController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: IndexController.php, v1.00 6/10/2023 1:20 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Front;
    
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Membership;
    use Wojo\Core\Plugin;
    use Wojo\Core\User;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class IndexController extends Controller
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
            $this->view->title = str_replace('[COMPANY]', $this->core->company, Language::$word->META_T31);
            $this->view->keywords = null;
            $this->view->description = null;
            
            if (!$row = $this->db->select(Content::pTable)->where('page_type', 'home', '=')->where('active', 1, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid home page detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                    $this->view->render('error', 'view/front/themes/' . $this->core->theme . '/');
                } else {
                    $this->view->title = Language::$word->META_ERROR;
                    $this->view->render('404', 'view/front/themes/' . $this->core->theme . '/');
                }
            } else {
                $this->view->data = $row;
                Content::$pagedata = $row;
                $menu_tree = Content::menuTree(true);
                $menu = Utility::searchForValueName('home_page', 1, 'mod_slug', $menu_tree, true);
                
                $this->view->title = Url::formatMeta($this->view->data->{'title' . Language::$lang}, $this->core->company);
                $this->view->keywords = $row->{'keywords' . Language::$lang};
                $this->view->description = $row->{'description' . Language::$lang};
                
                //homepage module switching
                if ($menu['mod_id'] and in_array($menu['mslug'], $this->core->moddir)) {
                    $this->view->plugins = Plugin::getModulePlugins($menu['mslug']);
                    $this->view->layout = Plugin::moduleLayout($this->view->plugins);
                    
                    $methodName = 'FrontHome';
                    $class = ucfirst($menu['mslug']);
                    $func = "\Wojo\Module\" . $class::$methodName";
                    $this->view->module = $menu['mslug'];
                    
                    $results = $func();
                    
                    foreach ($results as $name => $value) {
                        $this->view->$name = $value;
                    }
                    
                    $this->view->render('mod_home', 'view/front/themes/' . $this->core->theme . '/');
                } else {
                    $this->view->render('index', 'view/front/themes/' . $this->core->theme . '/');
                }
            }
        }
        
        /**
         * page
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function page(): void
        {
            $this->view->title = str_replace('[COMPANY]', $this->core->company, Language::$word->META_T31);
            $this->view->keywords = null;
            $this->view->description = null;
            $this->view->crumbs = null;
            
            if (!$row = $this->db->select(Content::pTable)->where('slug' . Language::$lang, $this->view->matches, '=')->where('active', 1, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid page slug "' . $this->view->matches . '" detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                    $this->view->render('error', 'view/front/themes/' . $this->core->theme . '/');
                } else {
                    $this->view->title = Language::$word->META_ERROR;
                    $this->view->render('404', 'view/front/themes/' . $this->core->theme . '/');
                }
            } else {
                $this->view->data = $row;
                Content::$pagedata = $row;
                
                $this->view->title = Url::formatMeta($row->{'title' . Language::$lang}, $this->core->company);
                $this->view->keywords = $row->{'keywords' . Language::$lang};
                $this->view->description = $row->{'description' . Language::$lang};
                $this->view->crumbs = [array(0 => Language::$word->HOME, 1 => ''), $row->{'title' . Language::$lang}];
                
                $this->view->meta = '<meta property="og:type" content="article" />' . "\n";
                $this->view->meta .= '<meta property="og:title" content="' . $row->{'title' . Language::$lang} . '" />' . "\n";
                if ($row->main_image) {
                    $this->view->meta .= '<meta property="og:image" content="' . SITEURL . $row->main_image . '" />' . "\n";
                }
                $this->view->meta .= '<meta property="og:description" content="' . $this->view->title . '" />' . "\n";
                $this->view->meta .= '<meta property="og:url" content="' . Url::url($this->core->pageslug, $this->view->matches) . '" />' . "\n";
                
                $this->view->render('page', 'view/front/themes/' . $this->core->theme . '/');
            }
        }
        
        /**
         * register
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function register(): void
        {
            $this->view->title = Url::formatMeta(Language::$word->REGISTER, $this->core->company);
            $this->view->keywords = null;
            $this->view->description = null;
            $this->view->meta = null;
            $this->view->crumbs = null;
            
            $this->view->custom_fields = Content::renderCustomFieldsFront(0, 'profile');
            $this->view->countries = $this->core->enable_tax ? $this->db->select(Content::cTable)->orderBy('sorting', 'DESC')->run() : null;
            
            $this->view->render('register', THEMEBASE);
        }
        
        /**
         * activation
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function activation(): void
        {
            $this->view->title = str_replace('[COMPANY]', $this->core->company, Language::$word->META_T31);
            $this->view->keywords = null;
            $this->view->description = null;
            $this->view->meta = null;
            $this->view->crumbs = null;
            
            if (!$row = $this->db->select(Content::pTable)->where('page_type', 'activate', '=')->where('active', 1, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid privacy policy page detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                    $this->view->render('error', 'view/front/themes/' . $this->core->theme . '/');
                } else {
                    $this->view->title = Language::$word->META_ERROR;
                    $this->view->render('404', 'view/front/themes/' . $this->core->theme . '/');
                }
            } else {
                $this->view->data = $row;
                Content::$pagedata = $row;
                
                $this->view->title = Url::formatMeta($row->{'title' . Language::$lang}, $this->core->company);
                $this->view->keywords = $row->{'keywords' . Language::$lang};
                $this->view->description = $row->{'description' . Language::$lang};
                $this->view->crumbs = [array(0 => Language::$word->HOME, 1 => ''), $row->{'title' . Language::$lang}];
                
                $this->view->render('activation', 'view/front/themes/' . $this->core->theme . '/');
                
                if (Validator::get('token') and Validator::get('email')) {
                    $validate = Validator::run($_GET);
                    $validate
                        ->set('email', Language::$word->M_EMAIL)->required()->email()
                        ->set('token', Language::$word->M_INFO10)->required()->string();
                    
                    $safe = $validate->safe();
                    if (count(Message::$msgs) === 0) {
                        if ($row = $this->db->select(User::mTable, array('id'))->where('email', $safe->email, '=')->where('token', $safe->token, '=')->first()->run()) {
                            $this->db->update(User::mTable, array('active' => 'y', 'token' => 0))->where('id', $row->id, '=')->run();
                            Url::redirect(Url::url($this->core->system_slugs->activate[0]->{'slug' . Language::$lang}, '?done=true'));
                        }
                    }
                }
            }
        }
        
        /**
         * password
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function password(): void
        {
            $this->view->title = str_replace('[COMPANY]', $this->core->company, Language::$word->META_T31);
            $this->view->keywords = null;
            $this->view->description = null;
            $this->view->meta = null;
            $this->view->crumbs = null;
            
            if (!$row = $this->db->select(User::mTable)->where('token', $this->view->matches, '=')->where('active', 'y', '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid user token ' . $this->view->matches . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                    $this->view->render('error', 'view/front/themes/' . $this->core->theme . '/');
                } else {
                    $this->view->title = Language::$word->META_ERROR;
                    $this->view->render('404', 'view/front/themes/' . $this->core->theme . '/');
                }
            } else {
                $this->view->data = $row;
                Content::$pagedata = $row;
                
                $this->view->title = Url::formatMeta(Language::$word->M_PASSWORD_RES, $this->core->company);
                $this->view->crumbs = [array(0 => Language::$word->HOME, 1 => ''), Language::$word->M_PASSWORD_RES];
                
                $this->view->render('password', 'view/front/themes/' . $this->core->theme . '/');
            }
        }
        
        /**
         * profile
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function profile(): void
        {
            $lg = Language::$lang;
            
            $this->view->title = str_replace('[COMPANY]', $this->core->company, Language::$word->META_T31);
            $this->view->keywords = null;
            $this->view->description = null;
            $this->view->meta = null;
            $this->view->crumbs = null;
            
            $sql = "
            SELECT *,u.id as id, m.title$lg as mtitle
              FROM   `" . User::mTable . '` as u
              LEFT JOIN ' . Membership::mTable . ' as m on m.id = u.membership_id
              WHERE u.username = ?
              AND u.active = ?
            ';
            if (!$row = $this->db->rawQuery($sql, array($this->view->matches, 'y'))->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid user token ' . $this->view->matches . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                    $this->view->render('error', 'view/front/themes/' . $this->core->theme . '/');
                } else {
                    $this->view->title = Language::$word->META_ERROR;
                    $this->view->render('404', 'view/front/themes/' . $this->core->theme . '/');
                }
            } else {
                $this->view->data = $row;
                $this->view->custom_fields = Content::renderCustomFieldsFront($row->id, 'profile', 'user');
                
                $this->view->title = Url::formatMeta(Language::$word->META_T32, $this->core->company);
                $this->view->keywords = $this->view->matches;
                $this->view->description = $row->info;
                $this->view->crumbs = [array(0 => Language::$word->HOME, 1 => ''), Language::$word->META_T32];
                
                $this->view->render('profile', 'view/front/themes/' . $this->core->theme . '/');
            }
        }
        
        /**
         * privacy
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function privacy(): void
        {
            $this->view->title = str_replace('[COMPANY]', $this->core->company, Language::$word->META_T31);
            $this->view->keywords = null;
            $this->view->description = null;
            $this->view->meta = null;
            $this->view->crumbs = null;
            
            if (!$row = $this->db->select(Content::pTable)->where('page_type', 'policy', '=')->where('active', 1, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid privacy policy page detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                    $this->view->render('error', 'view/front/themes/' . $this->core->theme . '/');
                } else {
                    $this->view->title = Language::$word->META_ERROR;
                    $this->view->render('404', 'view/front/themes/' . $this->core->theme . '/');
                }
            } else {
                $this->view->data = $row;
                Content::$pagedata = $row;
                
                $this->view->title = Url::formatMeta($row->{'title' . Language::$lang}, $this->core->company);
                $this->view->keywords = $row->{'keywords' . Language::$lang};
                $this->view->description = $row->{'description' . Language::$lang};
                $this->view->crumbs = [array(0 => Language::$word->HOME, 1 => ''), $row->{'title' . Language::$lang}];
                
                $this->view->render('page', 'view/front/themes/' . $this->core->theme . '/');
            }
        }
        
        /**
         * search
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function search(): void
        {
            $this->view->title = str_replace('[COMPANY]', $this->core->company, Language::$word->META_T31);
            $this->view->keywords = null;
            $this->view->description = null;
            $this->view->meta = null;
            $this->view->crumbs = null;
            
            if (!$row = $this->db->select(Content::pTable)->where('page_type', 'search', '=')->where('active', 1, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid privacy policy page detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                    $this->view->render('error', 'view/front/themes/' . $this->core->theme . '/');
                } else {
                    $this->view->title = Language::$word->META_ERROR;
                    $this->view->render('404', 'view/front/themes/' . $this->core->theme . '/');
                }
            } else {
                $this->view->data = $row;
                Content::$pagedata = $row;
                
                $this->view->keyword = Validator::get('keyword');
                $this->view->pagedata = Content::searchResults(Validator::sanitize($this->view->keyword, 'string', 20));
                $this->view->blogdata = array();
                $this->view->portadata = array();
                $this->view->digidata = array();
                $this->view->shopdata = array();
                
                $this->view->title = Url::formatMeta($row->{'title' . Language::$lang}, $this->core->company);
                $this->view->keywords = $row->{'keywords' . Language::$lang};
                $this->view->description = $row->{'description' . Language::$lang};
                $this->view->crumbs = [array(0 => Language::$word->HOME, 1 => ''), $row->{'title' . Language::$lang}];
                
                $this->view->render('search', 'view/front/themes/' . $this->core->theme . '/');
            }
        }
        
        /**
         * sitemap
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function sitemap(): void
        {
            $this->view->title = str_replace('[COMPANY]', $this->core->company, Language::$word->META_T31);
            $this->view->keywords = null;
            $this->view->description = null;
            $this->view->meta = null;
            $this->view->crumbs = null;
            
            if (!$row = $this->db->select(Content::pTable)->where('page_type', 'sitemap', '=')->where('active', 1, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid privacy policy page detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                    $this->view->render('error', 'view/front/themes/' . $this->core->theme . '/');
                } else {
                    $this->view->title = Language::$word->META_ERROR;
                    $this->view->render('404', 'view/front/themes/' . $this->core->theme . '/');
                }
            } else {
                $this->view->data = $row;
                Content::$pagedata = $row;
                $lg = Language::$lang;
                
                $this->view->pagedata = $this->db->select(Content::pTable, array("title$lg AS title", "slug$lg AS slug"))->where('page_type', 'normal', '=')->where('active', 1, '=')->run();
                $this->view->blogdata = array();
                $this->view->portadata = array();
                $this->view->digidata = array();
                $this->view->shopdata = array();
                
                $this->view->title = Url::formatMeta($row->{'title' . Language::$lang}, $this->core->company);
                $this->view->keywords = $row->{'keywords' . Language::$lang};
                $this->view->description = $row->{'description' . Language::$lang};
                $this->view->crumbs = [array(0 => Language::$word->HOME, 1 => ''), $row->{'title' . Language::$lang}];
                
                $this->view->render('sitemap', 'view/front/themes/' . $this->core->theme . '/');
            }
        }
    }