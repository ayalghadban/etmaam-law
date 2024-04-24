<?php
    /**
     * LanguageController Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: LanguageController.php, v1.00 5/8/2023 9:11 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Core;
    use Wojo\Core\Filter;
    use Wojo\Core\Logger;
    use Wojo\Core\Membership;
    use Wojo\Core\Module;
    use Wojo\Core\Plugin;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\Exception\NotFoundException;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class LanguageController extends Controller
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
            $this->view->data = $this->getLanguages();
            
            $this->view->crumbs = ['admin', Language::$word->ADM_LNGMNG];
            $this->view->caption = Language::$word->LG_TITLE;
            $this->view->title = Language::$word->META_T21;
            $this->view->subtitle = Language::$word->LG_SUB;
            $this->view->render('language', 'view/admin/');
        }
        
        /**
         * new
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function new(): void
        {
            $this->view->crumbs = ['admin', 'languages', 'new'];
            $this->view->caption = Language::$word->LG_SUB5;
            $this->view->title = Language::$word->LG_SUB5;
            $this->view->subtitle = [];
            $this->view->render('language', 'view/admin/');
        }
        
        /**
         * edit
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function edit(): void
        {
            $this->view->crumbs = ['admin', 'languages', 'edit'];
            $this->view->title = Language::$word->LG_TITLE1;
            $this->view->caption = Language::$word->LG_SUB1;
            $this->view->subtitle = null;
            
            if (!$row = $this->db->select(Language::lTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->data = $row;
                $this->view->render('language', 'view/admin/');
            }
        }
        
        /**
         * translate
         *
         * @return void
         * @throws FileNotFoundException
         */
        public function translate(): void
        {
            $this->view->crumbs = ['admin', 'languages', Language::$word->LG_TITLE1];
            $this->view->title = Language::$word->LG_TITLE1;
            $this->view->caption = Language::$word->LG_TITLE1;
            $this->view->subtitle = Language::$word->LG_SUB2;
            
            if (!$row = $this->db->select(Language::lTable)->where('id', $this->view->matches, '=')->first()->run()) {
                if (DEBUG) {
                    $this->view->error = 'Invalid ID ' . ($this->view->matches) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $this->view->error = Language::$word->META_ERROR;
                }
                $this->view->render('error', 'view/admin/');
            } else {
                $this->view->row = $row;
                $this->view->data = Language::$main;
                $this->view->sections = Language::$section;
                $this->view->pluglang = Language::$plugins;
                $this->view->modlang = Language::$modules;
                $this->view->render('language', 'view/admin/');
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
                        case 'new':
                        case 'update':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->process();
                            break;
                        
                        case 'color':
                            IS_DEMO ? print '0' : $this->color();
                            break;
                        
                        case 'phrase':
                            IS_DEMO ? print '0' : $this->phrase();
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
            
            $getAction = Validator::get('action');
            if ($getAction) {
                if (IS_AJAX) {
                    switch ($getAction) {
                        case 'section':
                            $this->section();
                            break;
                        
                        case 'file':
                            $this->file();
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
        public function process(): void
        {
            $validate = Validator::run($_POST);
            $validate
                ->set('name', Language::$word->LG_NAME)->required()->string()
                ->set('abbr', Language::$word->LG_ABBR)->required()->alpha('basic')->exact_len(2)
                ->set('color', Language::$word->LG_COLOR)->color()
                ->set('author', Language::$word->LG_AUTHOR)->string();
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data = array(
                    'name' => $safe->name,
                    'abbr' => strtolower($safe->abbr),
                    'color' => $safe->color,
                    'author' => $safe->author,
                );
                
                (Filter::$id) ? $this->db->update(Language::lTable, $data)->where('id', Filter::$id, '=')->run() : $last_id = $this->db->insert(Language::lTable, $data)->run();
                
                $this->buildLangList();
                
                $message = Filter::$id ?
                    Message::formatSuccessMessage($data['name'], Language::$word->LG_UPDATE_OK) :
                    Message::formatSuccessMessage($data['name'], Language::$word->LG_ADDED_OK);
                
                Message::msgReply($this->db->affected(), 'success', $message);
                Logger::writeLog($message);
                
                
                //run language files
                if (!Filter::$id) {
                    $flag_id = $data['abbr'];
                    
                    // custom fields
                    $sql = '
                    ALTER TABLE `' . Content::cfTable . "`
                      ADD COLUMN title_$flag_id VARCHAR (60) NOT NULL AFTER title_en,
                      ADD COLUMN tooltip_$flag_id VARCHAR (100) DEFAULT NULL AFTER tooltip_en
                    ";
                    $this->db->rawQuery($sql)->run();
                    
                    $this->db->rawQuery('UPDATE `' . Content::cfTable . '` SET `title_' . $flag_id . '`=`title_en`')->run();
                    $this->db->rawQuery('UPDATE `' . Content::cfTable . '` SET `tooltip_' . $flag_id . '`=`tooltip_en`')->run();
                    
                    // email templates
                    $sql = '
                    ALTER TABLE `' . Content::eTable . "`
                      ADD COLUMN name_$flag_id VARCHAR (100) NOT NULL AFTER name_en,
                      ADD COLUMN subject_$flag_id VARCHAR (150) NOT NULL AFTER subject_en,
                      ADD COLUMN help_$flag_id tinytext AFTER help_en,
                      ADD COLUMN body_$flag_id text NOT NULL AFTER body_en
                    ";
                    $this->db->rawQuery($sql)->run();
                    
                    $this->db->rawQuery('UPDATE `' . Content::eTable . '` SET `name_' . $flag_id . '`=`name_en`')->run();
                    $this->db->rawQuery('UPDATE `' . Content::eTable . '` SET `subject_' . $flag_id . '`=`subject_en`')->run();
                    $this->db->rawQuery('UPDATE `' . Content::eTable . '` SET `help_' . $flag_id . '`=`help_en`')->run();
                    $this->db->rawQuery('UPDATE `' . Content::eTable . '` SET `body_' . $flag_id . '`=`body_en`')->run();
                    
                    // layout
                    $sql = 'ALTER TABLE `' . Content::lTable . "` ADD COLUMN page_slug_$flag_id VARCHAR (150) DEFAULT NULL AFTER page_slug_en";
                    $this->db->rawQuery($sql)->run();
                    
                    $this->db->rawQuery('UPDATE `' . Content::lTable . '` SET `page_slug_' . $flag_id . '`=`page_slug_en`')->run();
                    
                    // memberships
                    $sql = '
                    ALTER TABLE `' . Membership::mTable . "`
                      ADD COLUMN title_$flag_id VARCHAR (80) NOT NULL AFTER title_en,
                      ADD COLUMN description_$flag_id VARCHAR (150) DEFAULT NULL AFTER description_en
                    ";
                    $this->db->rawQuery($sql)->run();
                    
                    $this->db->rawQuery('UPDATE `' . Membership::mTable . '` SET `title_' . $flag_id . '`=`title_en`')->run();
                    $this->db->rawQuery('UPDATE `' . Membership::mTable . '` SET `description_' . $flag_id . '`=`description_en`')->run();
                    
                    // menus
                    $sql = '
                    ALTER TABLE `' . Content::mTable . "`
                      ADD COLUMN page_slug_$flag_id VARCHAR (100) DEFAULT NULL AFTER page_slug_en,
                      ADD COLUMN name_$flag_id VARCHAR (100) NOT NULL AFTER name_en,
                      ADD COLUMN caption_$flag_id VARCHAR (100) DEFAULT NULL AFTER caption_en
                    ";
                    $this->db->rawQuery($sql)->run();
                    
                    $this->db->rawQuery('UPDATE `' . Content::mTable . '` SET `page_slug_' . $flag_id . '`=`page_slug_en`')->run();
                    $this->db->rawQuery('UPDATE `' . Content::mTable . '` SET `name_' . $flag_id . '`=`name_en`')->run();
                    $this->db->rawQuery('UPDATE `' . Content::mTable . '` SET `caption_' . $flag_id . '`=`caption_en`')->run();
                    
                    // mod_adblock
                    $sql = "ALTER TABLE `mod_adblock`ADD COLUMN title_$flag_id VARCHAR (100) NOT NULL AFTER title_en";
                    $this->db->rawQuery($sql)->run();
                    
                    $this->db->rawQuery('UPDATE `mod_adblock` SET `title_' . $flag_id . '`=`title_en`')->run();
                    
                    // mod_events
                    $sql = "
                    ALTER TABLE `mod_events`
                      ADD COLUMN title_$flag_id VARCHAR (100) NOT NULL AFTER title_en,
                      ADD COLUMN venue_$flag_id VARCHAR (100) DEFAULT NULL AFTER venue_en,
                      ADD COLUMN body_$flag_id TEXT AFTER body_en
                    ";
                    $this->db->rawQuery($sql)->run();
                    
                    $this->db->rawQuery('UPDATE `mod_events` SET `title_' . $flag_id . '`=`title_en`')->run();
                    $this->db->rawQuery('UPDATE `mod_events` SET `venue_' . $flag_id . '`=`venue_en`')->run();
                    $this->db->rawQuery('UPDATE `mod_events` SET `body_' . $flag_id . '`=`body_en`')->run();
                    
                    // mod_gallery
                    $sql = "
                    ALTER TABLE `mod_gallery`
                      ADD COLUMN title_$flag_id VARCHAR (60) NOT NULL AFTER title_en,
                      ADD COLUMN slug_$flag_id VARCHAR (100) NOT NULL AFTER slug_en,
                      ADD COLUMN description_$flag_id VARCHAR (100) DEFAULT NULL AFTER description_en
                    ";
                    $this->db->rawQuery($sql)->run();
                    
                    $this->db->rawQuery('UPDATE `mod_gallery` SET `title_' . $flag_id . '`=`title_en`')->run();
                    $this->db->rawQuery('UPDATE `mod_gallery` SET `slug_' . $flag_id . '`=`slug_en`')->run();
                    $this->db->rawQuery('UPDATE `mod_gallery` SET `description_' . $flag_id . '`=`description_en`')->run();
                    
                    // mod_gallery_data
                    $sql = "
                    ALTER TABLE `mod_gallery_data`
                      ADD COLUMN title_$flag_id VARCHAR (80) NOT NULL AFTER title_en,
                      ADD COLUMN description_$flag_id VARCHAR (200) DEFAULT NULL AFTER description_en
                    ";
                    $this->db->rawQuery($sql)->run();
                    
                    $this->db->rawQuery('UPDATE `mod_gallery_data` SET `title_' . $flag_id . '`=`title_en`')->run();
                    $this->db->rawQuery('UPDATE `mod_gallery_data` SET `description_' . $flag_id . '`=`description_en`')->run();
                    
                    // mod_timeline_data
                    $sql = "
                    ALTER TABLE `mod_timeline_data`
                      ADD COLUMN title_$flag_id VARCHAR (100) NOT NULL AFTER title_en,
                      ADD COLUMN body_$flag_id TEXT AFTER body_en
                    ";
                    $this->db->rawQuery($sql)->run();
                    
                    $this->db->rawQuery('UPDATE `mod_timeline_data` SET `title_' . $flag_id . '`=`title_en`')->run();
                    $this->db->rawQuery('UPDATE `mod_timeline_data` SET `body_' . $flag_id . '`=`body_en`')->run();
                    
                    // modules
                    $sql = '
                    ALTER TABLE `' . Module::mTable . "`
                      ADD COLUMN title_$flag_id VARCHAR (120) NOT NULL AFTER title_en,
                      ADD COLUMN info_$flag_id VARCHAR (200) DEFAULT NULL AFTER info_en,
                      ADD COLUMN keywords_$flag_id VARCHAR(200) DEFAULT NULL AFTER keywords_en,
                      ADD COLUMN description_$flag_id TEXT AFTER description_en
                    ";
                    $this->db->rawQuery($sql)->run();
                    
                    $this->db->rawQuery('UPDATE `' . Module::mTable . '` SET `title_' . $flag_id . '`=`title_en`')->run();
                    $this->db->rawQuery('UPDATE `' . Module::mTable . '` SET `info_' . $flag_id . '`=`info_en`')->run();
                    $this->db->rawQuery('UPDATE `' . Module::mTable . '` SET `keywords_' . $flag_id . '`=`keywords_en`')->run();
                    $this->db->rawQuery('UPDATE `' . Module::mTable . '` SET `description_' . $flag_id . '`=`description_en`')->run();
                    
                    // pages
                    $sql = '
                    ALTER TABLE `' . Content::pTable . "`
                      ADD COLUMN title_$flag_id VARCHAR (200) NOT NULL AFTER title_en,
                      ADD COLUMN slug_$flag_id VARCHAR (200) DEFAULT NULL AFTER slug_en,
                      ADD COLUMN caption_$flag_id VARCHAR(150) DEFAULT NULL AFTER caption_en,
                      ADD COLUMN custom_bg_$flag_id VARCHAR(100) DEFAULT NULL AFTER custom_bg_en,
                      ADD COLUMN body_$flag_id TEXT AFTER body_en,
                      ADD COLUMN keywords_$flag_id VARCHAR(200) DEFAULT NULL AFTER keywords_en,
                      ADD COLUMN description_$flag_id TEXT AFTER description_en
                    ";
                    $this->db->rawQuery($sql)->run();
                    
                    $this->db->rawQuery('UPDATE `' . Content::pTable . '` SET `title_' . $flag_id . '`=`title_en`')->run();
                    $this->db->rawQuery('UPDATE `' . Content::pTable . '` SET `slug_' . $flag_id . '`=`slug_en`')->run();
                    $this->db->rawQuery('UPDATE `' . Content::pTable . '` SET `caption_' . $flag_id . '`=`caption_en`')->run();
                    $this->db->rawQuery('UPDATE `' . Content::pTable . '` SET `custom_bg_' . $flag_id . '`=`custom_bg_en`')->run();
                    $this->db->rawQuery('UPDATE `' . Content::pTable . '` SET `body_' . $flag_id . '`=`body_en`')->run();
                    $this->db->rawQuery('UPDATE `' . Content::pTable . '` SET `keywords_' . $flag_id . '`=`keywords_en`')->run();
                    $this->db->rawQuery('UPDATE `' . Content::pTable . '` SET `description_' . $flag_id . '`=`description_en`')->run();
                    
                    //do system slug
                    Url::doSystemPageSlugs();
                    
                    // plug_carousel
                    $sql = "
                    ALTER TABLE `plug_carousel`
                      ADD COLUMN title_$flag_id VARCHAR (100) NOT NULL AFTER title_en,
                      ADD COLUMN body_$flag_id TEXT AFTER body_en
                    ";
                    $this->db->rawQuery($sql)->run();
                    
                    $this->db->rawQuery('UPDATE `plug_carousel` SET `title_' . $flag_id . '`=`title_en`')->run();
                    $this->db->rawQuery('UPDATE `plug_carousel` SET `body_' . $flag_id . '`=`body_en`')->run();
                    
                    // plug_background
                    $sql = "
                    ALTER TABLE `plug_background`
                      ADD COLUMN title_$flag_id VARCHAR (100) NOT NULL AFTER title_en,
                      ADD COLUMN header_$flag_id tinytext AFTER header_en,
                      ADD COLUMN subtext_$flag_id tinytext AFTER subtext_en
                    ";
                    $this->db->rawQuery($sql)->run();
                    
                    $this->db->rawQuery('UPDATE `plug_background` SET `title_' . $flag_id . '`=`title_en`')->run();
                    $this->db->rawQuery('UPDATE `plug_background` SET `header_' . $flag_id . '`=`header_en`')->run();
                    $this->db->rawQuery('UPDATE `plug_background` SET `subtext_' . $flag_id . '`=`subtext_en`')->run();
                    
                    // plugins
                    $sql = '
                    ALTER TABLE `' . Plugin::mTable . "`
                      ADD COLUMN title_$flag_id VARCHAR (120) NOT NULL AFTER title_en,
                      ADD COLUMN body_$flag_id TEXT AFTER body_en,
                      ADD COLUMN info_$flag_id VARCHAR (150) DEFAULT NULL AFTER info_en
                    ";
                    $this->db->rawQuery($sql)->run();
                    
                    $this->db->rawQuery('UPDATE `' . Plugin::mTable . '` SET `title_' . $flag_id . '`=`title_en`')->run();
                    $this->db->rawQuery('UPDATE `' . Plugin::mTable . '` SET `title_' . $flag_id . '`=`title_en`')->run();
                    $this->db->rawQuery('UPDATE `' . Plugin::mTable . '` SET `info_' . $flag_id . '`=`info_en`')->run();
                    
                    //modules
                    if ($modules = File::scanFiles(AMODPATH, '*_addLanguage.lang.php')) {
                        foreach ($modules as $mdata) {
                            include_once($mdata);
                        }
                    }
                }
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * color
         *
         * @return void
         */
        private function color(): void
        {
            if (isset($_POST['color'])) {
                $color = Validator::sanitize($_POST['color'], 'string', 7);
                if ($this->db->update(Language::lTable, ['color' => $color])->where('id', Filter::$id, '=')->run()) {
                    $data = $this->getLanguages();
                    $this->db->update(Core::sTable, array('lang_list' => json_encode($data)))->where('id', 1, '=')->run();
                }
            }
        }
        
        /**
         * phrase
         *
         * @return void
         */
        private function phrase(): void
        {
            $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
            $payload = BASEPATH . Language::langdir . $_POST['path'];
            if (File::exists($payload)) {
                $data = json_decode(File::loadFile($payload), true);
                $update = array();
                $what = Validator::sanitize($_POST['key']);
                
                foreach ($data as $key => $value) {
                    foreach ($value as $name => $row) {
                        if ($name == $what) {
                            $value[$name] = $title;
                        }
                        $update[$key] = $value;
                    }
                }
                
                $jsonData = json_encode($update, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                File::writeToFile($payload, $jsonData);
                
                $json['title'] = $title;
                print json_encode($json);
            }
        }
        
        /**
         * section
         *
         * @return void
         * @throws FileNotFoundException
         */
        private function section(): void
        {
            $payload = BASEPATH . Language::langdir . $_GET['abbr'] . '/lang.json';
            if (File::exists($payload)) {
                $data = json_decode(File::loadFile($payload), true);
                $section = $data[Validator::sanitize($_GET['section'])];
                
                $this->view->section = $section;
                $this->view->type = $_GET['type'];
                $this->view->abbr = $_GET['abbr'];
                
                $json['html'] = $this->view->snippet('loadLanguageSection', 'view/admin/snippets/');
            } else {
                $json['type'] = 'error';
                $json['title'] = Language::$word->ERROR;
                $json['message'] = Language::$word->FU_ERROR15;
            }
            print json_encode($json);
        }
        
        /**
         * file
         *
         * @return void
         * @throws FileNotFoundException
         */
        private function file(): void
        {
            $payload = BASEPATH . Language::langdir . $_GET['abbr'] . '/' . $_GET['section'];
            if (File::exists($payload)) {
                $data = json_decode(File::loadFile($payload), true);
                $result = $data[Validator::sanitize($_GET['key'])];
                
                $this->view->path = $_GET['section'];
                $this->view->data = $result;
                $this->view->type = $_GET['type'];
                $this->view->abbr = $_GET['abbr'];
                
                $json['html'] = $this->view->snippet('loadLanguageSection', 'view/admin/snippets/');
                $json['type'] = 'success';
            } else {
                $json['type'] = 'error';
                $json['title'] = Language::$word->ERROR;
                $json['message'] = Language::$word->FU_ERROR15;
            }
            print json_encode($json);
        }
        
        /**
         * getLanguages
         *
         * @return array|false|mixed
         */
        public function getLanguages(): mixed
        {
            return $this->db->select(Language::lTable)->orderBy('home', 'DESC')->run();
        }
        
        /**
         * buildLangList
         *
         * @return void
         */
        public function buildLangList(): void
        {
            $result = $this->db->select(Language::lTable)->run('json');
            $this->db->update(Core::sTable, array('lang_list' => $result))->where('id', 1, '=')->run();
        }
        
        /**
         * _delete
         *
         * @return void
         */
        private function _delete(): void
        {
            $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
            $json = array();
            if ($row = $this->db->select(Language::lTable, array('id', 'abbr'))->where('id', Filter::$id)->first()->run()) {
                if ($row->abbr == Core::$language) {
                    $json['type'] = 'error';
                    $json['title'] = Language::$word->ERROR;
                    $json['message'] = Language::$word->LG_INFO;
                } else {
                    if ($this->_deleteLanguage($row->abbr)) {
                        $this->db->delete(Language::lTable)->where('id', Filter::$id)->run();
                        Core::buildLangList();
                        Url::doSystemPageSlugs();
                        $json['title'] = Language::$word->SUCCESS;
                        $json['type'] = 'success';
                        $json['message'] = str_replace('[NAME]', $title, Language::$word->LG_DEL_OK);
                        
                    }
                }
            }
            print json_encode($json);
        }
        
        /**
         * _deleteLanguage
         *
         * @param string $abbr
         * @return true
         */
        private function _deleteLanguage(string $abbr): true
        {
            $this->db->rawQuery('
            ALTER TABLE `' . Content::cfTable . "`
              DROP COLUMN title_$abbr,
              DROP COLUMN tooltip_$abbr"
            )->run();
            
            $this->db->rawQuery('
            ALTER TABLE `' . Content::eTable . "`
              DROP COLUMN name_$abbr,
              DROP COLUMN subject_$abbr,
              DROP COLUMN body_$abbr,
              DROP COLUMN help_$abbr"
            );
            
            $this->db->rawQuery('
            ALTER TABLE `' . Content::lTable . "`
              DROP COLUMN page_slug_$abbr"
            );
            
            $this->db->rawQuery('
            ALTER TABLE `' . Membership::mTable . "`
              DROP COLUMN title_$abbr,
              DROP COLUMN description_$abbr"
            );
            
            $this->db->rawQuery('
            ALTER TABLE `' . Content::mTable . "`
              DROP COLUMN page_slug_$abbr,
              DROP COLUMN name_$abbr,
            DROP COLUMN caption_$abbr"
            );
            
            $this->db->rawQuery("
            ALTER TABLE `mod_adblock`
              DROP COLUMN title_$abbr;"
            );
            
            $this->db->rawQuery("
            ALTER TABLE `mod_events`
              DROP COLUMN title_$abbr,
              DROP COLUMN venue_$abbr,
              DROP COLUMN body_$abbr"
            );
            
            $this->db->rawQuery("
            ALTER TABLE `mod_gallery`
              DROP COLUMN title_$abbr,
              DROP COLUMN slug_$abbr,
              DROP COLUMN description_$abbr"
            );
            
            $this->db->rawQuery("
            ALTER TABLE `mod_gallery_data`
              DROP COLUMN title_$abbr,
              DROP COLUMN description_$abbr;"
            );
            
            $this->db->rawQuery("
            ALTER TABLE `mod_timeline_data`
              DROP COLUMN title_$abbr,
              DROP COLUMN body_$abbr"
            );
            
            $this->db->rawQuery('
            ALTER TABLE `' . Module::mTable . "`
              DROP COLUMN title_$abbr,
              DROP COLUMN info_$abbr,
              DROP COLUMN keywords_$abbr,
              DROP COLUMN description_$abbr"
            );
            
            $this->db->rawQuery('
            ALTER TABLE `' . Content::pTable . "`
              DROP COLUMN title_$abbr,
              DROP COLUMN slug_$abbr,
              DROP COLUMN caption_$abbr,
              DROP COLUMN custom_bg_$abbr,
              DROP COLUMN body_$abbr,
              DROP COLUMN keywords_$abbr,
              DROP COLUMN description_$abbr"
            );
            
            $this->db->rawQuery("
            ALTER TABLE `plug_carousel`
              DROP COLUMN title_$abbr,
              DROP COLUMN body_$abbr;"
            );
            
            $this->db->rawQuery("
            ALTER TABLE `plug_background`
              DROP COLUMN title_$abbr,
              DROP COLUMN header_$abbr,
              DROP COLUMN subtext_$abbr;"
            );
            
            $this->db->rawQuery('
            ALTER TABLE `' . Plugin::mTable . "`
              DROP COLUMN title_$abbr,
              DROP COLUMN body_$abbr,
              DROP COLUMN info_$abbr"
            );
            
            //modules
            if ($modules = File::scanFiles(AMODPATH, '*_delLanguage.lang.php')) {
                foreach ($modules as $row) {
                    include_once($row);
                }
            }
            return true;
        }
    }