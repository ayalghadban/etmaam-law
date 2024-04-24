<?php
    /**
     * ConfigurationController CLass
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: ConfigurationController.php, v1.00 5/11/2023 9:55 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Controller\Admin;
    
    use Wojo\Core\Content;
    use Wojo\Core\Controller;
    use Wojo\Core\Core;
    use Wojo\Exception\FileNotFoundException;
    use Wojo\File\File;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class ConfigurationController extends Controller
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
            $this->view->data = $this->core;
            $this->view->countries = $this->db->select(Content::cTable)->orderBy('sorting', 'DESC')->run();
            
            $this->view->crumbs = ['admin', Language::$word->META_T14];
            $this->view->caption = Language::$word->META_T14;
            $this->view->title = Language::$word->META_T14;
            $this->view->subtitle = Language::$word->CG_INFO;
            $this->view->render('configuration', 'view/admin/');
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
                        case 'update':
                            IS_DEMO ? Message::msgReply(true, 'info', Language::$word->PROCESS_ERR_DEMO) : $this->process();
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
            $validate
                ->set('site_name', Language::$word->CG_SITENAME)->required()->string()->min_len(2)->max_len(80)
                ->set('company', Language::$word->CG_COMPANY)->required()->string()->min_len(2)->max_len(80)
                ->set('site_email', Language::$word->CG_WEBEMAIL)->email()
                ->set('theme', Language::$word->CG_THEME)->required()->string()
                ->set('perpage', Language::$word->CG_PERPAGE)->required()->numeric()
                ->set('thumb_w', Language::$word->CG_TH_WH)->required()->numeric()
                ->set('thumb_h', Language::$word->CG_TH_WH)->required()->numeric()
                ->set('img_w', Language::$word->CG_IM_WH)->required()->numeric()
                ->set('img_h', Language::$word->CG_IM_WH)->required()->numeric()
                ->set('avatar_w', Language::$word->CG_AV_WH)->required()->numeric();
            
            $validate
                ->set('avatar_h', Language::$word->CG_AV_WH)->required()->numeric()
                ->set('long_date', Language::$word->CG_LONGDATE)->required()->string()
                ->set('short_date', Language::$word->CG_SHORTDATE)->required()->string()
                ->set('calendar_date', Language::$word->CG_CALDATE)->required()->string()
                ->set('time_format', Language::$word->CG_TIMEFORMAT)->required()->string()
                ->set('dtz', Language::$word->CG_DTZ)->required()->string()
                ->set('locale', Language::$word->CG_LOCALES)->required()->string()
                ->set('weekstart', Language::$word->CG_WEEKSTART)->required()->numeric()
                ->set('lang', Language::$word->CG_LANG)->required()->string()->exact_len(2)
                ->set('ploader', Language::$word->CG_PLOADER)->required()->numeric();
            
            $validate
                ->set('eucookie', Language::$word->CG_EUCOOKIE)->required()->numeric()
                ->set('offline', Language::$word->CG_OFFLINE_M)->required()->numeric()
                ->set('showlang', Language::$word->CG_LANG_SHOW)->required()->numeric()
                ->set('showsearch', Language::$word->CG_SEARCH_SHOW)->required()->numeric()
                ->set('showlogin', Language::$word->CG_LOGIN_SHOW)->required()->numeric()
                ->set('showcrumbs', Language::$word->CG_CRUMBS_SHOW)->required()->numeric()
                ->set('currency', Language::$word->CG_CURRENCY)->required()->string()->min_len(3)->max_len(6)
                ->set('tax_rate', Language::$word->CG_ETAX_RATE)->required()->float()
                ->set('enable_tax', Language::$word->CG_ETAX)->required()->numeric()
                ->set('file_size', Language::$word->CG_FILESIZE)->required()->numeric()->string()->min_len(1)->max_len(3)
                ->set('file_ext', Language::$word->CG_FILETYPE)->required()->string()
                ->set('reg_verify', Language::$word->CG_REGVERIFY)->required()->numeric();
            
            $validate
                ->set('auto_verify', Language::$word->CG_AUTOVERIFY)->required()->numeric()
                ->set('notify_admin', Language::$word->CG_NOTIFY_ADMIN)->required()->numeric()
                ->set('flood', Language::$word->CG_LOGIN_TIME)->required()->numeric()->min_len(1)->max_len(3)
                ->set('attempt', Language::$word->CG_LOGIN_ATTEMPT)->required()->numeric()->exact_len(1)
                ->set('logging', Language::$word->CG_LOG_ON)->required()->numeric()
                ->set('one_login', Language::$word->CG_LOGIN)->required()->numeric()
                ->set('mailer', Language::$word->CG_MAILER)->required()->string()->min_len(3)->max_len(5)
                ->set('is_ssl', Language::$word->CG_SMTP_SSL)->required()->numeric()
                ->set('site_dir', Language::$word->CG_SMTP_SSL)->string()
                ->set('twitter', Language::$word->CG_SMTP_SSL)->string();
            
            $validate
                ->set('facebook', Language::$word->CG_SMTP_SSL)->string()
                ->set('offline_d_submit', Language::$word->CG_SMTP_SSL)->string()
                ->set('offline_t', Language::$word->CG_SMTP_SSL)->string()
                ->set('inv_info', Language::$word->CG_SMTP_SSL)->text('basic')
                ->set('inv_note', Language::$word->CG_SMTP_SSL)->text('basic')
                ->set('offline_info', Language::$word->CG_SMTP_SSL)->text('basic')
                ->set('offline_msg', Language::$word->CG_SMTP_SSL)->text('basic')
                ->set('analytics', Language::$word->CG_SMTP_SSL)->string()
                ->set('ytapi', Language::$word->CG_SMTP_SSL)->string()
                ->set('mapapi', Language::$word->CG_SMTP_SSL)->string()
                ->set('sendmail', Language::$word->CG_SMAILPATH)->path();
            
            $validate
                ->set('smtp_host', Language::$word->CG_SMTP_HOST)->string()
                ->set('smtp_user', Language::$word->CG_SMTP_USER)->string()
                ->set('smtp_pass', Language::$word->CG_SMTP_PASS)->string()
                ->set('smtp_port', Language::$word->CG_SMTP_PORT)->numeric();
            
            switch ($_POST['mailer']) {
                case 'SMTP':
                    $validate
                        ->set('smtp_host', Language::$word->CG_SMTP_HOST)->required()->string()
                        ->set('smtp_user', Language::$word->CG_SMTP_USER)->required()->string()
                        ->set('smtp_pass', Language::$word->CG_SMTP_PASS)->required()->string()
                        ->set('smtp_port', Language::$word->CG_SMTP_PORT)->numeric();
                    break;
                
                case 'SMAIL':
                    $validate->set('sendmail', Language::$word->CG_SMAILPATH)->required()->string();
                    break;
            }
            $safe = $validate->safe();
            
            $logo = File::upload('logo', 3145728, 'png,jpg,svg');
            $plogo = File::upload('plogo', 3145728, 'png,jpg,svg');
            
            if (count(Message::$msgs) === 0) {
                $smedia['facebook'] = $safe->facebook;
                $smedia['twitter'] = $safe->twitter;
                
                $data = array(
                    'site_name' => $safe->site_name,
                    'company' => $safe->company,
                    'site_email' => $safe->site_email,
                    'site_dir' => $safe->site_dir,
                    'theme' => $safe->theme,
                    'perpage' => $safe->perpage,
                    'thumb_w' => $safe->thumb_w,
                    'thumb_h' => $safe->thumb_h,
                    'img_w' => $safe->img_w,
                    'img_h' => $safe->img_h,
                    'avatar_w' => $safe->avatar_w,
                    'avatar_h' => $safe->avatar_h,
                    'long_date' => $safe->long_date,
                    'short_date' => $safe->short_date,
                    'calendar_date' => $safe->calendar_date,
                    'time_format' => $safe->time_format,
                    'weekstart' => $safe->weekstart,
                    'lang' => $safe->lang,
                    'dtz' => $safe->dtz,
                    'locale' => $safe->locale,
                    'ploader' => $safe->ploader,
                    'eucookie' => $safe->eucookie,
                    'offline' => $safe->offline,
                    'offline_msg' => $safe->offline_msg,
                    'offline_d' => $this->db->toDate($safe->offline_d_submit),
                    'offline_t' => $safe->offline_t,
                    'offline_info' => $safe->offline_info,
                    'showlang' => $safe->showlang,
                    'showlogin' => $safe->showlogin,
                    'showsearch' => $safe->showsearch,
                    'showcrumbs' => $safe->showcrumbs,
                    'currency' => $safe->currency,
                    'tax_rate' => $safe->tax_rate,
                    'enable_tax' => $safe->enable_tax,
                    'file_size' => ($safe->file_size * pow(1024, 2)),
                    'file_ext' => $safe->file_ext,
                    'reg_verify' => $safe->reg_verify,
                    'auto_verify' => $safe->auto_verify,
                    'notify_admin' => $safe->notify_admin,
                    'flood' => ($safe->flood * 60),
                    'attempt' => $safe->attempt,
                    'logging' => $safe->logging,
                    'one_login' => $safe->one_login,
                    'analytics' => $safe->analytics,
                    'mailer' => $safe->mailer,
                    'sendmail' => $safe->sendmail,
                    'smtp_host' => $safe->smtp_host,
                    'smtp_user' => $safe->smtp_user,
                    'smtp_pass' => $safe->smtp_pass,
                    'smtp_port' => $safe->smtp_port,
                    'is_ssl' => $safe->is_ssl,
                    'inv_info' => $safe->inv_info,
                    'inv_note' => $safe->inv_note,
                    'social_media' => json_encode($smedia),
                    'ytapi' => $safe->ytapi,
                    'mapapi' => $safe->mapapi,
                );
                
                if (array_key_exists('logo', $_FILES)) {
                    File::deleteFile(UPLOADS . $this->core->logo);
                    $result = File::process($logo, UPLOADS . '/', false, 'logo', false);
                    $data['logo'] = $result['fname'];
                }
                
                if (array_key_exists('plogo', $_FILES)) {
                    File::deleteFile(UPLOADS . $this->core->logo);
                    $result = File::process($plogo, UPLOADS . '/', false, 'print_logo', false);
                    $data['plogo'] = $result['fname'];
                }
                
                if (Validator::post('dellogo')) {
                    $data['logo'] = 'NULL';
                }
                if (Validator::post('dellogop')) {
                    $data['plogo'] = 'NULL';
                }
                
                $this->db->update(Core::sTable, $data)->where('id', 1, '=')->run();
                Message::msgReply($this->db->affected(), 'success', Language::$word->CG_UPDATED);
            } else {
                Message::msgSingleStatus();
            }
        }
    }