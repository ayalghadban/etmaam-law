<?php
    /**
     * Blog CLass
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Blog.php, v1.00 5/19/2023 1:39 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Module\Blog;
    
    use Wojo\Container\Container;
    use Wojo\Core\Content;
    use Wojo\Core\Membership;
    use Wojo\Core\Module;
    use Wojo\Core\User;
    use Wojo\Core\View;
    use Wojo\Database\Database;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Blog
    {
        const mTable = 'mod_blog';
        const cTable = 'mod_blog_categories';
        const rTable = 'mod_blog_related_categories';
        const gTable = 'mod_blog_gallery';
        const tTable = 'mod_blog_tags';
        
        const BLOGDATA = 'blog/data/';
        const BLOGFILES = 'blog/datafiles/';
        const FILES = 'zip,pdf,rar,mp3';
        const MAXIMG = 5242880;
        const MAXFILE = 52428800;
        
        public  $thumb_w;
        public  $thumb_h;
        public  $auto_approve;
        public $blacklist_words;
        public  $cdateformat;
        public  $char_limit;
        public  $comperpage;
        public  $cperpage;
        public  $email_req;
        public  $flayout;
        public  $fperpage;
        public  $latestperpage;
        public  $notify_new;
        public  $popperpage;
        public  $public_access;
        public  $show_captcha;
        public  $show_counter;
        public  $show_username;
        public  $show_www;
        public  $sorting;
        public  $upost;
        public  $username_req;
        
        
        /**
         *
         */
        public function __construct()
        {
            $this->config();
        }
        
        /**
         * config
         *
         * @return void
         */
        private function config(): void
        {
            
            $row = json_decode(File::loadFile(AMODPATH . 'blog/config.json'));
            
            $this->thumb_w = $row->blog->thumb_w;
            $this->thumb_h = $row->blog->thumb_h;
            $this->auto_approve = $row->blog->auto_approve;
            $this->blacklist_words = $row->blog->blacklist_words;
            $this->cdateformat = $row->blog->cdateformat;
            $this->char_limit = $row->blog->char_limit;
            $this->comperpage = $row->blog->comperpage;
            $this->cperpage = $row->blog->cperpage;
            $this->email_req = $row->blog->email_req;
            $this->flayout = $row->blog->flayout;
            $this->fperpage = $row->blog->fperpage;
            $this->latestperpage = $row->blog->latestperpage;
            $this->notify_new = $row->blog->notify_new;
            $this->popperpage = $row->blog->popperpage;
            $this->public_access = $row->blog->public_access;
            $this->show_captcha = $row->blog->show_captcha;
            $this->show_counter = $row->blog->show_counter;
            $this->show_username = $row->blog->show_username;
            $this->show_www = $row->blog->show_www;
            $this->sorting = $row->blog->sorting;
            $this->upost = $row->blog->upost;
            $this->username_req = $row->blog->username_req;
        }
        
        /**
         * categoryTree
         *
         * @return array
         */
        public static function categoryTree(): array
        {
            $data = Database::Go()->select(self::cTable, array('id', 'parent_id', ' name' . Language::$lang))->orderBy('parent_id', 'ASC')->orderBy('sorting', 'ASC')->run();
            
            $cats = array();
            $result = array();
            
            foreach ($data as $row) {
                $cats['id'] = $row->id;
                $cats['name'] = $row->{'name' . Language::$lang};
                $cats['parent_id'] = $row->parent_id;
                $result[$row->id] = $cats;
            }
            return $result;
        }
        
        /**
         * getSortCategoryList
         *
         * @param array $array
         * @param int $parent_id
         * @return string
         */
        public static function getSortCategoryList(array $array, int $parent_id = 0): string
        {
            $submenu = false;
            $icon = '<i class="icon negative trash"></i>';
            $html = '';
            foreach ($array as $key => $row) {
                if ($row['parent_id'] == $parent_id) {
                    if ($submenu === false) {
                        $submenu = true;
                        $html .= "<ul class=\"wojo nestable list\">\n";
                    }
                    $html .= '
                         <li class="item" data-id="' . $row['id'] . '">'
                        . '<div class="content"><div class="handle"><i class="icon grip horizontal"></i></div><div class="text">
                         <a href="' . Url::url('admin/modules/blog/category', $row['id']) . '">' . $row['name'] . '</a></div>'
                        . '<div class="actions"><a class="data" data-set=\'{"option":[{"action": "delete","title": "' . Validator::sanitize($row['name'], 'chars') . '","id":' . $row['id'] . ', "type":"category"}],"action":"delete","parent":"li", "redirect":"' . Url::url('admin/modules/blog/categories') . '","url":"modules/blog/action/"}\'>' . $icon . '</a>
                         </div></div>';
                    $html .= self::getSortCategoryList($array, $key);
                    $html .= "</li>\n";
                }
            }
            unset($row);
            
            if ($submenu === true) {
                $html .= "</ul>\n";
            }
            
            return $html;
        }
        
        /**
         * getCategoryDropList
         *
         * @param array $array
         * @param int $parent_id
         * @param int $level
         * @param string $spacer
         * @param int $selected
         * @return string
         */
        public static function getCategoryDropList(array $array, int $parent_id, int $level = 0, string $spacer = '--', int $selected = 0): string
        {
            $html = '';
            if ($array) {
                foreach ($array as $key => $row) {
                    $sel = ($row['id'] == $selected) ? " selected=\"selected\"" : '';
                    if ($parent_id == $row['parent_id']) {
                        $html .= "<option value=\"" . $row['id'] . "\"" . $sel . '>';
                        $html .= str_repeat($spacer, $level);
                        $html .= $row['name'] . "</option>\n";
                        $level++;
                        $html .= self::getCategoryDropList($array, $key, $level, $spacer, $selected);
                        $level--;
                    }
                }
                unset($row);
            }
            return $html;
        }
        
        /**
         * getCatCheckList
         *
         * @param array $array
         * @param int $parent_id
         * @param int $level
         * @param string $spacer
         * @param bool $selected
         * @return string
         */
        public static function getCatCheckList(array $array, int $parent_id, int $level = 0, string $spacer = '--', bool $selected = false): string
        {
            
            $html = '';
            if ($array) {
                if ($selected) {
                    $arr = explode(',', $selected);
                }
                foreach ($array as $key => $row) {
                    $active = ($selected and in_array($row['id'], $arr)) ? " checked=\"checked\"" : '';
                    
                    if ($parent_id == $row['parent_id']) {
                        $html .= "<div class=\"item\"><div class=\"wojo small checkbox fitted inline\"> <input id=\"ckb_" . $row['id'] . "\" type=\"checkbox\" name=\"categories[]\" value=\"" . $row['id'] . "\"" . $active . '>';
                        $html .= "<label for=\"ckb_" . $row['id'] . "\">";
                        $html .= str_repeat($spacer, $level);
                        
                        $html .= $row['name'] . "</label></div></div>\n";
                        $level++;
                        $html .= self::getCatCheckList($array, $key, $level, $spacer, $selected);
                        $level--;
                    }
                }
                unset($row);
            }
            return $html;
        }
        
        /**
         * catList
         *
         * @return array
         */
        public static function catList(): array
        {
            $lg = Language::$lang;
            
            $sql = "
            SELECT c.id, c.parent_id, c.name$lg as name, c.slug$lg as slug,
                   (SELECT COUNT(p.id) FROM " . self::mTable . ' p
                   INNER JOIN `' . self::rTable . '` rc ON p.id = rc.item_id
                   WHERE rc.category_id = c.id AND p.active = ?) as items
              FROM `' . self::cTable . '` as c
              GROUP BY c.id, parent_id, sorting
              ORDER BY parent_id, sorting
            ';
            
            $menu = array();
            $result = array();
            
            if ($data = Database::Go()->rawQuery($sql, array(1))->run()) {
                foreach ($data as $row) {
                    $menu['id'] = $row->id;
                    $menu['name'] = $row->name;
                    $menu['parent_id'] = $row->parent_id;
                    $menu['slug'] = $row->slug;
                    $menu['items'] = $row->items;
                    
                    $result[$row->id] = $menu;
                }
            }
            return $result;
        }
        
        /**
         * renderCategories
         *
         * @param array|null $array
         * @param int $parent_id
         * @param string $menu_id
         * @param string $class
         * @return string
         */
        public static function renderCategories(array|null $array, int $parent_id = 0, string $menu_id = 'blogcats', string $class = 'vertical-menu'): string
        {
            $core = Container::instance()->get('core');
            $html = '';
            
            if (is_array($array) && count($array) > 0) {
                $submenu = false;
                $attr = (!$parent_id) ? ' class="' . $class . '" id="' . $menu_id . '"' : ' class="menu-submenu"';
                
                foreach ($array as $key => $row) {
                    
                    if ($row['parent_id'] == $parent_id) {
                        if ($submenu === false) {
                            $submenu = true;
                            
                            $html .= '<ul' . $attr . ">\n";
                        }
                        
                        $url = Url::url($core->modname['blog'] . '/' . $core->modname['blog-cat'], $row['slug'] . Url::buildQuery());
                        
                        $counter = '<span>(' . $row['items'] . ')</span> ';
                        $active = (isset(Content::$segments[2]) and Content::$segments[2] == $row['slug']) ? ' active' : 'normal';
                        $link = '<a href="' . $url . '" class="' . $active . '" title="' . $row['name'] . '">' . $row['name'] . $counter . '</a>';
                        
                        
                        $html .= '<li>';
                        $html .= $link;
                        $html .= self::renderCategories($array, $key);
                        $html .= "</li>\n";
                    }
                }
                unset($row);
                
                if ($submenu === true) {
                    $html .= "</ul>\n";
                }
            }
            return $html;
        }
        
        /**
         * getMembershipAccess
         *
         * @param string $memberships
         * @return bool
         */
        public static function getMembershipAccess(string $memberships): bool
        {
            $lg = Language::$lang;
            $tpl = new View();
            
            $m_arr = explode(',', $memberships);
            $auth = Container::instance()->get('auth');
            
            if ($memberships > 0) {
                if ($auth->logged_in and in_array($auth->membership_id, $m_arr)) {
                    return true;
                } else {
                    $tpl->data = Database::Go()->rawQuery("SELECT title$lg as title FROM `" . Membership::mTable . '` WHERE id IN(' . $memberships . ')')->run();
                    try {
                        echo $tpl->snippet('membershipError', THEMEBASE . 'snippets/');
                    } catch (FileNotFoundException) {
                    }
                    return false;
                }
            } else {
                return true;
            }
        }
        
        /**
         * LatestPlugin
         *
         * @return mixed
         */
        public function LatestPlugin(): mixed
        {
            $lg = Language::$lang;
            $limit = 'LIMIT 0, ' . $this->latestperpage;
            
            $sql = "
            SELECT b.id, b.title$lg as title, slug$lg as slug, b.body$lg as body, b.thumb, u.avatar, CONCAT(u.fname,' ',u.lname) as name, u.username, b.created
              FROM `" . self::mTable . '` as b
              LEFT JOIN `' . User::mTable . '` as u ON u.id = b.user_id
              WHERE b.active = ?
              ORDER BY b.created DESC
              ' . $limit;
            
            return Database::Go()->rawQuery($sql, array(1))->run();
        }
        
        /**
         * blogCombo
         *
         * @return array
         */
        public function blogCombo(): array
        {
            $lg = Language::$lang;
            $data = array();
            
            //archive
            $aSql = "
            SELECT YEAR(created) as year, DATE_FORMAT(created, '%m') as month,COUNT(id) as total
              FROM `" . self::mTable . '`
              WHERE active = ?
              AND created <= DATE_SUB(NOW(), INTERVAL 1 MONTH)
              GROUP BY year, month
              ORDER BY year DESC, month DESC
            ';
            $data['archive'] = Database::Go()->rawQuery($aSql, array(1))->run();
            
            //popular
            $pSql = "
            SELECT title$lg as title, slug$lg as slug, thumb, created, id
              FROM `" . self::mTable . "`
              WHERE active = ?
              ORDER BY hits DESC
              LIMIT {$this->popperpage}
            ";
            $data['popular'] = Database::Go()->rawQuery($pSql, array(1))->run();
            
            //comments
            $cSql = "
            SELECT c.body, a.title$lg as title, a.slug$lg as slug, c.created
              FROM `" . Module::mcTable . '` as c
              LEFT JOIN `' . self::mTable . "` as a ON a.id = c.parent_id
              WHERE c.section = ?
              AND c.active = ?
              AND a.active = ?
              ORDER BY c.created DESC
              LIMIT {$this->comperpage}
            ";
            $data['comments'] = Database::Go()->rawQuery($cSql, array('blog', 1, 1))->run();
            
            return $data;
        }
        
        /**
         * blogFooter
         *
         * @return array
         */
        public function blogFooter(): array
        {
            $lg = Language::$lang;
            $data = array();
            
            //archive
            $aSql = "
            SELECT YEAR(created) as year, DATE_FORMAT(created, '%m') as month,COUNT(id) as total
              FROM `" . self::mTable . '`
              WHERE active = ?
              AND created <= DATE_SUB(NOW(), INTERVAL 1 MONTH)
              GROUP BY year, month
              ORDER BY year DESC, month DESC
            ';
            $data['archive'] = Database::Go()->rawQuery($aSql, array(1))->run();
            
            //popular
            $pSql = "
            SELECT title$lg as title, slug$lg as slug, thumb, created, id
              FROM `" . self::mTable . "`
              WHERE active = ?
              ORDER BY hits DESC
              LIMIT {$this->popperpage}
            ";
            $data['popular'] = Database::Go()->rawQuery($pSql, array(1))->run();
            
            return $data;
        }
        
        /**
         * searchResults
         *
         * @param string|null $keyword
         * @return mixed
         */
        public static function searchResults(string|null $keyword): mixed
        {
            $lg = Language::$lang;
            $keyword = Validator::sanitize($keyword, 'text', 20);
            $sql = "
            SELECT title$lg AS title, body$lg AS body, slug$lg AS slug
              FROM `" . self::mTable . "`
              WHERE MATCH (title$lg) AGAINST ('" . $keyword . "*' IN BOOLEAN MODE)
              OR MATCH (body$lg) AGAINST ('" . $keyword . "*' IN BOOLEAN MODE)
              AND active = ?
              ORDER BY created DESC
              LIMIT 10
          ";
            
            return Database::Go()->rawQuery($sql, array(1))->run();
        }
        
        /**
         * sitemap
         *
         * @return mixed
         */
        public static function sitemap(): mixed
        {
            $lg = Language::$lang;
            
            return Database::Go()->select(self::mTable, array("title$lg AS title", "slug$lg AS slug"))->where('active', 1, '=')->orderBy('created', 'DESC')->run();
        }
        
        /**
         * hasThumb
         *
         * @param string|null $thumb
         * @param int $id
         * @return string
         */
        public static function hasThumb(string|null $thumb, int $id): string
        {
            return $thumb ? FMODULEURL . self::BLOGDATA . $id . '/thumbs/' . $thumb : UPLOADURL . 'blank.jpg';
        }
        
        /**
         * hasImage
         *
         * @param string|null $image
         * @param int $id
         * @return string
         */
        public static function hasImage(string|null $image, int $id): string
        {
            return $image ? FMODULEURL . self::BLOGDATA . $id . '/' . $image : UPLOADURL . 'blank.jpg';
        }
    }