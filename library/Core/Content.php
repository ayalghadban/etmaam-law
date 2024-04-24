<?php
    /**
     * Content Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Content.php, v1.00 4/29/2023 9:25 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Core;
    
    use Wojo\Container\Container;
    use Wojo\Database\Database;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Exception\NotFoundException;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Url\Url;
    use Wojo\Utility\Utility;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Content
    {
        const mTable = 'menus';
        const pTable = 'pages';
        const lTable = 'layout';
        
        const cTable = 'countries';
        const dcTable = 'coupons';
        const eTable = 'email_templates';
        const cfTable = 'custom_fields';
        const cfdTable = 'custom_fields_data';
        
        public static object|array $pagedata = array();
        public static array $segments = array();
        
        /**
         * renderMenu
         *
         * @param array|null $array
         * @param int $parent_id
         * @param string $menu_id
         * @param string $class
         * @return void
         */
        public static function renderMenu(array|null $array, int $parent_id = 0, string $menu_id = 'main-menu', string $class = 'top-menu'): void
        {
            if (is_array($array) && count($array) > 0) {
                $submenu = false;
                $attr = (!$parent_id) ? ' class="' . $class . '" id="' . $menu_id . '"' : ' class="menu-submenu"';
                $attr2 = (!$parent_id) ? ' class="nav-item"' : ' class="nav-submenu-item"';
                $core = Container::instance()->get('core');
                $pageslug = $core->pageslug;
                $modslug = $core->slugs->module;
                $segments = self::$segments;
                
                
                foreach ($array as $key => $row) {
                    if ($row['parent_id'] == $parent_id) {
                        
                        if ($submenu === false) {
                            $submenu = true;
                            print '<ul' . $attr . ">\n";
                        }
                        
                        $url = Url::url($pageslug, $row['pslug']);
                        $homeactive = (isset(self::$pagedata->page_type) and self::$pagedata->page_type == 'home') ? 'active' : 'normal';
                        
                        $name = ($parent_id == 0) ? '' . $row['name'] : $row['name'];
                        $home = ($row['home_page']) ? ' homepage' : '';
                        $icon = ($row['icon']) ? '<i class="' . $row['icon'] . '"></i>' : '';
                        $caption = ($row['caption']) ? '<small>' . $row['caption'] . '</small>' : null;
                        $link = '';
                        switch ($row['content_type']) {
                            case 'module':
                                $mactive = (in_array($modslug->{$row['mslug']}, $segments) ? 'active' : 'normal');
                                $murl = $row['home_page'] ? SITEURL : Url::url($modslug->{$row['mslug']});
                                if ($row['home_page']) {
                                    $link = '<a href="' . SITEURL . '" class="' . $homeactive . $home . '">' . $icon . '<span>'.$name.'</span>' . $caption . '</a>';
                                } else {
                                    $link = '<a data-mslug="' . $modslug->{$row['mslug']} . '" href="' . $murl . '" class="' . $mactive . '">' . $icon . '<span>'.$name.'</span>' . $caption . '</a>';
                                }
                                break;
                            
                            case 'page':
                                $active = ((in_array($row['pslug'], $segments) and $row['mod_id'] == 0) ? 'active' : 'normal');
                                if ($row['home_page']) {
                                    $link = '<a href="' . SITEURL . '" class="' . $homeactive . $home . '">' . $icon . '<span>'.$name.'</span>' . $caption . '</a>';
                                } else {
                                    $link = '<a href="' . $url . '" class="' . $active . $home . '">' . $icon . '<span>'.$name.'</span>' . $caption . '</a>';
                                }
                                break;
                            
                            case 'web':
                                $wlink = ($row['link'] == '#') ? '#' : $row['link'];
                                $wtarget = ($row['link'] == '#') ? null : ' target="' . $row['target'] . '"';
                                $link = '<a href="' . Url::out_url($wlink) . '"' . $wtarget . '>' . $icon . '<span>'.$name.'</span>' . $caption . '</a>';
                                break;
                        }
                        
                        print '<li' . $attr2 . ' data-id="' . $row['id'] . '" data-columns="' . $row['cols'] . '">';
                        print $link;
                        self::renderMenu($array, $key);
                        print "</li>\n";
                    }
                }
                unset($row);
                
                if ($submenu === true) {
                    print "</ul>\n";
                }
            }
        }
        
        /**
         * parseContentData
         *
         * @param string|null $body
         * @param bool $is_builder
         * @return string|void
         */
        public static function parseContentData(string|null $body, bool $is_builder = false)
        {
            $pattern = '/%%(.*?)%%/';
            $matches = array();
            $all = array();
            $tpl = new View();
            
            try {
                $core = Container::instance()->get('core');
                $auth = Container::instance()->get('auth');
                preg_match_all($pattern, $body ?? '', $matches);
                
                if ($matches) {
                    $ids = array();
                    foreach ($matches[1] as $val) {
                        $v = explode('|', $val);
                        $ids[] = $v[3];
                    }
                    if ($ids) {
                        $all = Plugin::renderAll(Utility::implodeFields($ids));
                    }
                    
                    foreach ($matches[1] as $k => $row) {
                        $items = explode('|', $row);
                        
                        switch ($items[1]) {
                            case 'uplugin':
                                $ubody = array_column($all, 'body', 'id');
                                $html = '<div data-wuplugin-id="' . $items[3] . '">' . $ubody[$items[3]] . '</div>';
                                break;
                            
                            case 'plugin':
                                $tpl->id = $items[3];
                                $tpl->plugin_id = $items[2];
                                $tpl->all = $all;
                                $tpl->core = $core;
                                $tpl->auth = $auth;
                                
                                $contents = File::is_File(FPLUGPATH . $items[0] . '/themes/' . $core->theme . '/index.tpl.php') ?
                                    $tpl->snippet('index', FPLUGPATH . $items[0] . '/themes/' . $core->theme . '/') :
                                    $tpl->snippet('index', FPLUGPATH . $items[0] . '/');
                                
                                if ($is_builder) {
                                    $html = '<div data-mode="readonly" data-wplugin-id="' . $items[3] . '"
                                              data-wplugin-plugin_id="' . $items[2] . '"
                                              data-wplugin-alias="' . $items[0] . '">' . $contents . '</div>';
                                } else {
                                    $html = $contents;
                                }
                                break;
                            
                            default:
                                $group = explode('/', $items[0]);
                                $tpl->id = $items[3];
                                $tpl->module_id = $items[2];
                                $tpl->alias = $items[0];
                                $tpl->core = $core;
                                $tpl->auth = $auth;
                                
                                $contents = File::is_File(FMODPATH . $items[0] . '/themes/' . $core->theme . '/index.tpl.php') ?
                                    $tpl->snippet('index', FMODPATH . $items[0] . '/themes/' . $core->theme . '/') :
                                    $tpl->snippet('index', FMODPATH . $items[0] . '/');
                                
                                if ($is_builder) {
                                    $html = '<div data-mode="readonly" data-wmodule-group="' . $group[0] . '"
                                              data-wmodule-module_id="' . $items[2] . '"
                                              data-wmodule-id="' . $items[3] . '"
                                              data-wmodule-alias="' . $items[0] . '">' . $contents . '</div>';
                                } else {
                                    $html = $contents;
                                }
                                break;
                        }
                        $body = str_replace($matches[0][$k], $html, $body);
                    }
                    return Url::out_url($body);
                }
            } catch (NotFoundException) {
            }
            
            
        }
        
        /**
         * getMenuDropList
         *
         * @param array $array
         * @param int $parent_id
         * @param int $level
         * @param string $spacer
         * @param bool $selected
         * @return string
         */
        public static function getMenuDropList(array $array, int $parent_id, int $level = 0, string $spacer = '--', bool $selected = false): string
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
                        $html .= self::getMenuDropList($array, $key, $level, $spacer, $selected);
                        $level--;
                    }
                }
                unset($row);
            }
            return $html;
        }
        
        /**
         * getSortMenuList
         *
         * @param array $array
         * @param int $parent_id
         * @return string
         */
        public static function getSortMenuList(array $array, int $parent_id = 0): string
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
                    $html .= '<li class="item" data-id="' . $row['id'] . '">';
                    $html .= '<div class="content"><div class="handle"><i class="icon grip horizontal"></i></div>';
                    $html .= '<div class="text"><a href="' . Url::url('admin/menus/edit', $row['id']) . '/">' . $row['name'] . '</a></div>';
                    $html .= '<div class="actions">';
                    $html .= '<a class="data" data-set=\'{"option":[{"action": "trash","title": "' . Validator::sanitize($row['name'], 'chars') . '","id":' . $row['id'] . '}],"action":"trash","parent":"li", "redirect":"' . Url::url('admin/menus') . '", "url":"menus/action/"}\'>' . $icon . '</a></div>';
                    $html .= '</div>';
                    $html .= self::getSortMenuList($array, $key);
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
         * menuTree
         *
         * @param bool $active
         * @return array
         */
        public static function menuTree(bool $active = false): array
        {
            $is_active = ($active) ? 'WHERE active = 1' : null;
            $data = Database::Go()->rawQuery('SELECT * FROM `' . Content::mTable . "` $is_active ORDER BY parent_id, sorting")->run();
            
            $menu = array();
            $result = array();
            if ($data) {
                foreach ($data as $row) {
                    $menu['id'] = $row->id;
                    $menu['name'] = $row->{'name' . Language::$lang};
                    $menu['parent_id'] = $row->parent_id;
                    $menu['caption'] = $row->{'caption' . Language::$lang};
                    $menu['page_id'] = $row->page_id;
                    $menu['mod_id'] = $row->mod_id;
                    $menu['content_type'] = $row->content_type;
                    $menu['link'] = $row->link;
                    $menu['home_page'] = $row->home_page;
                    $menu['cols'] = $row->cols;
                    $menu['active'] = $row->active;
                    $menu['target'] = $row->target;
                    $menu['icon'] = $row->icon;
                    $menu['pslug'] = $row->{'page_slug' . Language::$lang};
                    $menu['mslug'] = $row->mod_slug;
                    
                    $result[$row->id] = $menu;
                }
            }
            return $result;
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
            $sql = "
            SELECT title$lg as title, body$lg as body, slug$lg as slug , page_type
              FROM `" . self::pTable . "`
              WHERE active = ?
              AND page_type = ?
              AND (`title$lg` LIKE '%" . $keyword . "%')
              OR (`body$lg` LIKE '%" . $keyword . "%')
              ORDER BY created DESC
              LIMIT 10
            ";
            
            return Database::Go()->rawQuery($sql, array(1, 'normal'))->run();
        }
        
        /**
         * validatePage
         *
         * @return bool
         */
        public static function validatePage(): bool
        {
            $lg = Language::$lang;
            $tpl = new View();
            try {
                $auth = Container::instance()->get('auth');
                switch (self::$pagedata->access) {
                    case 'Registered':
                        if (!$auth->logged_in) {
                            echo $tpl->snippet('registerError', THEMEBASE . 'snippets/');
                            return false;
                        } else {
                            return true;
                        }
                        
                        break;
                    
                    case 'Membership':
                        $m_arr = explode(',', self::$pagedata->membership_id);
                        if ($auth->logged_in and in_array($auth->membership_id, $m_arr)) {
                            return true;
                        } else {
                            $tpl = new View();
                            $tpl->data = Database::Go()->rawQuery("SELECT title$lg as title FROM `" . Membership::mTable . '` WHERE id IN(' . self::$pagedata->membership_id . ')')->run();
                            echo $tpl->snippet('membershipError', THEMEBASE . 'snippets/');
                            return false;
                        }
                        break;
                    
                    default:
                        return true;
                        break;
                }
            } catch (NotFoundException) {
            }
            
            
        }
        
        /**
         * pageHeading
         *
         * @return string
         */
        public static function pageHeading(): string
        {
            if (File::is_File(UPLOADS . 'images/' . self::$pagedata->{'slug' . Language::$lang} . '.jpg')) {
                $bg = ' style="background-image: url(' . UPLOADURL . 'images/' . self::$pagedata->{'slug' . Language::$lang} . '.jpg);"';
            } elseif (File::is_File(UPLOADS . 'images/default-heading.jpg')) {
                $bg = ' style="background-image: url(' . UPLOADURL . 'images/default-heading.jpg);"';
            } else {
                $bg = '';
            }
            
            return $bg;
        }
        
        /**
         * pageBg
         *
         * @return string|null
         */
        public static function pageBg(): ?string
        {
            if (self::$pagedata->{'custom_bg' . Language::$lang}) {
                return ' style="background-image: url(' . UPLOADURL . self::$pagedata->{'custom_bg' . Language::$lang} . '); background-repeat: no-repeat; background-position: top center; background-size: cover;"';
            } else {
                return null;
            }
        }
        
        /**
         * getPageList
         *
         * @return array|false|int|mixed
         */
        public static function getPageList(): mixed
        {
            $lg = Language::$lang;
            $where = (Container::instance()->get('auth')->usertype <> 'owner') ? 'WHERE is_admin = 1' : null;
            
            $sql = "SELECT id, title$lg FROM `" . self::pTable . "` $where ORDER BY title$lg";
            $row = Database::Go()->rawQuery($sql)->run();
            
            return ($row) ?: 0;
        }
        
        /**
         * getContentType
         *
         * @return array
         */
        public static function getContentType(): array
        {
            $modList = Module::getModuleList();
            if ($modList) {
                $array = array(
                    'page' => Language::$word->CON_PAGE,
                    'module' => Language::$word->MODULE,
                    'web' => Language::$word->EXT_LINK
                );
            } else {
                $array = array(
                    'page' => Language::$word->CON_PAGE,
                    'web' => Language::$word->EXT_LINK
                );
            }
            
            return $array;
        }
        
        /**
         * pageType
         *
         * @param string $type
         * @return string
         */
        public static function pageType(string $type): string
        {
            return match ($type) {
                'home' => '<i class="icon primary house disabled"></i>',
                'login' => '<i class="icon primary lock disabled"></i>',
                'activate' => '<i class="icon primary key disabled"></i>',
                'register' => '<i class="icon primary person disabled"></i>',
                'account' => '<i class="icon primary grid fill disabled"></i>',
                'search' => '<i class="icon primary search disabled"></i>',
                'sitemap' => '<i class="icon primary diagram disabled"></i>',
                'profile' => '<i class="icon primary person lines disabled"></i>',
                'policy' => '<i class="icon primary shield disabled"></i>',
                default => '<i class="icon file disabled"></i>',
            };
        }
        
        /**
         * writeSiteMap
         *
         * @return void
         * @throws NotFoundException
         */
        public static function writeSiteMap(): void
        {
            $filename = BASEPATH . 'sitemap.xml';
            $file = SITEURL . 'sitemap.xml';
            if (is_writable($filename)) {
                File::writeToFile($filename, self::makeSiteMap());
                Message::msgReply($file, 'success', Message::formatSuccessMessage($file, Language::$word->UTL_MAP_OK));
            } else {
                Message::msgReply($file, 'error', Message::formatErrorMessage($file, Language::$word->UTL_MAP_ERROR));
            }
        }
        
        /**
         * makeSiteMap
         *
         * @return string
         */
        public static function makeSiteMap(): string
        {
            $html = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
            $html .= "<urlset xmlns=\"https://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:xsi=\"https://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"https://www.sitemaps.org/schemas/sitemap/0.9 https://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\r\n";
            $html .= "<url>\r\n";
            $html .= '<loc>' . SITEURL . "</loc>\r\n";
            $html .= '<lastmod>' . date('Y-m-d') . "</lastmod>\r\n";
            $html .= "</url>\r\n";
            
            $core = Container::instance()->get('core');
            
            //pages
            $pages = 'SELECT slug' . Language::$lang . ' as slug FROM `' . Content::pTable . '` WHERE active = ? AND page_type = ? ORDER BY title' . Language::$lang;
            $query = Database::Go()->rawQuery($pages, array(1, 'normal'));
            
            foreach ($query->run() as $row) {
                $html .= "<url>\r\n";
                $html .= '<loc>' . Url::url($core->pageslug, $row->slug) . "</loc>\r\n";
                $html .= '<lastmod>' . date('Y-m-d') . "</lastmod>\r\n";
                $html .= "<changefreq>weekly</changefreq>\r\n";
                $html .= "</url>\r\n";
            }
            unset($row, $query);
            
            //blog
            if (Database::Go()->exist('mod_blog')->run()) {
                $blog = 'SELECT slug' . Language::$lang . ' as slug FROM `mod_blog` WHERE active = ? ORDER BY created DESC';
                $query = Database::Go()->rawQuery($blog, array(1));
                
                foreach ($query->run() as $row) {
                    $html .= "<url>\r\n";
                    $html .= '<loc>' . Url::url($core->modname['blog'], $row->slug) . "</loc>\r\n";
                    $html .= '<lastmod>' . date('Y-m-d') . "</lastmod>\r\n";
                    $html .= "<changefreq>weekly</changefreq>\r\n";
                    $html .= "</url>\r\n";
                }
                unset($row, $query);
            }
            
            //digishop
            if (Database::Go()->exist('mod_digishop')->run()) {
                $digishop = 'SELECT slug' . Language::$lang . ' as slug FROM `mod_digishop` WHERE active = ? ORDER BY created DESC';
                $query = Database::Go()->rawQuery($digishop, array(1));
                
                foreach ($query->run() as $row) {
                    $html .= "<url>\r\n";
                    $html .= '<loc>' . Url::url($core->modname['digishop'], $row->slug) . "</loc>\r\n";
                    $html .= '<lastmod>' . date('Y-m-d') . "</lastmod>\r\n";
                    $html .= "<changefreq>weekly</changefreq>\r\n";
                    $html .= "</url>\r\n";
                }
                unset($row, $query);
            }
            
            //shop
            if (Database::Go()->exist('mod_shop')->run()) {
                $digishop = 'SELECT slug' . Language::$lang . ' as slug FROM `mod_shop` WHERE active = ? ORDER BY created DESC';
                $query = Database::Go()->rawQuery($digishop, array(1));
                
                foreach ($query->run() as $row) {
                    $html .= "<url>\r\n";
                    $html .= '<loc>' . Url::url($core->modname['shop'], $row->slug) . "</loc>\r\n";
                    $html .= '<lastmod>' . date('Y-m-d') . "</lastmod>\r\n";
                    $html .= "<changefreq>weekly</changefreq>\r\n";
                    $html .= "</url>\r\n";
                }
                unset($row, $query);
            }
            
            //portfolio
            if (Database::Go()->exist('mod_portfolio')->run()) {
                $portfolio = 'SELECT slug' . Language::$lang . ' as slug FROM `mod_portfolio` ORDER BY created DESC';
                $query = Database::Go()->rawQuery($portfolio);
                
                foreach ($query->run() as $row) {
                    $html .= "<url>\r\n";
                    $html .= '<loc>' . Url::url($core->modname['portfolio'], $row->slug) . "</loc>\r\n";
                    $html .= '<lastmod>' . date('Y-m-d') . "</lastmod>\r\n";
                    $html .= "<changefreq>weekly</changefreq>\r\n";
                    $html .= "</url>\r\n";
                }
                unset($row, $query);
            }
            
            $html .= '</urlset>';
            
            return $html;
        }
        
        /**
         * verifyCustomFields
         *
         * @param string $section
         * @return void
         */
        public static function verifyCustomFields(string $section): void
        {
            if ($data = Database::Go()->select(self::cfTable)->where('section', $section, '=')->where('active', 1, '=')->where('required', 1, '=')->run()) {
                foreach ($data as $row) {
                    Validator::checkPost('custom_' . $row->name, Language::$word->FIELD_R0 . ' "' . $row->{'title' . Language::$lang} . '" ' . Language::$word->FIELD_R100);
                }
            }
        }
        
        /**
         * renderCustomFieldsFront
         *
         * @param int $id
         * @param string $section
         * @param $type
         * @return string
         * @throws FileNotFoundException
         */
        public static function renderCustomFieldsFront(int $id, string $section, $type = null): string
        {
            $html = '';
            
            if ($id) {
                $where = match ($section) {
                    'digishop' => 'WHERE cd.digishop_id = ?',
                    'shop' => 'WHERE cd.shop_id = ?',
                    'portfolio' => 'WHERE cd.portfolio_id = ?',
                    'profile' => 'WHERE cd.user_id = ? ',
                    default => null,
                };
                
                $sql = '
                SELECT cf.*, cd.field_value
                  FROM `' . self::cfTable . '` AS cf
                  LEFT JOIN `' . self::cfdTable . "` AS cd ON cd.field_id = cf.id
                  $where
                  AND cf.section = ?
                  AND cf.active = ?
                  ORDER BY cf.sorting
                ";
                $result = Database::Go()->rawQuery($sql, array($id, $section, 1))->run();
            } else {
                $result = Database::Go()->select(self::cfTable)->where('section', $section, '=')->where('active', 1, '=')->orderBy('sorting', 'ASC')->run();
            }
            
            $tpl = new View();
            $tpl->data = $result;
            $tpl->id = $id;
            $tpl->section = $section;
            $tpl->type = $type;
            
            $html .= $tpl->snippet('customFields', THEMEBASE . 'snippets/');
            
            return $html;
            
        }
        
        /**
         * renderCustomFields
         *
         * @param int $id
         * @param string $section
         * @return string
         */
        public static function renderCustomFields(int $id, string $section): string
        {
            if ($id) {
                $where = null;
                switch ($section) {
                    case 'digishop':
                        $where = 'WHERE cd.digishop_id = ?';
                        break;
                    
                    case 'shop':
                        $where = 'WHERE cd.shop_id = ?';
                        break;
                    
                    case 'portfolio':
                        $where = 'WHERE cd.portfolio_id = ?';
                        break;
                    
                    case 'profile':
                        $where = 'WHERE cd.user_id = ? ';
                        break;
                    
                }
                
                $sql = '
			    SELECT cf.*,cd.field_value
			      FROM `' . self::cfTable . '` AS cf
			      LEFT JOIN `' . self::cfdTable . "` AS cd ON cd.field_id = cf.id
			      $where AND cf.section = ?
			      ORDER BY cf.sorting;
			    ";
                $data = Database::Go()->rawQuery($sql, array($id, $section))->run();
            } else {
                $data = Database::Go()->select(self::cfTable)->where('section', $section, '=')->orderBy('sorting', 'ASC')->run();
            }
            
            $html = '';
            if ($data) {
                foreach ($data as $row) {
                    $tooltip = $row->{'tooltip' . Language::$lang} ? ' <span data-tooltip="' . $row->{'tooltip' . Language::$lang} . '"><i class="icon question circle"></i></span>' : '';
                    $required = $row->required ? ' <i class="icon asterisk"></i>' : '';
                    $html .= '<div class="wojo fields align-middle">';
                    $html .= '<div class="field four wide labeled">';
                    $html .= '<label>' . $row->{'title' . Language::$lang} . $required . $tooltip . '</label>';
                    $html .= '</div>';
                    $html .= '<div class="six wide field">';
                    $html .= '<input name="custom_' . $row->name . '" type="text" placeholder="' . $row->{'title' . Language::$lang} . '" value="' . ($id ? $row->field_value : '') . '">';
                    $html .= '</div>';
                    $html .= '</div>';
                }
            }
            return $html;
        }
    }