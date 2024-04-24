<?php
    /**
     * BlogController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: BlogController.php, v1.00 11/29/2023 6:30 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Front\Module\Blog;
    
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Core\Membership;
    use Wojo\Core\Module;
    use Wojo\Core\Plugin;
    use Wojo\Core\Router;
    use Wojo\Core\Session;
    use Wojo\Core\User;
    use Wojo\Database\Paginator;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Exception\NotFoundException;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Module\Blog\Blog;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class BlogController extends Controller
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
            
            $this->view->title = str_replace('[COMPANY]', $this->core->company, Language::$word->META_T31);
            $this->view->keywords = null;
            $this->view->description = null;
            $this->view->meta = null;
            $this->view->crumbs = [array(0 => Language::$word->HOME, 1 => ''), Language::$word->_MOD_AM_TITLE];
            
            $mSql = array("title$lg as title", "info$lg", "keywords$lg", "description$lg");
            if (!$this->view->data = $this->db->select(Module::mTable, $mSql)->where('modalias', 'blog', '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid blog module detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                    $this->view->render('error', 'view/front/themes/' . $this->core->theme . '/');
                } else {
                    $this->view->title = Language::$word->META_ERROR;
                    $this->view->render('404', 'view/front/themes/' . $this->core->theme . '/');
                }
            } else {
                $config = $this->_config();
                
                $pager = Paginator::instance();
                $pager->items_total = $this->db->count(Blog::mTable, 'WHERE active = 1 LIMIT 1')->run();
                $pager->default_ipp = $config->blog->fperpage;
                $pager->path = Url::url(Router::$path, '?');
                $pager->paginate();
                
                $sql = "
                SELECT a.id, a.created, a.title$lg as title, a.slug$lg as slug, a.body$lg as body, a.thumb, c.slug$lg as cslug, c.name$lg as ctitle,
                       (SELECT COUNT(parent_id) FROM `" . Module::mcTable . '` WHERE `' . Module::mcTable . "`.parent_id = a.id AND section = 'blog') as comments
                  FROM `" . Blog::mTable . '` as a
                  LEFT JOIN `' . Blog::cTable . '` as c ON c.id = a.category_id
                  LEFT JOIN `' . User::mTable . '` as u ON u.id = a.user_id
                  WHERE a.active = ?
                  ORDER BY a.created
                  DESC ' . $pager->limit;
                
                $this->view->title = Url::formatMeta($this->view->data->title, $this->core->company);
                $this->view->keywords = $this->view->data->{'keywords' . $lg};
                $this->view->description = $this->view->data->{'description' . $lg};
                $this->view->crumbs = [array(0 => Language::$word->HOME, 1 => ''), $this->view->data->title];
                
                $this->view->rows = $this->db->rawQuery($sql, array(1))->run();
                $this->view->plugins = Plugin::getModulePlugins('blog');
                $this->view->layout = Plugin::moduleLayout($this->view->plugins);
                $this->view->pager = $pager;
                $this->view->settings = $config->blog;
                
                $this->view->render('mod_index', 'view/front/themes/' . $this->core->theme . '/');
            }
        }
        
        /**
         * render
         *
         * @return void
         * @throws FileNotFoundException
         * @throws NotFoundException
         */
        public function render(): void
        {
            $lg = Language::$lang;
            
            $this->view->title = str_replace('[COMPANY]', $this->core->company, Language::$word->META_T31);
            $this->view->keywords = null;
            $this->view->description = null;
            $this->view->meta = null;
            $this->view->crumbs = [array(0 => Language::$word->HOME, 1 => ''), Language::$word->_MOD_AM_TITLE];
            
            $mSql = array("title$lg as title", "info$lg", "keywords$lg", "description$lg");
            $this->view->data = $this->db->select(Module::mTable, $mSql)->where('modalias', 'blog', '=')->first()->run();
            
            $sql = "
            SELECT a.*, a.slug$lg as slug, c.slug$lg as catslug, c.name$lg as catname, CONCAT(u.fname,' ',u.lname) as user, u.username,
                   GROUP_CONCAT(m.title$lg SEPARATOR ', ') as memberships
              FROM `" . Blog::mTable . '` AS a
              LEFT JOIN `' . User::mTable . '` as u ON u.id = a.user_id
              LEFT JOIN `' . Blog::cTable . '` as c ON c.id = a.category_id
              LEFT JOIN `' . Membership::mTable . "` as m ON FIND_IN_SET(m.id, a.membership_id)
              WHERE a.slug$lg = ?
              AND a.active = ?
              GROUP BY a.id
            ";
            
            if (!$this->view->row = $this->db->rawQuery($sql, array($this->view->matches, 1))->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid blog slug ' . $this->view->matches . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                    $this->view->render('error', 'view/front/themes/' . $this->core->theme . '/');
                } else {
                    $this->view->title = Language::$word->META_ERROR;
                    $this->view->render('404', 'view/front/themes/' . $this->core->theme . '/');
                }
            } else {
                $this->view->title = Url::formatMeta($this->view->row->{'title' . $lg}, $this->core->company);
                $this->view->keywords = $this->view->row->{'keywords' . $lg};
                $this->view->description = $this->view->row->{'description' . $lg};
                $this->view->crumbs = [array(0 => Language::$word->HOME, 1 => ''), array(0 => $this->view->data->title, 1 => $this->core->modname['blog']), $this->view->row->{'title' . $lg}];
                
                $this->view->meta = '<meta property="og:type" content="article" />' . "\n";
                $this->view->meta .= '<meta property="og:title" content="' . $this->view->row->{'title' . Language::$lang} . '" />' . "\n";
                $this->view->meta .= '<meta property="og:image" content="' . Blog::hasThumb($this->view->row->thumb, $this->view->row->id) . '" />' . "\n";
                $this->view->meta .= '<meta property="og:description" content="' . $this->view->title . '" />' . "\n";
                $this->view->meta .= '<meta property="og:url" content="' . Url::url($this->core->modname['blog'], $this->view->matches) . '" />' . "\n";
                
                $this->_doHits($this->view->row->id);
                
                $config = $this->_config();
                $this->view->plugins = Plugin::getModulePlugins('blog');
                $this->view->layout = Plugin::moduleLayout($this->view->plugins);
                $this->view->images = Utility::jSonToArray($this->view->row->images);
                //$this->view->membership_access = $this->getMembershipAccess($this->view->row->membership_id);
                $this->view->settings = $config->blog;
                
                $this->view->render('mod_index', 'view/front/themes/' . $this->core->theme . '/');
            }
            
        }
        
        /**
         * category
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function category(): void
        {
            $lg = Language::$lang;
            
            $this->view->title = str_replace('[COMPANY]', $this->core->company, Language::$word->META_T31);
            $this->view->keywords = null;
            $this->view->description = null;
            $this->view->meta = null;
            $this->view->crumbs = [array(0 => Language::$word->HOME, 1 => ''), Language::$word->_MOD_AM_TITLE];
            
            $mSql = array("title$lg as title", "info$lg", "keywords$lg", "description$lg");
            $this->view->data = $this->db->select(Module::mTable, $mSql)->where('modalias', 'blog', '=')->first()->run();
            
            if (!$this->view->row = $this->db->select(Blog::cTable)->where("slug$lg", $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid blog category slug ' . $this->view->matches . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                    $this->view->render('error', 'view/front/themes/' . $this->core->theme . '/');
                } else {
                    $this->view->title = Language::$word->META_ERROR;
                    $this->view->render('404', 'view/front/themes/' . $this->core->theme . '/');
                }
            } else {
                if (isset($_GET['order']) and count(explode('|', $_GET['order'])) == 2) {
                    list($sort, $order) = explode('|', $_GET['order']);
                    $sort = Validator::sanitize($sort, 'string', 16);
                    $order = Validator::sanitize($order, 'string', 4);
                    if (in_array($sort, array(
                        'title',
                        'rating',
                        'hits',
                        'memberships',
                        'created'
                    ))) {
                        $ord = ($order == 'DESC') ? ' DESC' : ' ASC';
                        $sorting = $sort . $ord;
                    } else {
                        $sorting = ' created DESC';
                    }
                } else {
                    $sorting = ' created DESC';
                }
                $config = $this->_config();
                
                $pSql = '
                SELECT COUNT(a.id) as items
                  FROM `' . Blog::mTable . '` as a
                  INNER JOIN `' . Blog::rTable . '` as rc ON a.id = rc.item_id
                  WHERE rc.category_id = ?
                  AND a.active = ?
                  LIMIT 1
                ';
                
                $total = $this->db->rawQuery($pSql, array($this->view->row->id, 1))->first()->run();
                
                $pager = Paginator::instance();
                $pager->items_total = $total->items;
                $pager->default_ipp = $this->view->row->perpage;
                $pager->path = Url::url(Router::$path, '?');
                $pager->paginate();
                
                $sql = "
                SELECT a.id, a.created, a.title$lg as title, a.slug$lg as slug, a.body$lg as body, a.thumb, a.rating, a.membership_id, c.slug$lg as cslug, c.name$lg as ctitle,
                       GROUP_CONCAT(m.title$lg SEPARATOR ', ') as memberships,
                       (SELECT COUNT(parent_id) FROM `" . Module::mcTable . '`
                         WHERE `' . Module::mcTable . "`.parent_id = a.id
                         AND section = 'blog') as comments
                  FROM `" . Blog::mTable . '` as a
                  LEFT JOIN `' . Blog::cTable . '` as c ON c.id = a.category_id
                  INNER JOIN `' . Blog::rTable . '` as rc ON a.id = rc.item_id
                  LEFT JOIN `' . Membership::mTable . "` as m ON FIND_IN_SET(m.id, a.membership_id)
                  WHERE rc.category_id = ?
                  AND a.active = ?
                  AND c.active = ?
                  GROUP BY a.id
                  ORDER BY $sorting " . $pager->limit;
                
                $this->view->title = Url::formatMeta($this->view->row->{'name' . $lg}, $this->core->company);
                $this->view->keywords = $this->view->row->{'keywords' . $lg};
                $this->view->description = $this->view->row->{'description' . $lg};
                $this->view->crumbs = [array(0 => Language::$word->HOME, 1 => ''), array(0 => $this->view->data->title, 1 => $this->core->modname['blog']), $this->view->row->{'name' . $lg}];
                
                $this->view->rows = $this->db->rawQuery($sql, array($this->view->row->id, 1, 1))->run();
                $this->view->plugins = Plugin::getModulePlugins('blog');
                $this->view->layout = Plugin::moduleLayout($this->view->plugins);
                $this->view->pager = $pager;
                $this->view->settings = $config->blog;
                
                $this->view->render('mod_index', 'view/front/themes/' . $this->core->theme . '/');
            }
        }
        
        /**
         * archive
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function archive(): void
        {
            $lg = Language::$lang;
            
            $this->view->title = str_replace('[COMPANY]', $this->core->company, Language::$word->META_T31);
            $this->view->keywords = null;
            $this->view->description = null;
            $this->view->meta = null;
            $this->view->crumbs = [array(0 => Language::$word->HOME, 1 => ''), Language::$word->_MOD_AM_TITLE];
            
            $mSql = array("title$lg as title", "info$lg", "keywords$lg", "description$lg");
            if (!$this->view->data = $this->db->select(Module::mTable, $mSql)->where('modalias', 'blog', '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid blog module detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                    $this->view->render('error', 'view/front/themes/' . $this->core->theme . '/');
                } else {
                    $this->view->title = Language::$word->META_ERROR;
                    $this->view->render('404', 'view/front/themes/' . $this->core->theme . '/');
                }
            } else {
                $sql = "
                SELECT a.*, a.slug$lg as slug, a.title$lg as title
                  FROM " . Blog::mTable . ' as a
                  WHERE a.active = ?
                  AND YEAR(a.created) = ?
                  AND MONTH(a.created) = ?
                  GROUP BY a.id
                  ORDER BY a.created DESC
                ';
                
                $this->view->title = Url::formatMeta($this->view->data->title, $this->core->company);
                $this->view->keywords = $this->view->data->{'keywords' . $lg};
                $this->view->description = $this->view->data->{'description' . $lg};
                $this->view->crumbs = [array(0 => Language::$word->HOME, 1 => ''), array(0 => $this->view->data->title, 1 => $this->core->modname['blog']), Language::$word->_MOD_AM_SUB42];
                
                $this->view->rows = $this->db->rawQuery($sql, array(1, $this->view->matches[0], $this->view->matches[1]))->run();
                $this->view->plugins = Plugin::getModulePlugins('blog');
                $this->view->layout = Plugin::moduleLayout($this->view->plugins);
                
                $config = $this->_config();
                $this->view->settings = $config->blog;
                
                $this->view->render('mod_index', 'view/front/themes/' . $this->core->theme . '/');
            }
        }
        
        /**
         * tag
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function tag(): void
        {
            $lg = Language::$lang;
            
            $this->view->title = str_replace('[COMPANY]', $this->core->company, Language::$word->META_T31);
            $this->view->keywords = null;
            $this->view->description = null;
            $this->view->meta = null;
            $this->view->crumbs = [array(0 => Language::$word->HOME, 1 => ''), Language::$word->_MOD_AM_TITLE];
            
            $mSql = array("title$lg as title", "info$lg", "keywords$lg", "description$lg");
            if (!$this->view->data = $this->db->select(Module::mTable, $mSql)->where('modalias', 'blog', '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid blog module detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                    $this->view->render('error', 'view/front/themes/' . $this->core->theme . '/');
                } else {
                    $this->view->title = Language::$word->META_ERROR;
                    $this->view->render('404', 'view/front/themes/' . $this->core->theme . '/');
                }
            } else {
                $slug = $this->view->matches;
                $sql = "
                SELECT id, slug$lg as slug, title$lg as title, thumb
                  FROM `" . Blog::mTable . "`
                  WHERE tags$lg = '$slug'
                  OR tags$lg LIKE '$slug,%'
                  OR tags$lg LIKE '%,$slug,%'
                  OR tags$lg LIKE '%,$slug'
                  AND active = ?
                  ORDER BY created DESC
                ";
                
                $this->view->title = Url::formatMeta($this->view->data->title, $this->core->company);
                $this->view->keywords = $this->view->data->{'keywords' . $lg};
                $this->view->description = $this->view->data->{'description' . $lg};
                $this->view->crumbs = [array(0 => Language::$word->HOME, 1 => ''), array(0 => $this->view->data->title, 1 => $this->core->modname['blog']), Language::$word->_MOD_AM_SUB79];
                
                $this->view->rows = $this->db->rawQuery($sql, array(1))->run();
                $this->view->plugins = Plugin::getModulePlugins('blog');
                $this->view->layout = Plugin::moduleLayout($this->view->plugins);
                
                $config = $this->_config();
                $this->view->settings = $config->blog;
                
                $this->view->render('mod_index', 'view/front/themes/' . $this->core->theme . '/');
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
                        case 'like':
                            $this->like(Filter::$id);
                            break;
                        
                        default:
                            Url::invalidMethod();
                            break;
                    }
                } else {
                    Url::invalidMethod();
                }
            } else {
                Url::invalidMethod();
            }
        }
        
        /**
         * like
         *
         * @param int $id
         * @return void
         */
        private function like(int $id): void
        {
            if ($id) {
                $type = Validator::sanitize($_POST['type'], 'string', 4);
                if (!Session::cookieExists('BLOG_voted', $id)) {
                    $this->db->rawQuery('UPDATE `' . Blog::mTable . "` SET like_$type = like_$type + 1 WHERE id = ?", array($id))->run();
                    $json['status'] = 'success';
                } else {
                    $json['status'] = 'error';
                }
                print json_encode($json);
            }
        }
        
        /**
         * _doHits
         *
         * @param int $id
         * @return void
         */
        private function _doHits(int $id): void
        {
            $this->db->rawQuery('UPDATE `' . Blog::mTable . '` SET `hits` = `hits` + 1 WHERE id = ?', array($id))->run();
        }
        
        /**
         * _config
         *
         * @return mixed
         */
        private function _config(): mixed
        {
            return json_decode(File::loadFile(AMODPATH . 'blog/config.json'));
        }
    }