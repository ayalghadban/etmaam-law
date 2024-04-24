<?php
    /**
     * BlogController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: BlogController.php, v1.00 5/19/2023 1:46 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin\Module\Blog;
    
    use Exception;
    use Wojo\Core\Controller;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Core\Membership;
    use Wojo\Core\Meta;
    use Wojo\Core\Module;
    use Wojo\Core\Router;
    use Wojo\Core\Session;
    use Wojo\Database\Paginator;
    use Wojo\Debug\Debug;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Exception\NotFoundException;
    use Wojo\File\File;
    use Wojo\Image\Image;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
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
            $find = isset($_POST['find']) ? Validator::sanitize($_POST['find'], 'default', 30) : null;
            $lg = Language::$lang;
            
            if (isset($_GET['letter']) and $find) {
                $letter = Validator::sanitize($_GET['letter'], 'string', 2);
                $counter = $this->db->count(Blog::mTable, 'WHERE `title' . $lg . "` LIKE '%" . trim($find) . "%' AND `title$lg` REGEXP '^$letter'")->run();
                $where = 'WHERE d.title' . $lg . " LIKE '%" . trim($find) . "%' AND d.title" . $lg . " REGEXP '^" . $letter . "'";
                
            } elseif (isset($_POST['find'])) {
                $counter = $this->db->count(Blog::mTable, 'WHERE `title' . $lg . "` LIKE '%" . trim($find) . "%'")->run();
                $where = 'WHERE d.title' . $lg . " LIKE '%" . trim($find) . "%'";
                
            } elseif (isset($_GET['letter'])) {
                $letter = Validator::sanitize($_GET['letter'], 'string', 2);
                $where = 'WHERE d.title' . $lg . " REGEXP '^$letter'";
                $counter = $this->db->count(Blog::mTable, "WHERE `title$lg` REGEXP '^$letter'")->run();
            } else {
                $counter = $this->db->count(Blog::mTable)->run();
                $where = null;
            }
            
            if (isset($_GET['order']) and count(explode('|', $_GET['order'])) == 2) {
                list($sort, $order) = explode('|', $_GET['order']);
                $sort = Validator::sanitize($sort, 'default', 16);
                $order = Validator::sanitize($order, 'default', 4);
                if (in_array($sort, array(
                    'title',
                    'hits',
                    'active',
                    'memberships',
                    'category_id'
                ))) {
                    $ord = ($order == 'DESC') ? ' DESC' : ' ASC';
                    $sorting = $sort . $ord;
                } else {
                    $sorting = ' created DESC';
                }
            } else {
                $sorting = ' created DESC';
            }
            
            $pager = Paginator::instance();
            $pager->items_total = $counter;
            $pager->default_ipp = $this->core->perpage;
            $pager->path = Url::url(Router::$path, '?');
            $pager->paginate();
            
            $sql = "
            SELECT d.id, d.category_id, d.hits, d.thumb, d.like_up, d.like_down, d.active, d.created, d.title$lg as title, c.name$lg as name, GROUP_CONCAT(m.title$lg SEPARATOR ', ') as memberships,
                   (SELECT COUNT(parent_id) FROM `" . Module::mcTable . '` WHERE `' . Module::mcTable . "`.parent_id = d.id AND section = 'blog') as comments
              FROM `" . Blog::mTable . '` as d
              LEFT JOIN `' . Blog::cTable . '` as c ON c.id = d.category_id
              LEFT JOIN `' . Membership::mTable . "` as m ON FIND_IN_SET (m.id, d.membership_id)
              $where
              GROUP BY d.id
              ORDER BY $sorting " . $pager->limit;
            
            $this->view->data = $this->db->rawQuery($sql)->run();
            $this->view->pager = $pager;
            
            $this->view->crumbs = ['admin', 'modules', Language::$word->_MOD_AM_TITLE];
            $this->view->caption = Language::$word->_MOD_AM_TITLE;
            $this->view->title = Language::$word->_MOD_AM_TITLE;
            $this->view->subtitle = null;
            $this->view->render('index', 'view/admin/modules/blog/view/', true, 'view/admin/');
        }
        
        /**
         * new
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function new(): void
        {
            Session::set('blogtoken', Utility::randNumbers(4));
            $this->view->langlist = $this->core->langlist;
            $this->view->membership_list = Membership::getMembershipList();
            $this->view->tree = Blog::categoryTree();
            $this->view->droplist = Blog::getCatCheckList($this->view->tree, 0, 0, '&#166;&nbsp;&nbsp;&nbsp;&nbsp;');
            
            $this->view->crumbs = ['admin', 'modules', 'blog', Language::$word->_MOD_AM_NEW];
            $this->view->caption = Language::$word->_MOD_AM_NEW;
            $this->view->title = Language::$word->_MOD_AM_NEW;
            $this->view->subtitle = [];
            $this->view->render('index', 'view/admin/modules/blog/view/', true, 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'modules', 'blog', 'edit'];
            $this->view->title = Language::$word->_MOD_AM_TITLE2;
            $this->view->caption = Language::$word->_MOD_AM_TITLE2;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Blog::mTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->langlist = $this->core->langlist;
                $this->view->membership_list = Membership::getMembershipList();
                $this->view->tree = Blog::categoryTree();
                $this->view->droplist = Blog::getCatCheckList($this->view->tree, 0, 0, '&#166;&nbsp;&nbsp;&nbsp;&nbsp;', $row->categories);
                $this->view->images = $this->db->select(Blog::gTable, array('id', 'name'))->where('parent_id', $row->id, '=')->orderBy('sorting', 'ASC')->run();
                
                $this->view->render('index', 'view/admin/modules/blog/view/', true, 'view/admin/');
            }
        }
        
        /**
         * settings
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function settings(): void
        {
            $row = $this->_config();
            $this->view->data = $row->blog;
            
            $this->view->crumbs = ['admin', 'modules', 'blog', Language::$word->_MOD_AM_SUB18];
            $this->view->caption = Language::$word->_MOD_AM_SUB18;
            $this->view->title = Language::$word->_MOD_AM_SUB18;
            $this->view->subtitle = [];
            $this->view->render('index', 'view/admin/modules/blog/view/', true, 'view/admin/');
        }
        
        /**
         * categoryNew
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function categoryNew(): void
        {
            $this->view->langlist = $this->core->langlist;
            $this->view->tree = Blog::categoryTree();
            $this->view->droplist = Blog::getCategoryDropList($this->view->tree, 0, 0, '&#166;&nbsp;&nbsp;&nbsp;&nbsp;');
            $this->view->sortlist = Blog::getSortCategoryList($this->view->tree);
            
            $this->view->crumbs = ['admin', 'modules', 'blog', 'categories'];
            $this->view->caption = Language::$word->_MOD_AM_SUB12;
            $this->view->title = Language::$word->_MOD_AM_SUB12;
            $this->view->subtitle = [];
            $this->view->render('index', 'view/admin/modules/blog/view/', true, 'view/admin/');
        }
        
        /**
         * categoryEdit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function categoryEdit(): void
        {
            $this->view->crumbs = ['admin', 'modules', 'blog', 'category'];
            $this->view->title = Language::$word->_MOD_AM_SUB12;
            $this->view->caption = Language::$word->_MOD_AM_SUB12;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Blog::cTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->langlist = $this->core->langlist;
                $this->view->tree = Blog::categoryTree();
                $this->view->droplist = Blog::getCategoryDropList($this->view->tree, 0, 0, '&#166;&nbsp;&nbsp;&nbsp;&nbsp;', $row->parent_id);
                $this->view->sortlist = Blog::getSortCategoryList($this->view->tree);
                
                $this->view->render('index', 'view/admin/modules/blog/view/', true, 'view/admin/');
            }
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
                        case 'add':
                        case 'update':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->process();
                            break;
                        
                        case 'configuration':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->configuration();
                            break;
                        
                        case 'category':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->processCategory();
                            break;
                        
                        case 'images':
                            IS_DEMO ? Message::msgReply(true, 'success', Language::$word->PROCESS_ERR_DEMO) : $this->images();
                            break;
                        
                        case 'sort':
                            IS_DEMO ? print '0' : $this->sort(Validator::post('type'));
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
            foreach ($this->core->langlist as $lang) {
                $validate
                    ->set('title_' . $lang->abbr, Language::$word->NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>')->required()->string()->min_len(3)->max_len(80)
                    ->set('slug_' . $lang->abbr, Language::$word->ITEMSLUG)->string()
                    ->set('keywords_' . $lang->abbr, Language::$word->METAKEYS)->string(true, true)
                    ->set('description_' . $lang->abbr, Language::$word->METADESC)->string(true, true)
                    ->set('body_' . $lang->abbr, Language::$word->METADESC)->text('advanced')
                    ->set('tags_' . $lang->abbr, Language::$word->_MOD_AM_SUB2)->string();
            }
            
            $validate
                ->set('layout', Language::$word->_MOD_AM_SUB3)->required()->numeric()
                ->set('show_author', Language::$word->_MOD_AM_SUB7)->required()->numeric()
                ->set('show_ratings', Language::$word->_MOD_AM_SUB8)->required()->numeric()
                ->set('show_comments', Language::$word->_MOD_AM_SUB9)->required()->numeric()
                ->set('show_created', Language::$word->_MOD_AM_SUB6)->required()->numeric()
                ->set('show_sharing', Language::$word->_MOD_AM_SUB11)->required()->numeric()
                ->set('show_like', Language::$word->_MOD_AM_SUB10)->required()->numeric()
                ->set('active', Language::$word->PUBLISHED)->required()->numeric()
                ->set('time_end', Language::$word->PUBLISHED)->string()
                ->set('categories', Language::$word->CATEGORIES)->one();
            
            (Filter::$id) ? $this->_update($validate) : $this->_add($validate);
        }
        
        /**
         * _add
         *
         * @param Validator $validate
         * @return void
         */
        private function _add(Validator $validate): void
        {
            $thumb = File::upload('thumb', Blog::MAXIMG, 'png,jpg,jpeg');
            $file = File::upload('file', Blog::MAXFILE, Blog::FILES);
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data_m = array();
                foreach ($this->core->langlist as $i => $lang) {
                    $slug[$i] = (strlen($safe->{'slug_' . $lang->abbr}) === 0)
                        ? Url::doSeo($safe->{'title_' . $lang->abbr})
                        : Url::doSeo($safe->{'slug_' . $lang->abbr});
                    $data_m['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
                    $data_m['slug_' . $lang->abbr] = $slug[$i];
                    $data_m['keywords_' . $lang->abbr] = $safe->{'keywords_' . $lang->abbr};
                    $data_m['description_' . $lang->abbr] = $safe->{'description_' . $lang->abbr};
                    $data_m['tags_' . $lang->abbr] = Url::doSeo($safe->{'tags_' . $lang->abbr});
                    $data_m['tags_' . $lang->abbr] = str_replace('-', ',', $data_m['tags_' . $lang->abbr]);
                    $data_m['body_' . $lang->abbr] = Url::in_url($safe->{'body_' . $lang->abbr});
                    
                    if (strlen($safe->{'keywords_' . $lang->abbr}) === 0 or strlen($safe->{'description_' . $lang->abbr}) === 0) {
                        Meta::instance($safe->{'body_' . $lang->abbr});
                        if (strlen($safe->{'keywords_' . $lang->abbr}) === 0) {
                            $data_m['keywords_' . $lang->abbr] = Meta::getKeywords();
                        }
                        if (strlen($safe->{'description_' . $lang->abbr}) === 0) {
                            $data_m['description_' . $lang->abbr] = Meta::metaText($safe->{'body_' . $lang->abbr});
                        }
                    }
                }
                $data_x = array(
                    'category_id' => intval($_POST['categories'][0]),
                    'categories' => Utility::implodeFields($_POST['categories']),
                    'membership_id' => array_key_exists('membership_id', $_POST) ? Utility::implodeFields($_POST['membership_id']) : 0,
                    'show_author' => $safe->show_author,
                    'show_ratings' => $safe->show_ratings,
                    'show_comments' => $safe->show_comments,
                    'show_sharing' => $safe->show_sharing,
                    'show_created' => $safe->show_created,
                    'show_like' => $safe->show_like,
                    'layout' => $safe->layout,
                    'user_id' => $this->auth->uid,
                    'active' => $safe->active,
                );
                
                $temp_id = Session::get('blogtoken');
                File::makeDirectory(FMODPATH . Blog::BLOGDATA . $temp_id . '/thumbs');
                
                //process thumb
                if (array_key_exists('thumb', $_FILES)) {
                    $thumb_path = FMODPATH . Blog::BLOGDATA . $temp_id . '/';
                    $thumb_result = File::process($thumb, $thumb_path, false);
                    $config = $this->_config();
                    try {
                        $img = new Image($thumb_path . $thumb_result['fname']);
                        $img->thumbnail($config->blog->thumb_w, $config->blog->thumb_h)->save($thumb_path . 'thumbs/' . $thumb_result['fname']);
                    } catch (Exception $e) {
                        Debug::addMessage('errors', '<i>Error</i>', $e->getMessage(), 'session');
                    }
                    $data_x['thumb'] = $thumb_result['fname'];
                }
                //process file
                if (array_key_exists('file', $_FILES)) {
                    $file_result = File::process($file, FMODPATH . Blog::BLOGFILES, false);
                    $data_x['file'] = $file_result['fname'];
                }
                
                $last_id = $this->db->insert(Blog::mTable, array_merge($data_m, $data_x))->run();
                
                //process related categories
                $data_array = array();
                foreach ($_POST['categories'] as $item) {
                    $data_array[] = array(
                        'item_id' => $last_id,
                        'category_id' => $item
                    );
                }
                $this->db->batch(Blog::rTable, $data_array)->run();
                
                //process gallery
                if ($rows = $this->db->select(Blog::gTable, array('id', 'parent_id'))->where('parent_id', Session::get('blogtoken'), '=')->run()) {
                    $query = 'UPDATE `' . Blog::gTable . '` SET `parent_id` = CASE ';
                    $list = '';
                    foreach ($rows as $item) {
                        $query .= ' WHEN id = ' . $item->id . ' THEN ' . $last_id;
                        $list .= $item->id . ',';
                    }
                    $list = substr($list, 0, -1);
                    $query .= ' END WHERE id IN (' . $list . ')';
                    $this->db->rawQuery($query)->run();
                    
                    $images = $this->db->select(Blog::gTable, array('name'))->where('parent_id', $last_id, '=')->orderBy('sorting', 'ASC')->run('json');
                    $this->db->update(Blog::mTable, array('images' => $images))->where('id', $last_id, '=')->run();
                }
                
                //rename temp folder
                File::renameDirectory(FMODPATH . Blog::BLOGDATA . $temp_id, FMODPATH . Blog::BLOGDATA . $last_id);
                
                if ($last_id) {
                    $message = Message::formatSuccessMessage($data_m['title' . Language::$lang], Language::$word->_MOD_AM_ITM_ADDED_OK);
                    $json['type'] = 'success';
                    $json['title'] = Language::$word->SUCCESS;
                    $json['message'] = $message;
                    $json['redirect'] = Url::url('admin/modules', 'blog/');
                    Logger::writeLog($message);
                } else {
                    $json['type'] = 'success';
                    $json['title'] = Language::$word->SUCCESS;
                    $json['message'] = Language::$word->NOPROCESS;
                }
                print json_encode($json);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * _update
         *
         * @param Validator $validate
         * @return void
         */
        private function _update(Validator $validate): void
        {
            
            $thumb = File::upload('thumb', Blog::MAXIMG, 'png,jpg,jpeg');
            $file = File::upload('file', Blog::MAXFILE, Blog::FILES);
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data_m = array();
                foreach ($this->core->langlist as $i => $lang) {
                    $slug[$i] = (strlen($safe->{'slug_' . $lang->abbr}) === 0)
                        ? Url::doSeo($safe->{'title_' . $lang->abbr})
                        : Url::doSeo($safe->{'slug_' . $lang->abbr});
                    $data_m['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
                    $data_m['slug_' . $lang->abbr] = $slug[$i];
                    $data_m['keywords_' . $lang->abbr] = $safe->{'keywords_' . $lang->abbr};
                    $data_m['description_' . $lang->abbr] = $safe->{'description_' . $lang->abbr};
                    $tags[$i] = (strlen($safe->{'tags_' . $lang->abbr} === 0)) ? null : str_replace(array(',', ' '), array('-', ''), $safe->{'tags_' . $lang->abbr});
                    $tags[$i] = Url::doSeo($tags[$i]);
                    $data_m['tags_' . $lang->abbr] = str_replace('-', ',', $tags[$i]);
                    $data_m['body_' . $lang->abbr] = Url::in_url($safe->{'body_' . $lang->abbr});
                    
                    if (strlen($safe->{'keywords_' . $lang->abbr}) === 0 or strlen($safe->{'description_' . $lang->abbr}) === 0) {
                        Meta::instance($safe->{'body_' . $lang->abbr});
                        if (strlen($safe->{'keywords_' . $lang->abbr}) === 0) {
                            $data_m['keywords_' . $lang->abbr] = Meta::getKeywords();
                        }
                        if (strlen($safe->{'description_' . $lang->abbr}) === 0) {
                            $data_m['description_' . $lang->abbr] = Meta::metaText($safe->{'body_' . $lang->abbr});
                        }
                    }
                }
                $data_x = array(
                    'category_id' => intval($_POST['categories'][0]),
                    'categories' => Utility::implodeFields($_POST['categories']),
                    'membership_id' => array_key_exists('membership_id', $_POST) ? Utility::implodeFields($_POST['membership_id']) : 0,
                    'show_author' => $safe->show_author,
                    'show_ratings' => $safe->show_ratings,
                    'show_comments' => $safe->show_comments,
                    'show_sharing' => $safe->show_sharing,
                    'show_created' => $safe->show_created,
                    'show_like' => $safe->show_like,
                    'layout' => $safe->layout,
                    'user_id' => $this->auth->uid,
                    'active' => $safe->active,
                    'modified' => $this->db::toDate(),
                    'images' => $this->db->select(Blog::gTable, array('name'))->where('parent_id', Filter::$id, '=')->orderBy('sorting', 'ASC')->run('json'),
                );
                
                //process thumb
                $row = $this->db->select(Blog::mTable, array('thumb', 'file'))->where('id', Filter::$id, '=')->first()->run();
                if (array_key_exists('thumb', $_FILES)) {
                    $thumb_path = FMODPATH . Blog::BLOGDATA . Filter::$id . '/';
                    $thumb_result = File::process($thumb, $thumb_path, false);
                    $config = $this->_config();
                    File::deleteFile($thumb_path . $row->thumb);
                    File::deleteFile($thumb_path . 'thumbs/' . $row->thumb);
                    try {
                        $img = new Image($thumb_path . $thumb_result['fname']);
                        $img->thumbnail($config->blog->thumb_w, $config->blog->thumb_h)->save($thumb_path . 'thumbs/' . $thumb_result['fname']);
                    } catch (Exception $e) {
                        Debug::addMessage('errors', '<i>Error</i>', $e->getMessage(), 'session');
                    }
                    $data_x['thumb'] = $thumb_result['fname'];
                }
                
                //process file
                if (array_key_exists('file', $_FILES)) {
                    $file_result = File::process($file, FMODPATH . Blog::BLOGFILES, false);
                    File::deleteFile(FMODPATH . Blog::BLOGFILES . $row->file);
                    $data_x['file'] = $file_result['fname'];
                }
                if (Validator::post('remfile')) {
                    $data_m['file'] = 'NULL';
                }
                
                //process related categories
                $this->db->delete(Blog::rTable)->where('item_id', Filter::$id, '=')->run();
                $data_array = array();
                foreach ($_POST['categories'] as $item) {
                    $data_array[] = array(
                        'item_id' => Filter::$id,
                        'category_id' => $item
                    );
                }
                $this->db->batch(Blog::rTable, $data_array)->run();
                
                $this->db->update(Blog::mTable, array_merge($data_m, $data_x))->where('id', Filter::$id, '=')->run();
                
                $message = Message::formatSuccessMessage($data_m['title' . Language::$lang], Language::$word->_MOD_AM_ITM_UPDATE_OK);
                Message::msgReply($this->db->affected(), 'success', $message);
                Logger::writeLog($message);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * configuration
         *
         * @return void
         */
        private function configuration(): void
        {
            $validate = Validator::run($_POST);
            $validate
                ->set('auto_approve', Language::$word->_MOD_AM_SUB32)->required()->numeric()
                ->set('cdateformat', Language::$word->_MOD_AM_SUB36)->required()->string()
                ->set('char_limit', Language::$word->_MOD_AM_SUB37)->required()->numeric()
                ->set('notify_new', Language::$word->_MOD_AM_SUB31)->required()->numeric()
                ->set('comperpage', Language::$word->_MOD_AM_SUB22)->required()->numeric()
                ->set('public_access', Language::$word->_MOD_AM_SUB29)->required()->numeric()
                ->set('show_captcha', Language::$word->_MOD_AM_SUB25)->required()->numeric()
                ->set('sorting', Language::$word->_MOD_AM_SUB34)->required()->string()
                ->set('comperpage', Language::$word->_MOD_AM_SUB22)->numeric()->string()
                ->set('username_req', Language::$word->_MOD_AM_SUB24)->required()->numeric()
                ->set('blacklist_words', Language::$word->_MOD_AM_SUB39)->string();
            $validate
                ->set('cperpage', Language::$word->_MOD_AM_SUB38)->required()->numeric()
                ->set('email_req', Language::$word->_MOD_AM_SUB30)->required()->numeric()
                ->set('flayout', Language::$word->_MOD_AM_SUB33)->required()->numeric()
                ->set('fperpage', Language::$word->_MOD_AM_SUB19)->required()->numeric()
                ->set('latestperpage', Language::$word->_MOD_AM_SUB20)->required()->numeric()
                ->set('popperpage', Language::$word->_MOD_AM_SUB21)->required()->numeric();
            $validate
                ->set('show_counter', Language::$word->_MOD_AM_SUB23)->required()->numeric()
                ->set('show_username', Language::$word->_MOD_AM_SUB27)->required()->numeric()
                ->set('show_www', Language::$word->_MOD_AM_SUB26)->required()->numeric()
                ->set('upost', Language::$word->_MOD_AM_SUB28)->required()->numeric()
                ->set('thumb_w', Language::$word->THUMB_W)->required()->numeric()->exact_len(3)
                ->set('thumb_h', Language::$word->THUMB_H)->required()->numeric()->exact_len(3);
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data = array(
                    'blog' => array(
                        'auto_approve' => $safe->auto_approve,
                        'cdateformat' => $safe->cdateformat,
                        'char_limit' => $safe->char_limit,
                        'comperpage' => $safe->comperpage,
                        'cperpage' => $safe->cperpage,
                        'email_req' => $safe->email_req,
                        'flayout' => $safe->flayout,
                        'fperpage' => $safe->fperpage,
                        'latestperpage' => $safe->latestperpage,
                        'popperpage' => $safe->popperpage,
                        'notify_new' => $safe->notify_new,
                        'public_access' => $safe->public_access,
                        'show_captcha' => $safe->show_captcha,
                        'show_counter' => $safe->show_counter,
                        'show_username' => $safe->show_username,
                        'show_www' => $safe->show_www,
                        'sorting' => $safe->sorting,
                        'upost' => $safe->upost,
                        'username_req' => $safe->username_req,
                        'blacklist_words' => $safe->blacklist_words,
                        'thumb_w' => $safe->thumb_w,
                        'thumb_h' => $safe->thumb_h,
                    )
                );
                
                Message::msgReply(File::writeToFile(AMODPATH . 'blog/config.json', json_encode($data, JSON_PRETTY_PRINT)), 'success', Language::$word->_MOD_AM_CUPDATED);
                Logger::writeLog(Language::$word->_MOD_DS_CUPDATED);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * processCategory
         *
         * @return void
         */
        public function processCategory(): void
        {
            $validate = Validator::run($_POST);
            foreach ($this->core->langlist as $lang) {
                $validate
                    ->set('name_' . $lang->abbr, Language::$word->NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>')->required()->string()->min_len(3)->max_len(80)
                    ->set('slug_' . $lang->abbr, Language::$word->CATSLUG)->string()
                    ->set('keywords_' . $lang->abbr, Language::$word->METAKEYS)->string(true, true)
                    ->set('description_' . $lang->abbr, Language::$word->METADESC)->string(true, true);
            }
            
            $validate
                ->set('parent_id', Language::$word->_MOD_AM_SUB13)->required()->numeric()
                ->set('active', Language::$word->_MOD_AM_SUB15)->required()->numeric()
                ->set('layout', Language::$word->PAG_MDLCOMMENT)->required()->numeric()
                ->set('perpage', Language::$word->_MOD_AM_SUB17)->required()->numeric()
                ->set('icon', Language::$word->_MOD_AM_SUB16)->string();
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data_m = array();
                foreach ($this->core->langlist as $i => $lang) {
                    $slug[$i] = (strlen($safe->{'slug_' . $lang->abbr}) === 0)
                        ? Url::doSeo($safe->{'name_' . $lang->abbr})
                        : Url::doSeo($safe->{'slug_' . $lang->abbr});
                    $data_m['name_' . $lang->abbr] = $safe->{'name_' . $lang->abbr};
                    $data_m['slug_' . $lang->abbr] = $slug[$i];
                    $data_m['keywords_' . $lang->abbr] = $safe->{'keywords_' . $lang->abbr};
                    $data_m['description_' . $lang->abbr] = $safe->{'description_' . $lang->abbr};
                }
                
                $data_x = array(
                    'parent_id' => $safe->parent_id,
                    'layout' => $safe->layout,
                    'perpage' => $safe->perpage,
                    'icon' => $safe->icon,
                    'active' => $safe->active,
                );
                
                $data = array_merge($data_m, $data_x);
                $last_id = 0;
                
                (Filter::$id) ? $this->db->update(Blog::cTable, $data)->where('id', Filter::$id, '=')->run() : $last_id = $this->db->insert(Blog::cTable, $data)->run();
                if (Filter::$id) {
                    $message = Message::formatSuccessMessage($data_m['name' . Language::$lang], Language::$word->_MOD_AM_CAT_UPDATE_OK);
                    Message::msgReply($this->db->affected(), 'success', $message);
                    Logger::writeLog($message);
                } else {
                    if ($last_id) {
                        $message = Message::formatSuccessMessage($data_m['name' . Language::$lang], Language::$word->_MOD_AM_CAT_ADDED_OK);
                        $json['type'] = 'success';
                        $json['title'] = Language::$word->SUCCESS;
                        $json['message'] = $message;
                        $json['redirect'] = Url::url('admin/modules/blog/categories/');
                        Logger::writeLog($message);
                    } else {
                        $json['type'] = 'alert';
                        $json['title'] = Language::$word->ALERT;
                        $json['message'] = Language::$word->NOPROCESS;
                    }
                    print json_encode($json);
                }
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * images
         *
         * @return void
         */
        private function images(): void
        {
            $num_files = count($_FILES['images']['tmp_name']);
            $file_dir = FMODPATH . Blog::BLOGDATA . Filter::$id;
            $config = $this->_config();
            $json['type'] = 'error';
            File::makeDirectory($file_dir . '/thumbs');
            
            for ($x = 0; $x < $num_files; $x++) {
                $image = $_FILES['images']['name'][$x];
                $newName = 'IMG_' . Utility::randomString(12);
                $ext = substr($image, strrpos($image, '.') + 1);
                $name = $newName . '.' . strtolower($ext);
                $full_name = $file_dir . '/' . $name;
                
                if (!move_uploaded_file($_FILES['images']['tmp_name'][$x], $full_name)) {
                    die(Message::msgSingleError(Language::$word->FU_ERROR13));
                }
                
                $img = new Image($file_dir . '/' . $name);
                
                if (!$img->getSourceWidth()) {
                    $json['title'] = Language::$word->ERROR;
                    $json['message'] = Message::$msgs['name'] = Language::$word->FU_ERROR7; //invalid image
                    print json_encode($json);
                    exit;
                }
                
                try {
                    $img->thumbnail($config->blog->thumb_w, $config->blog->thumb_h)->save($file_dir . '/thumbs/' . $name);
                    $last_id = $this->db->insert(Blog::gTable, array('parent_id' => Filter::$id, 'name' => $name))->run();
                    
                    $json['html'][$x] = '
                    <div class="columns" id="item_' . $last_id . '" data-id="' . $last_id . '">
                      <div class="wojo compact segment center-align">
                        <div class="handle"><i class="icon grip horizontal"></i></div>
                          <img src="' . Blog::hasThumb($name, Filter::$id) . '" alt="" class="wojo rounded image">
                          <a data-set=\'{"option":[{"delete": "delete","id":' . $last_id . ', "type":"image"}], "url":"modules/blog/action/","action":"delete", "parent":"#item_' . $last_id . '"}\' class="wojo mini icon negative simple button data"><i class="icon x alt"></i></a>
                      </div>
                    </div>';
                } catch (Exception $e) {
                    echo 'Error: ' . $e->getMessage();
                }
            }
            $json['type'] = 'success';
            print json_encode($json);
        }
        
        /**
         * sort
         *
         * @param string $type
         * @return void
         */
        private function sort(string $type): void
        {
            $i = 0;
            $list = '';
            
            $query = ($type == 'images') ? 'UPDATE `' . Blog::gTable . '` SET `sorting` = CASE ' : 'UPDATE `' . Blog::cTable . '` SET `sorting` = CASE ';
            
            foreach ($_POST['sorting'] as $item) {
                $i++;
                $query .= ' WHEN id = ' . $item . ' THEN ' . $i . ' ';
                $list .= $item . ',';
            }
            $list = substr($list, 0, -1);
            $query .= 'END WHERE id IN (' . $list . ')';
            $this->db->rawQuery($query)->run();
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
                case 'image':
                    if ($row = $this->db->select(Blog::gTable)->where('id', Filter::$id, '=')->first()->run()) {
                        File::deleteFile(FMODPATH . Blog::BLOGDATA . $row->parent_id . '/' . $row->name);
                        File::deleteFile(FMODPATH . Blog::BLOGDATA . $row->parent_id . '/thumbs/' . $row->name);
                        $this->db->delete(Blog::gTable)->where('id', Filter::$id, '=')->run();
                        $json['type'] = 'success';
                        $json['title'] = Language::$word->SUCCESS;
                    } else {
                        $json['type'] = 'error';
                    }
                    print json_encode($json);
                    break;
                
                case 'category':
                    if ($this->db->delete(Blog::cTable)->where('id', Filter::$id, '=')->run()) {
                        $this->db->delete(Blog::cTable)->where('parent_id', Filter::$id, '=')->run();
                    }
                    
                    $json['type'] = 'success';
                    $json['title'] = Language::$word->SUCCESS;
                    $json['message'] = str_replace('[NAME]', $title, Language::$word->_MOD_AM_CATDEL_OK);
                    print json_encode($json);
                    Logger::writeLog($json['message']);
                    break;
                
                default:
                    if ($this->db->delete(Blog::mTable)->where('id', Filter::$id, '=')->run()) {
                        $this->db->delete(Module::mcTable)->where('parent_id', Filter::$id, '=')->where('section', 'blog', '=')->run();
                        File::deleteRecursive(FMODPATH . Blog::BLOGDATA . Filter::$id, true);
                    }
                    
                    $message = str_replace('[NAME]', $title, Language::$word->_MOD_AM_DEL_OK);
                    Message::msgReply(true, 'success', $message);
                    Logger::writeLog($message);
                    break;
            }
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