<?php
   /**
    * configuration
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: configuration.tpl.php, v1.00 5/11/2023 9:58 AM Gewa Exp $
    *
    */

   use Wojo\Date\Date;
   use Wojo\File\File;
   use Wojo\Language\Language;
   use Wojo\Utility\Utility;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   $this->auth::checkOwner();
?>
<form method="post" id="wojo_form" name="wojo_form">
   <div class="wojo segment form">
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_SITENAME; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->CG_SITENAME; ?>" value="<?php echo $this->data->site_name; ?>" name="site_name">
         </div>
         <div class="field five wide">
            <label><?php echo Language::$word->CG_COMPANY; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->CG_COMPANY; ?>" value="<?php echo $this->data->company; ?>" name="company">
         </div>
      </div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_DIR; ?>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->CG_DIR; ?>" value="<?php echo $this->data->site_dir; ?>" name="site_dir">
         </div>
         <div class="field five wide">
            <label><?php echo Language::$word->CG_WEBEMAIL; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->CG_WEBEMAIL; ?>" value="<?php echo $this->data->site_email; ?>" name="site_email">
         </div>
      </div>
      <div class="wojo fields">
         <div class="field auto">
            <label><?php echo Language::$word->CG_LOGO; ?></label>
            <input type="file" name="logo" id="logo" class="filestyle" data-input="false" data-badge="true">
         </div>
         <div class="field">
            <label><?php echo Language::$word->CG_LOGODEL; ?></label>
            <div class="wojo toggle inline fitted checkbox">
               <input name="dellogo" type="checkbox" value="1" id="dellogo">
               <label for="dellogo"><?php echo Language::$word->YES; ?></label>
            </div>
         </div>
         <div class="field auto">
            <label><?php echo Language::$word->CG_LOGO1; ?></label>
            <input type="file" name="plogo" id="plogo" class="filestyle" data-input="false" data-badge="true">
         </div>
         <div class="field">
            <label><?php echo Language::$word->CG_LOGODEL; ?></label>
            <div class="wojo toggle inline fitted checkbox">
               <input name="dellogop" type="checkbox" value="1" id="dellogop">
               <label for="dellogop"><?php echo Language::$word->YES; ?></label>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_LONGDATE; ?>
               <i class="icon asterisk"></i>
            </label>
            <select name="long_date">
               <?php echo Date::getLongDate($this->data->long_date); ?>
            </select>
         </div>
         <div class="field three wide">
            <label><?php echo Language::$word->CG_SHORTDATE; ?>
               <i class="icon asterisk"></i>
            </label>
            <select name="short_date">
               <?php echo Date::getShortDate($this->data->short_date); ?>
            </select>
         </div>
         <div class="field two wide">
            <label><?php echo Language::$word->CG_TIMEFORMAT; ?>
               <i class="icon asterisk"></i>
            </label>
            <select name="time_format">
               <?php echo Date::getTimeFormat($this->data->time_format); ?>
            </select>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_WEEKSTART; ?></label>
            <select name="weekstart">
               <?php echo Date::weekList(true, true, $this->data->weekstart); ?>
            </select>
         </div>
         <div class="field three wide">
            <label><?php echo Language::$word->CG_LANG; ?></label>
            <select name="lang">
               <?php foreach ($this->data->langlist as $lang): ?>
                  <option value="<?php echo $lang->abbr; ?>" <?php echo Validator::getSelected($this->data->lang, $lang->abbr); ?>><?php echo $lang->name; ?></option>
               <?php endforeach; ?>
            </select>
         </div>
         <div class="field two wide">
            <label><?php echo Language::$word->CG_PERPAGE; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->CG_PERPAGE; ?>" value="<?php echo $this->data->perpage; ?>" name="perpage">
         </div>
      </div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_DTZ; ?></label>
            <select name="dtz">
               <?php echo Date::getTimezones(); ?>
            </select>
         </div>
         <div class="field three wide">
            <label><?php echo Language::$word->CG_LOCALES; ?></label>
            <select name="locale">
               <?php echo Date::localeList($this->data->locale); ?>
            </select>
         </div>
         <div class="field two wide">
            <label><?php echo Language::$word->CG_CALDATE; ?>
               <i class="icon asterisk"></i>
            </label>
            <select name="calendar_date">
               <?php echo Date::getCalendarDate($this->data->calendar_date); ?>
            </select>
         </div>
      </div>
      <div class="wojo auto very wide divider"></div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_CURRENCY; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->CG_CURRENCY; ?>" value="<?php echo $this->data->currency; ?>" name="currency">
         </div>
         <div class="field">
            <label><?php echo Language::$word->CG_ETAX; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="enable_tax" type="radio" value="1" id="enable_tax1" <?php echo Validator::getChecked($this->data->enable_tax, 1); ?>>
               <label for="enable_tax1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="enable_tax" type="radio" value="0" id="enable_tax0" <?php echo Validator::getChecked($this->data->enable_tax, 0); ?>>
               <label for="enable_tax0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->CG_ETAX_RATE; ?></label>
            <select name="tax_rate">
               <?php echo Utility::loopOptions($this->countries, 'vat', 'name', $this->data->tax_rate); ?>
            </select>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_THEME; ?></label>
            <select name="theme">
               <?php echo Utility::loopOptionsSimple(File::getThemes(FRONTBASE . 'themes'), $this->data->theme); ?>
            </select>
         </div>
         <div class="field">
            <label><?php echo Language::$word->CG_PLOADER; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="ploader" type="radio" value="1" id="ploader1" <?php echo Validator::getChecked($this->data->ploader, 1); ?>>
               <label for="ploader1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="ploader" type="radio" value="0" id="ploader0" <?php echo Validator::getChecked($this->data->ploader, 0); ?>>
               <label for="ploader0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->CG_EUCOOKIE; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="eucookie" type="radio" value="1" id="eucookie1" <?php echo Validator::getChecked($this->data->eucookie, 1); ?>>
               <label for="eucookie1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="eucookie" type="radio" value="0" id="eucookie0" <?php echo Validator::getChecked($this->data->eucookie, 0); ?>>
               <label for="eucookie0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_TWID; ?></label>
            <div class="wojo fluid right icon input">
               <input type="text" placeholder="<?php echo Language::$word->CG_TWID; ?>" value="<?php echo $this->data->social->twitter; ?>" name="twitter">
               <i class="icon x_twitter"></i>
            </div>
         </div>
         <div class="field five wide">
            <label><?php echo Language::$word->CG_FBID; ?></label>
            <div class="wojo fluid right icon input">
               <input type="text" placeholder="<?php echo Language::$word->CG_FBID; ?>" value="<?php echo $this->data->social->facebook; ?>" name="facebook">
               <i class="icon facebook"></i>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_YTKEY; ?></label>
            <div class="wojo fluid right icon input">
               <input type="text" placeholder="<?php echo Language::$word->CG_YTKEY; ?>" value="<?php echo $this->data->ytapi; ?>" name="ytapi">
               <i class="icon youtube"></i>
            </div>
         </div>
         <div class="field five wide">
            <label><?php echo Language::$word->CG_GMAPKEY; ?></label>
            <div class="wojo fluid right icon input">
               <input type="text" placeholder="<?php echo Language::$word->CG_GMAPKEY; ?>" value="<?php echo $this->data->mapapi; ?>" name="mapapi">
               <i class="icon map"></i>
            </div>
         </div>
      </div>
      <div class="wojo auto very wide divider"></div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->CG_REGVERIFY; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="reg_verify" type="radio" value="1" id="reg_verify_1" <?php echo Validator::getChecked($this->data->reg_verify, 1); ?>>
               <label for="reg_verify_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="reg_verify" type="radio" value="0" id="reg_verify_0" <?php echo Validator::getChecked($this->data->reg_verify, 0); ?>>
               <label for="reg_verify_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->CG_AUTOVERIFY; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="auto_verify" type="radio" value="1" id="auto_verify_1" <?php echo Validator::getChecked($this->data->auto_verify, 1); ?>>
               <label for="auto_verify_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="auto_verify" type="radio" value="0" id="auto_verify_0" <?php echo Validator::getChecked($this->data->auto_verify, 0); ?>>
               <label for="auto_verify_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->CG_NOTIFY_ADMIN; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="notify_admin" type="radio" value="1" id="notify_admin_1" <?php echo Validator::getChecked($this->data->notify_admin, 1); ?>>
               <label for="notify_admin_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="notify_admin" type="radio" value="0" id="notify_admin_0" <?php echo Validator::getChecked($this->data->notify_admin, 0); ?>>
               <label for="notify_admin_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->CG_LOGIN_ATTEMPT; ?></label>
            <input type="text" placeholder="<?php echo Language::$word->CG_LOGIN_ATTEMPT; ?>" value="<?php echo $this->data->attempt; ?>" name="attempt">
         </div>
         <div class="field">
            <label><?php echo Language::$word->CG_LOGIN_TIME; ?></label>
            <div class="wojo right labeled input">
               <input type="text" placeholder="<?php echo Language::$word->CG_LOGIN_TIME; ?>" value="<?php echo($this->data->flood / 60); ?>" name="flood">
               <span class="wojo simple label">min.</span>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->CG_LOG_ON; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="logging" type="radio" value="1" id="logging_1" <?php echo Validator::getChecked($this->data->logging, 1); ?>>
               <label for="logging_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="logging" type="radio" value="0" id="logging_0" <?php echo Validator::getChecked($this->data->logging, 0); ?>>
               <label for="logging_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->CG_LOGIN; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="one_login" type="radio" value="1" id="one_login_1" <?php echo Validator::getChecked($this->data->one_login, 1); ?>>
               <label for="one_login_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="one_login" type="radio" value="0" id="one_login_0" <?php echo Validator::getChecked($this->data->one_login, 0); ?>>
               <label for="one_login_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->CG_LOGIN_SHOW; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="showlogin" type="radio" value="1" id="showlogin_1" <?php echo Validator::getChecked($this->data->showlogin, 1); ?>>
               <label for="showlogin_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="showlogin" type="radio" value="0" id="showlogin_0" <?php echo Validator::getChecked($this->data->showlogin, 0); ?>>
               <label for="showlogin_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->CG_SEARCH_SHOW; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="showsearch" type="radio" value="1" id="showsearch_1" <?php echo Validator::getChecked($this->data->showsearch, 1); ?>>
               <label for="showsearch_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="showsearch" type="radio" value="0" id="showsearch_0" <?php echo Validator::getChecked($this->data->showsearch, 0); ?>>
               <label for="showsearch_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->CG_CRUMBS_SHOW; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="showcrumbs" type="radio" value="1" id="showcrumbs_1" <?php echo Validator::getChecked($this->data->showcrumbs, 1); ?>>
               <label for="showcrumbs_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="showcrumbs" type="radio" value="0" id="showcrumbs_0" <?php echo Validator::getChecked($this->data->showcrumbs, 0); ?>>
               <label for="showcrumbs_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->CG_LANG_SHOW; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="showlang" type="radio" value="1" id="showlang_1" <?php echo Validator::getChecked($this->data->showlang, 1); ?>>
               <label for="showlang_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="showlang" type="radio" value="0" id="showlang_0" <?php echo Validator::getChecked($this->data->showlang, 0); ?>>
               <label for="showlang_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
      </div>
      <div class="wojo auto very wide divider"></div>
      <div class="wojo fields">
         <div class="five wide field">
            <label><?php echo Language::$word->CG_OFFLINE_M; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="offline" type="radio" value="1" id="offline_1" <?php echo Validator::getChecked($this->data->offline, 1); ?>>
               <label for="offline_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="offline" type="radio" value="0" id="offline_0" <?php echo Validator::getChecked($this->data->offline, 0); ?>>
               <label for="offline_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="five wide field">
            <label><?php echo Language::$word->CG_OFFLINE_DT; ?></label>
            <div class="wojo icon input">
               <input name="offline_d" type="text" placeholder="<?php echo Language::$word->CG_OFFLINE_DT; ?>" value="<?php echo Date::doDate('calendar', $this->data->offline_d); ?>" readonly class="datepick">
               <i class="icon date"></i>
               <input name="offline_t" type="text" placeholder="<?php echo Language::$word->CG_OFFLINE_DT; ?>" value="<?php echo $this->data->offline_t; ?>" readonly class="timepick">
               <i class="icon clock"></i>
            </div>
         </div>
      </div>
      <div class="field">
         <label><?php echo Language::$word->CG_OFFLINE_MS; ?></label>
         <textarea class="altpost" name="offline_msg"><?php echo $this->data->offline_msg; ?></textarea>
      </div>
   </div>
   <div class="wojo segment form margin-top">
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_AV_WH; ?>
               <i class="icon asterisk"></i>
            </label>
            <div class="wojo labeled input">
               <input type="text" placeholder="<?php echo Language::$word->CG_AV_WH; ?>" value="<?php echo $this->data->avatar_w; ?>" name="avatar_w">
               <span class="wojo simple label">px</span>
               <input type="text" placeholder="<?php echo Language::$word->CG_AV_WH; ?>" value="<?php echo $this->data->avatar_h; ?>" name="avatar_h">
               <span class="wojo simple label">px</span>
            </div>
         </div>
         <div class="field five wide">
            <label><?php echo Language::$word->CG_FILETYPE; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->CG_FILETYPE; ?>" value="<?php echo $this->data->file_ext; ?>" name="file_ext">
         </div>
      </div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_IM_WH; ?>
               <i class="icon asterisk"></i>
            </label>
            <div class="wojo  labeled input">
               <input type="text" placeholder="<?php echo Language::$word->CG_IM_WH; ?>" value="<?php echo $this->data->img_w; ?>" name="img_w">
               <span class="wojo simple label">px</span>
               <input type="text" placeholder="<?php echo Language::$word->CG_IM_WH; ?>" value="<?php echo $this->data->img_h; ?>" name="img_h">
               <span class="wojo simple label">px</span>
            </div>
         </div>
         <div class="field five wide">
            <label><?php echo Language::$word->CG_FILESIZE; ?>
               <i class="icon asterisk"></i>
            </label>
            <div class="wojo labeled input">
               <input type="text" placeholder="<?php echo Language::$word->CG_FILESIZE; ?>" value="<?php echo($this->data->file_size / (1024 * 1024)); ?>" name="file_size">
               <span class="wojo simple label">mb</span>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_TH_WH; ?>
               <i class="icon asterisk"></i>
            </label>
            <div class="wojo labeled input">
               <input type="text" placeholder="<?php echo Language::$word->CG_TH_WH; ?>" value="<?php echo $this->data->thumb_w; ?>" name="thumb_w">
               <span class="wojo simple label">px</span>
               <input type="text" placeholder="<?php echo Language::$word->CG_TH_WH; ?>" value="<?php echo $this->data->thumb_h; ?>" name="thumb_h">
               <span class="wojo simple label">px</span>
            </div>
         </div>
      </div>
      <div class="wojo auto very wide divider"></div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_INVDATA; ?>
            </label>
            <textarea class="altpost" name="inv_info"><?php echo $this->data->inv_info; ?></textarea>
         </div>
         <div class="field five wide">
            <label><?php echo Language::$word->CG_INVNOTE; ?>
            </label>
            <textarea class="altpost" name="inv_note"><?php echo $this->data->inv_note; ?></textarea>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->CG_OFFLINE; ?></label>
            <textarea class="altpost" name="offline_info"><?php echo $this->data->offline_info; ?></textarea>
         </div>
      </div>
      <div class="wojo fields">
         <div class="five wide basic field">
            <label><?php echo Language::$word->CG_GA; ?></label>
            <input type="text" placeholder="<?php echo Language::$word->CG_GA; ?>" value="<?php echo $this->data->analytics; ?>" name="analytics">
         </div>
      </div>
   </div>
   <div class="wojo segment form margin-vertical">
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_MAILER; ?></label>
            <select name="mailer" id="mailerchange">
               <option value="SMAIL" <?php echo Validator::getSelected($this->data->mailer, 'SMAIL'); ?>>Sendmail</option>
               <option value="SMTP" <?php echo Validator::getSelected($this->data->mailer, 'SMTP'); ?>>SMTP Mailer</option>
            </select>
         </div>
         <div class="field showsmail<?php echo ($this->core->mailer == 'SMAIL')? '' : ' hide-all'; ?>">
            <label><?php echo Language::$word->CG_SMAILPATH; ?></label>
            <input type="text" placeholder="<?php echo Language::$word->CG_SMAILPATH; ?>" value="<?php echo $this->data->sendmail; ?>" name="sendmail">
         </div>
      </div>
      <div class="showsmtp<?php echo ($this->core->mailer == 'SMTP')? '' : ' hide-all'; ?>">
         <div class="wojo fields">
            <div class="field five wide">
               <label><?php echo Language::$word->CG_SMTP_HOST; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input type="text" placeholder="<?php echo Language::$word->CG_SMTP_HOST; ?>" value="<?php echo $this->data->smtp_host; ?>" name="smtp_host">
            </div>
            <div class="field five wide">
               <label><?php echo Language::$word->CG_SMTP_USER; ?></label>
               <input type="text" placeholder="<?php echo Language::$word->CG_SMTP_USER; ?>" value="<?php echo $this->data->smtp_user; ?>" name="smtp_user">
            </div>
         </div>
         <div class="wojo fields">
            <div class="field three wide basic">
               <label><?php echo Language::$word->CG_SMTP_PASS; ?></label>
               <input type="text" placeholder="<?php echo Language::$word->CG_SMTP_PASS; ?>" value="<?php echo $this->data->smtp_pass; ?>" name="smtp_pass">
            </div>
            <div class="field three wide basic">
               <label><?php echo Language::$word->CG_SMTP_PORT; ?></label>
               <input type="text" placeholder="<?php echo Language::$word->CG_SMTP_PORT; ?>" value="<?php echo $this->data->smtp_port; ?>" name="smtp_port">
            </div>
            <div class="field four wide basic">
               <label><?php echo Language::$word->CG_SMTP_SSL; ?></label>
               <div class="wojo checkbox small radio fitted toggle inline">
                  <input name="is_ssl" type="radio" value="1" id="is_ssl_1" <?php echo Validator::getChecked($this->data->is_ssl, 1); ?>>
                  <label for="is_ssl_1">SSL</label>
               </div>
               <div class="wojo checkbox small radio fitted toggle inline">
                  <input name="is_ssl" type="radio" value="0" id="is_ssl_0" <?php echo Validator::getChecked($this->data->is_ssl, 0); ?>>
                  <label for="is_ssl_0">TLS</label>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="center-align">
      <button type="button" data-route="admin/configuration/action/" data-action="update" name="dosubmit" class="wojo primary button"><?php echo Language::$word->CG_UPDATE; ?></button>
   </div>
</form>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $('#mailerchange').change(function () {
         let val = $('#mailerchange option:selected').val();
         if (val === 'SMTP') {
            $('.showsmtp').show();
            $('.showsmail').hide();
         } else {
            $('.showsmtp').hide();
            $('.showsmail').show();
         }
      });
   });
   // ]]>
</script>