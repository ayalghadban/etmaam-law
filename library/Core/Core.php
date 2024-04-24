<?php
    /**
     * Core Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Core.php, v1.00 4/25/2023 7:58 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Core;
    
    use Locale;
    use stdClass;
    use Wojo\Database\Database;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Validator\Validator;
    use ZipArchive;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Core
    {
        const sTable = 'settings';
        const txTable = 'trash';
        const gTable = 'gateways';
        const cjTable = 'cronjobs';
        
        public static string $language;
        
        public string $locale;
        public string $lang;
        public array $langlist;
        public int $weekstart;
        public string $theme;
        public int $perpage;
        public int $ploader;
        public string $currency;
        public int $offline;
        public string $offline_msg;
        public string $offline_info;
        public string $offline_d;
        public string $offline_t;
        public int $eucookie;
        public string $backup;
        public float $tax_rate;
        public int $enable_tax;
        public int $notify_admin;
        public string $pagesize;
        public string $inv_info;
        public string $inv_note;
        public string $company;
        public string $site_name;
        public string $site_dir;
        public string $site_email;
        public string $plogo;
        public string $logo;
        public int $showlogin;
        public int $showlang;
        public int $showcrumbs;
        public int $showsearch;
        public int $reg_verify;
        public int $auto_verify;
        public stdClass $slugs;
        public stdClass $system_slugs;
        public array $moddir;
        public array $modname;
        public string $pageslug;
        public string $short_date;
        public string $long_date;
        public string $time_format;
        public string $calendar_date;
        public string $dtz;
        public int $file_size;
        public string $file_ext;
        public int $thumb_w;
        public int $thumb_h;
        public int $img_w;
        public int $img_h;
        public int $avatar_w;
        public int $avatar_h;
        public string $mapapi;
        public string $ytapi;
        public int $flood;
        public int $attempt;
        public int $logging;
        public int $one_login;
        public string $analytics;
        public string $metakeys;
        public string $metadesc;
        public string $mailer;
        public string $smtp_host;
        public string $smtp_user;
        public string $smtp_pass;
        public int $smtp_port;
        public string $sendmail;
        public int $is_ssl;
        public stdClass $social;
        public string $wojov;
        public string $wojon;
        
        public string $_url;
        public array $_urlParts;
        
        /**
         * Core::__construct()
         *
         */
        public function __construct()
        {
            $this->settings();
            ($this->dtz) ? ini_set('date.timezone', $this->dtz) : date_default_timezone_set('UTC');
            Locale::setDefault($this->locale);
        }
        
        /**
         * getSettings
         *
         * @return void
         */
        private function settings(): void
        {
            $row = Database::Go()->select(self::sTable)->where('id', 1, '=')->first()->run();
            
            $this->site_name = $row->site_name;
            $this->company = $row->company;
            $this->site_dir = $row->site_dir;
            $this->site_email = $row->site_email;
            $this->logo = $row->logo;
            $this->plogo = $row->plogo;
            $this->short_date = $row->short_date;
            $this->long_date = $row->long_date;
            $this->calendar_date = $row->calendar_date;
            $this->time_format = $row->time_format;
            $this->dtz = $row->dtz;
            $this->locale = $row->locale;
            $this->lang = $row->lang;
            $this->weekstart = $row->weekstart;
            $this->theme = $row->theme;
            $this->perpage = $row->perpage;
            
            $this->showlang = $row->showlang;
            $this->showlogin = $row->showlogin;
            $this->showcrumbs = $row->showcrumbs;
            $this->showsearch = $row->showsearch;
            
            $this->ploader = $row->ploader;
            
            $this->offline = $row->offline;
            $this->offline_msg = $row->offline_msg;
            $this->offline_d = $row->offline_d;
            $this->offline_t = $row->offline_t;
            $this->offline_info = $row->offline_info;
            $this->eucookie = $row->eucookie;
            $this->backup = $row->backup;
            $this->currency = $row->currency;
            $this->file_ext = $row->file_ext;
            $this->file_size = $row->file_size;
            
            $this->avatar_w = $row->avatar_w;
            $this->avatar_h = $row->avatar_h;
            $this->thumb_h = $row->thumb_h;
            $this->thumb_w = $row->thumb_w;
            $this->img_w = $row->img_w;
            $this->img_h = $row->img_h;
            
            $this->tax_rate = $row->tax_rate;
            $this->enable_tax = $row->enable_tax;
            
            $this->reg_verify = $row->reg_verify;
            $this->auto_verify = $row->auto_verify;
            $this->notify_admin = $row->notify_admin;
            
            $this->mailer = $row->mailer;
            $this->smtp_host = $row->smtp_host;
            $this->smtp_user = $row->smtp_user;
            $this->smtp_pass = $row->smtp_pass;
            $this->smtp_port = $row->smtp_port;
            $this->sendmail = $row->sendmail;
            $this->is_ssl = $row->is_ssl;
            
            $this->slugs = json_decode($row->url_slugs);
            $this->system_slugs = json_decode($row->system_slugs);
            
            $this->moddir = (array) $this->slugs->moddir;
            $this->modname = (array) $this->slugs->module;
            $this->pageslug = $this->slugs->pagedata->page;
            
            $this->langlist = json_decode($row->lang_list);
            $this->social = json_decode($row->social_media);
            
            $this->ytapi = $row->ytapi;
            $this->mapapi = $row->mapapi;
            $this->analytics = $row->analytics;
            
            $this->flood = $row->flood;
            $this->attempt = $row->attempt;
            $this->logging = $row->logging;
            $this->one_login = $row->one_login;
            
            $this->inv_info = $row->inv_info;
            $this->inv_note = $row->inv_note;
            
            $this->wojov = $row->wojov;
            $this->wojon = $row->wojon;
        }
        
        /**
         * UpdateSlugs
         *
         * @return void
         */
        public static function updateSlugs(): void
        {
            $validate = Validator::run($_POST);
            $validate
                ->set('digishop', 'DigiShop')->required()->alpha()->min_len(3)->max_len(60)
                ->set('blog', 'Blog')->required()->alpha()->min_len(3)->max_len(60)
                ->set('portfolio', 'Portfolio')->required()->alpha()->min_len(3)->max_len(60)
                ->set('gallery', 'Gallery')->required()->alpha()->min_len(3)->max_len(60)
                ->set('shop', 'Shop')->required()->alpha()->min_len(3)->max_len(60)
                ->set('page', 'Page')->required()->alpha()->min_len(3)->max_len(60);
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $array = array(
                    'moddir' =>
                        array(
                            $safe->digishop => 'digishop',
                            $safe->blog => 'blog',
                            $safe->portfolio => 'portfolio',
                            $safe->gallery => 'gallery',
                            $safe->shop => 'shop',
                        ),
                    'pagedata' =>
                        array(
                            'page' => $safe->page,
                        ),
                    'module' =>
                        array(
                            'digishop' => $safe->digishop,
                            'digishop-cat' => 'category',
                            'digishop-checkout' => 'checkout',
                            'blog' => $safe->blog,
                            'blog-cat' => 'category',
                            'blog-search' => 'search',
                            'blog-archive' => 'archive',
                            'blog-author' => 'author',
                            'blog-tag' => 'tag',
                            'portfolio' => $safe->portfolio,
                            'portfolio-cat' => 'category',
                            'gallery' => $safe->gallery,
                            'gallery-album' => 'album',
                            'shop' => $safe->shop,
                            'shop-cat' => 'category',
                            'shop-cart' => 'cart',
                            'shop-checkout' => 'checkout'
                        ),
                );
                
                $data['url_slugs'] = json_encode($array);
                
                Database::Go()->update(self::sTable, $data)->where('id', 1, '=')->run();
                Message::msgReply(Database::Go()->affected(), 'success', Language::$word->UTL_SLUGS_OK);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * UpdateColors
         *
         * @return void
         */
        public function updateColors(): void
        {
            $validate = Validator::run($_POST);
            $validate
                ->set('alert-color', 'Alert')->required()->color()
                ->set('alert-color-hover', 'Alert Hover')->required()->color()
                ->set('alert-color-active', 'Alert Active')->required()->color()
                ->set('alert-color-inverted', 'Alert Inverted')->required()->color()
                ->set('alert-color-shadow', 'Alert Shadow')->required()->color()
                ->set('info-color', 'Info')->required()->color()
                ->set('info-color-hover', 'Info Hover')->required()->color()
                ->set('info-color-active', 'Info Active')->required()->color()
                ->set('info-color-inverted', 'Info Inverted')->required()->color()
                ->set('info-color-shadow', 'Info Shadow')->required()->color()
                ->set('light-color', 'Light')->required()->color()
                ->set('light-color-hover', 'Light Hover')->required()->color()
                ->set('light-color-active', 'Light Active')->required()->color()
                ->set('light-color-inverted', 'Light Inverted')->required()->color()
                ->set('light-color-shadow', 'Light Shadow')->required()->color()
                ->set('dark-color', 'Dark')->required()->color()
                ->set('dark-color-hover', 'Dark Hover')->required()->color()
                ->set('dark-color-active', 'Dark Active')->required()->color()
                ->set('dark-color-inverted', 'Dark Inverted')->required()->color()
                ->set('dark-color-shadow', 'Dark Shadow')->required()->color()
                ->set('grey-color', 'Grey')->required()->color()
                ->set('grey-color-100', 'Grey 100')->required()->color()
                ->set('grey-color-300', 'Grey 300')->required()->color()
                ->set('grey-color-500', 'Grey 500')->required()->color()
                ->set('grey-color-700', 'Grey 700')->required()->color();
            
            $validate
                ->set('body-color', 'Body Bg Color')->required()->color()
                ->set('body-bg-color', 'Body Bg Color')->required()->color()
                ->set('primary-color', 'Primary')->required()->color()
                ->set('primary-color-hover', 'Primary Hover')->required()->color()
                ->set('primary-color-active', 'Primary Active')->required()->color()
                ->set('primary-color-inverted', 'Primary Inverted')->required()->color()
                ->set('primary-color-shadow', 'Primary Shadow')->required()->color()
                ->set('secondary-color', 'Secondary')->required()->color()
                ->set('secondary-color-hover', 'Secondary Hover')->required()->color()
                ->set('secondary-color-active', 'Secondary Active')->required()->color()
                ->set('secondary-color-inverted', 'Secondary Inverted')->required()->color()
                ->set('secondary-color-shadow', 'Secondary Shadow')->required()->color()
                ->set('positive-color', 'Positive')->required()->color()
                ->set('positive-color-hover', 'Positive Hover')->required()->color()
                ->set('positive-color-active', 'Positive Active')->required()->color()
                ->set('positive-color-inverted', 'Positive Inverted')->required()->color()
                ->set('positive-color-shadow', 'Positive Shadow')->required()->color()
                ->set('negative-color', 'Negative')->required()->color()
                ->set('negative-color-hover', 'Negative Hover')->required()->color()
                ->set('negative-color-active', 'Negative Active')->required()->color()
                ->set('negative-color-inverted', 'Negative Inverted')->required()->color()
                ->set('negative-color-shadow', 'Negative Shadow')->required()->color();
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data = '
				  :root {
				   --body-color: ' . $safe->{'body-color'} . ';
				   --body-bg-color: ' . $safe->{'body-bg-color'} . ';
				   --primary-color: ' . $safe->{'primary-color'} . ';
				   --primary-color-hover: ' . $safe->{'primary-color-hover'} . ';
				   --primary-color-active: ' . $safe->{'primary-color-active'} . ';
				   --primary-color-inverted: ' . $safe->{'primary-color-inverted'} . ';
				   --primary-color-shadow: ' . $safe->{'primary-color-shadow'} . ';
				   --secondary-color: ' . $safe->{'secondary-color'} . ';
				   --secondary-color-hover: ' . $safe->{'secondary-color-hover'} . ';
				   --secondary-color-active: ' . $safe->{'secondary-color-active'} . ';
				   --secondary-color-inverted: ' . $safe->{'secondary-color-inverted'} . ';
				   --secondary-color-shadow: ' . $safe->{'secondary-color-shadow'} . ';
				   --positive-color: ' . $safe->{'positive-color'} . ';
				   --positive-color-hover: ' . $safe->{'positive-color-hover'} . ';
				   --positive-color-active: ' . $safe->{'positive-color-active'} . ';
				   --positive-color-inverted: ' . $safe->{'positive-color-inverted'} . ';
				   --positive-color-shadow: ' . $safe->{'positive-color-shadow'} . ';
				   --negative-color: ' . $safe->{'negative-color'} . ';
				   --negative-color-hover: ' . $safe->{'negative-color-hover'} . ';
				   --negative-color-active: ' . $safe->{'negative-color-active'} . ';
				   --negative-color-inverted: ' . $safe->{'negative-color-inverted'} . ';
				   --negative-color-shadow: ' . $safe->{'negative-color-shadow'} . ';
				   --alert-color: ' . $safe->{'alert-color'} . ';
				   --alert-color-hover: ' . $safe->{'alert-color-hover'} . ';
				   --alert-color-active: ' . $safe->{'alert-color-active'} . ';
				   --alert-color-inverted: ' . $safe->{'alert-color-inverted'} . ';
				   --alert-color-shadow: ' . $safe->{'alert-color-shadow'} . ';
				   --info-color: ' . $safe->{'info-color'} . ';
				   --info-color-hover: ' . $safe->{'info-color-hover'} . ';
				   --info-color-active: ' . $safe->{'info-color-active'} . ';
				   --info-color-inverted: ' . $safe->{'info-color-inverted'} . ';
				   --info-color-shadow: ' . $safe->{'info-color-shadow'} . ';
				   --light-color: ' . $safe->{'light-color'} . ';
				   --light-color-hover: ' . $safe->{'light-color-hover'} . ';
				   --light-color-active: ' . $safe->{'light-color-active'} . ';
				   --light-color-inverted: ' . $safe->{'light-color-inverted'} . ';
				   --light-color-shadow: ' . $safe->{'light-color-shadow'} . ';
				   --dark-color: ' . $safe->{'dark-color'} . ';
				   --dark-color-hover: ' . $safe->{'dark-color-hover'} . ';
				   --dark-color-active: ' . $safe->{'dark-color-active'} . ';
				   --dark-color-inverted: ' . $safe->{'dark-color-inverted'} . ';
				   --dark-color-shadow: ' . $safe->{'dark-color-shadow'} . ';
				   --black-color: #000;
				   --white-color: #fff;
				   --shadow-color: rgba(136, 152, 170, .15);
				   --grey-color: ' . $safe->{'grey-color'} . ';
				   --grey-color-100: ' . $safe->{'grey-color-100'} . ';
				   --grey-color-300: ' . $safe->{'grey-color-300'} . ';
				   --grey-color-500: ' . $safe->{'grey-color-500'} . ';
				   --grey-color-700: ' . $safe->{'grey-color-700'} . ';
				  }
			  ';
                
                $filename = THEMEBASE . 'css/color.css';
                $file = THEMEURL . 'css/color.css';
                
                if (is_writable($filename)) {
                    File::writeToFile($filename, trim(preg_replace('/\t+/', '', $data)));
                    File::deleteFile(THEMEBASE . 'cache/master_main_ltr.css');
                    Message::msgReply($file, 'success', Message::formatSuccessMessage($file, Language::$word->UTL_COLOR_OK));
                } else {
                    Message::msgReply($file, 'error', Message::formatErrorMessage($file, Language::$word->CF_FILE_ERROR));
                }
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Install
         *
         * @return void
         */
        public function install(): void
        {
            
            File::makeDirectory(BASEPATH . 'temp_update/');
            File::deleteRecursive(BASEPATH . 'temp_update/');
            $file = '';
            if (!array_key_exists('installer', $_FILES)) {
                Message::$msgs['installer'] = Language::$word->UTL_INSTALL_E;
            } else {
                preg_match('/[0-9]+/', ini_get('post_max_size'), $match);
                if (Validator::compareNumbers(round($_FILES['installer']['size'] / 1024 / 1024, 1), $match[0], '>')) {
                    Message::$msgs['installer'] = Language::$word->FU_ERROR10 . ' ' . ini_get('post_max_size');
                } else {
                    $file = File::upload('installer', 52428800, 'zip');
                }
            }
            
            if (count(Message::$msgs) === 0) {
                File::process($file, BASEPATH . 'temp_update/', false, 'temp_install');
                $path = BASEPATH . 'temp_update/temp_install.zip';
                
                $zip = new ZipArchive;
                $res = $zip->open($path);
                
                if ($res === true) {
                    $zip->extractTo(BASEPATH . 'temp_update/');
                    $zip->close();
                    
                    $json_file = File::loadFile(BASEPATH . 'temp_update/package.json');
                    
                    if ($json_file) {
                        $package = json_decode($json_file);
                        
                        // check if already exists
                        if (Database::Go()->exist($package->sql)->run()) {
                            $json['type'] = 'error';
                            $json['title'] = Language::$word->ERROR;
                            $json['message'] = $package->name . ' already exists. Install can not continue.';
                            // validate version
                        } elseif (Validator::compareNumbers($package->ver, $this->wojov, '<')) {
                            $json['type'] = 'error';
                            $json['title'] = Language::$word->ERROR;
                            $json['message'] = 'This package is not compatible with CMS PRO v.' . $this->wojov;
                            // all checks out god to go
                        } else {
                            if (File::exists(BASEPATH . 'temp_update/install.sql')) {
                                $sqlData = File::parseSQL(BASEPATH . 'temp_update/install.sql');
                                foreach ($sqlData as $sql) {
                                    Database::Go()->rawQuery($sql)->run();
                                }
                                
                                File::deleteFile(BASEPATH . 'temp_update/install.sql');
                            }
                            
                            File::deleteFile(BASEPATH . 'temp_update/temp_install.zip');
                            
                            $json['type'] = 'success';
                            $json['title'] = Language::$word->SUCCESS;
                            $json['message'] = $package->name . ' v.' . $package->ver . ' is successfully installed.';
                            
                            File::copyDirectory(BASEPATH . 'temp_update', BASEPATH);
                            File::deleteRecursive(BASEPATH . 'temp_update/');
                            File::deleteFile(BASEPATH . 'install.php');
                            
                            //install languages
                            if ($package->lang) {
                                if ($langData = Database::Go()->select(Language::lTable, array('abbr'))->where('abbr', 'en', '<>')->run()) {
                                    foreach ($langData as $lang) {
                                        $flag_id = $lang->abbr;
                                        include_once(BASEPATH . $package->lang);
                                    }
                                }
                            }
                            
                            File::deleteFile(BASEPATH . 'package.json');
                        }
                    } else {
                        $json['type'] = 'error';
                        $json['title'] = Language::$word->ERROR;
                        $json['message'] = 'Can not read package';
                    }
                } else {
                    $json['type'] = 'error';
                    $json['title'] = Language::$word->ERROR;
                    $json['message'] = 'Can not open zip archive';
                }
                print json_encode($json);
            } else {
                Message::msgSingleStatus();
            }
        }
    }