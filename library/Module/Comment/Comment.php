<?php
    /**
     * Comment
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Comment.php, v1.00 5/20/2023 8:08 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Module\Comment;
    
    use Wojo\Container\Container;
    use Wojo\Core\Router;
    use Wojo\Core\User;
    use Wojo\Database\Database;
    use Wojo\Database\Paginator;
    use Wojo\Date\Date;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Comment
    {
        const mTable = 'mod_comments';
        
        public int $auto_approve;
        public bool $rating;
        public int $timesince;
        public string $blacklist_words;
        public int $char_limit;
        public string $dateformat;
        public bool $notify_new;
        public int $perpage;
        public bool $public_access;
        public bool $show_captcha;
        public string $sorting;
        public bool $username_req;
        
        
        /**
         * render
         *
         * @param string $section
         * @param int $id
         * @return string
         */
        public function render(string $section, int $id): string
        {
            return $this->commentList($this->commentTree($section, $id));
        }
        
        /**
         * commentTree
         *
         * @param string $section
         * @param int $id
         * @return array
         */
        public function commentTree(string $section, int $id): array
        {
            $settings = self::settings();
            $counter = 'SELECT COUNT(*) as items FROM `' . self::mTable . '` WHERE section = ? AND comment_id = ? AND parent_id = ? AND active = ? LIMIT 1';
            $row = Database::Go()->rawQuery($counter, array($section, 0, $id, 1))->first()->run();
            
            $pager = Paginator::instance();
            $pager->items_total = $row->items;
            $pager->default_ipp = $settings->perpage;
            $pager->path = Url::url(Router::$path, '?');
            $pager->paginate();
            
            if (isset($_GET['order']) and count(explode('|', $_GET['order'])) == 2) {
                list($sort, $order) = explode('|', $_GET['order']);
                $sort = Validator::sanitize($sort, 'default', 16);
                $order = Validator::sanitize($order, 'default', 4);
                if (in_array($sort, array('vote_up', 'vote_down', 'created'))) {
                    $ord = ($order == 'DESC') ? ' DESC' : ' ASC';
                    $sorting = $sort . $ord;
                } else {
                    $sorting = ' created ' . $settings->sorting;
                }
            } else {
                $sorting = ' created ' . $settings->sorting;
            }
            
            $sql = "
            SELECT c.id, c.user_id, c.comment_id, c.parent_id, c.section, c.vote_down, c.vote_up, c.body, c.created, c.username as uname,
                   u.username, CONCAT(u.fname, ' ', u.lname) as name, u.avatar
              FROM `" . self::mTable . '` as c
              INNER JOIN (SELECT id FROM `' . self::mTable . "`
                WHERE section = ?
                AND parent_id = ?
                AND comment_id = ?
                AND active = ?
                ORDER BY $sorting " . $pager->limit . ') as ch ON ch.id IN (c.id, c.comment_id)
              LEFT JOIN `' . User::mTable . "` as u ON u.id = c.user_id
              WHERE section = ?
              AND parent_id = ?
              AND c.active = ?
              ORDER BY $sorting
            ";
            
            $data = Database::Go()->rawQuery($sql, array($section, $id, 0, 1, $section, $id, 1))->run();
            
            $comments = array();
            $result = array();
            
            foreach ($data as $row) {
                $comments['id'] = $row->id;
                $comments['user_id'] = $row->user_id;
                $comments['comment_id'] = $row->comment_id;
                $comments['parent_id'] = $row->parent_id;
                $comments['vote_up'] = $row->vote_up;
                $comments['vote_down'] = $row->vote_down;
                $comments['section'] = $row->section;
                $comments['body'] = $row->body;
                $comments['created'] = $row->created;
                $comments['name'] = $row->name;
                $comments['username'] = $row->username;
                $comments['uname'] = $row->uname;
                $comments['avatar'] = $row->avatar;
                $result[$row->id] = $comments;
            }
            return $result;
        }
        
        /**
         * commentList
         *
         * @param array $array
         * @param int $comment_id
         * @param string $class
         * @return string
         */
        public function commentList(array $array, int $comment_id = 0, string $class = 'threaded'): string
        {
            $auth = Container::instance()->get('auth');
            $core = Container::instance()->get('core');
            $settings = self::settings();
            
            $submenu = false;
            $class = ($comment_id == 0) ? "wojo comments $class" : 'comments';
            $delete = ($auth->is_Admin()) ? '<a class="delete"><i class="icon negative x alt"></i></a>' : null;
            $html = '';
            
            foreach ($array as $key => $row) {
                if ($row['comment_id'] == $comment_id) {
                    if ($submenu === false) {
                        $submenu = true;
                        $html .= "<div class=\"$class\">\n";
                    }
                    if ($row['uname']) {
                        $user = '<span class="author">' . $row['uname'] . '</span>';
                        $avatar = '<div class="avatar"><img src="' . UPLOADURL . 'avatars/default.svg" alt=""></div>';
                    } else {
                        $profile = Url::url($core->system_slugs->profile[0]->{'slug' . Language::$lang}, $row['username']);
                        $user = '<a href="' . $profile . '" class="author">' . $row['name'] . '</a>';
                        $avatar = '<a href="' . $profile . '" class="avatar"><img src="' . UPLOADURL . 'avatars/' . ($row['avatar'] ? : 'default.svg') . '" alt=""></a>';
                    }
                    
                    $html .= '<div class="comment" data-id="' . $row['id'] . '" id="comment_' . $row['id'] . '">';
                    $html .= $avatar;
                    $html .= '<div class="content">';
                    $html .= $user;
                    $html .= '<div class="metadata">';
                    $html .= '<span class="date">' . ($settings->timesince) ? Date::timesince($row['created']) : Date::doDate($settings->dateformat, $row['created']) . '</span>';
                    $html .= $delete;
                    $html .= '</div>';
                    $html .= '<div class="text">' . $row['body'] . '</div>';
                    $html .= '<div class="wojo horizontal divided list actions">';
                    if ($settings->rating) {
                        $html .= '<a data-up="' . $row['vote_up'] . '" data-id="' . $row['id'] . '"
					  class="item up"><span class="text-color-positive">' . $row['vote_up'] . '</span> <i class="icon caret up"></i></a>';
                        $html .= '<a data-down="' . $row['vote_down'] . '" data-id="' . $row['id'] . '"
					  class="item down"><span class="text-color-negative">' . $row['vote_down'] . '</span> <i class="icon caret down"></i></a>';
                    }
                    if ($comment_id == 0) {
                        $html .= '<a data-id="' . $row['id'] . '" class="item replay">' . Language::$word->_MOD_CM_REPLAY . '</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= $this->commentList($array, $key);
                    $html .= "</div>\n";
                }
            }
            unset($row);
            
            if ($submenu === true) {
                $html .= "</div>\n";
            }
            
            return $html;
        }
        
        /**
         * singleComment
         *
         * @param int $id
         * @return mixed
         */
        public static function singleComment(int $id): mixed
        {
            $sql = "
            SELECT c.id, c.user_id, c.comment_id, c.parent_id, c.section, c.vote_down, c.vote_up, c.body, c.created,
                   c.username as uname, u.username, CONCAT(u.fname, ' ', u.lname) as name, u.avatar
              FROM `" . self::mTable . '` as c
              LEFT JOIN `' . User::mTable . '` as u ON u.id = c.user_id
              WHERE c.id = ?
            ';
            
            return Database::Go()->rawQuery($sql, array($id))->first()->run();
        }
        
        /**
         * settings
         *
         * @return mixed
         */
        public static function settings(): mixed
        {
            $row = json_decode(File::loadFile(AMODPATH . 'comments/config.json'));
            return $row->comments;
        }
    }