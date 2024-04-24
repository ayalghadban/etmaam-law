<?php
   /**
    * header
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: header.tpl.php, v1.00 4/27/2023 11:29 PM Gewa Exp $
    *
    */

   use Wojo\Auth\Auth;
   use Wojo\Cache\Cache;
   use Wojo\Core\Core;
   use Wojo\Core\Session;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;
   use Wojo\Language\Language;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<!DOCTYPE html>
<head>
   <meta charset="utf-8">
   <title><?php echo $this->title; ?></title>
   <link href="<?php echo ADMINVIEW . 'cache/' . Cache::cssCache(array(
       'base.css', 'transition.css', 'label.css', 'form.css', 'dropdown.css', 'input.css', 'button.css', 'message.css', 'image.css', 'list.css', 'table.css', 'icon.css', 'flags.css', 'card.css', 'modal.css', 'editor.css', 'tooltip.css', 'menu.css', 'progress.css', 'utility.css', 'style.css'
     ), ADMINBASE); ?>?ver=<?php echo time(); ?>" rel="stylesheet" type="text/css"/>
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
   <meta name="apple-mobile-web-app-capable" content="yes">
   <script type="text/javascript" src="<?php echo SITEURL; ?>assets/jquery.js"></script>
   <script type="text/javascript" src="<?php echo SITEURL; ?>assets/global.js"></script>
</head>
<body>
<header class="main"<?php echo Session::getCookie('CMSA_USERBG')? ' style="background-image:url(' . ADMINVIEW . 'images/' . Session::getCookie('CMSA_USERBG') . '.jpg)"' : null; ?>>
   <div class="wojo-grid">
      <div class="row small-horizontal-gutters align-middle" id="mainRow">
         <div class="columns auto phone-order-1 mobile-order-1">
            <a href="<?php echo ADMINURL; ?>" class="logo">
               <?php echo ($this->core->logo)? '<img src="' . SITEURL . 'uploads/' . $this->core->logo . '" alt="' . $this->core->company . '">' : $this->core->company; ?></a>
         </div>
         <div class="columns phone-order-6 mobile-order-6">
            <nav class="wojo menu">
               <ul>
                  <li<?php echo Utility::isActiveMulti(['templates', 'menus', 'pages', 'languages', 'fields', 'coupons'], $this->segments);?>>
                     <a href="#"><?php echo Language::$word->ADM_CONTENT; ?></a>
                     <ul>
                        <?php if (Auth::hasPrivileges('manage_menus')): ?>
                           <li>
                              <a<?php echo Utility::isActive('menus', $this->segments);?> href="<?php echo Url::url('admin/menus'); ?>"><?php echo Language::$word->ADM_MENUS; ?></a>
                           </li>
                        <?php endif; ?>
                        <?php if (Auth::hasPrivileges('manage_pages')): ?>
                           <li>
                              <a<?php echo Utility::isActive('pages', $this->segments);?> href="<?php echo Url::url('admin/pages'); ?>"><?php echo Language::$word->ADM_PAGES; ?></a>
                           </li>
                        <?php endif; ?>
                        <?php if (Auth::hasPrivileges('manage_coupons')): ?>
                           <li>
                              <a<?php echo Utility::isActive('coupons', $this->segments);?> href="<?php echo Url::url('admin/coupons'); ?>"><?php echo Language::$word->ADM_COUPONS; ?></a>
                           </li>
                        <?php endif; ?>
                        <?php if (Auth::hasPrivileges('manage_languages')): ?>
                           <li>
                              <a<?php echo Utility::isActive('languages', $this->segments);?> href="<?php echo Url::url('admin/languages'); ?>"><?php echo Language::$word->ADM_LNGMNG; ?></a>
                           </li>
                        <?php endif; ?>
                        <?php if (Auth::hasPrivileges('manage_fields')): ?>
                           <li>
                              <a<?php echo Utility::isActive('fields', $this->segments);?> href="<?php echo Url::url('admin/fields'); ?>"><?php echo Language::$word->ADM_CFIELDS; ?></a>
                           </li>
                        <?php endif; ?>
                        <?php if (Auth::hasPrivileges('manage_email')): ?>
                           <li>
                              <a<?php echo Utility::isActive('templates', $this->segments);?> href="<?php echo Url::url('admin/templates'); ?>"><?php echo Language::$word->ADM_EMTPL; ?></a>
                           </li>
                        <?php endif; ?>
                     </ul>
                  </li>
                  <?php if (Auth::hasPrivileges('manage_users')): ?>
                     <li<?php echo Utility::isActive('users', $this->segments);?>>
                        <a href="<?php echo Url::url('admin/users'); ?>"><?php echo Language::$word->ADM_USERS; ?></a>
                     </li>
                  <?php endif; ?>
                  <?php if (Auth::hasPrivileges('manage_memberships')): ?>
                     <li<?php echo Utility::isActive('memberships', $this->segments);?>>
                        <a href="<?php echo Url::url('admin/memberships'); ?>"><?php echo Language::$word->ADM_MEMBS; ?></a>
                     </li>
                  <?php endif; ?>
                  <li<?php echo Utility::isActiveMulti(['backup', 'manager', 'mailer', 'countries', 'configuration'], $this->segments);?>>
                     <a href="#"><?php echo Language::$word->ADM_CONFIG; ?></a>
                     <ul>
                        <?php if (Auth::checkAcl('owner')): ?>
                           <li>
                              <a<?php echo Utility::isActive('configuration', $this->segments);?> href="<?php echo Url::url('admin/configuration'); ?>"><?php echo Language::$word->ADM_SYSTEM; ?></a>
                           </li>
                        <?php endif; ?>
                        <?php if (Auth::hasPrivileges('manage_backup')): ?>
                           <li>
                              <a<?php echo Utility::isActive('backup', $this->segments);?> href="<?php echo Url::url('admin/backup'); ?>"><?php echo Language::$word->ADM_BACKUP; ?></a>
                           </li>
                        <?php endif; ?>
                        <?php if (Auth::hasPrivileges('manage_files')): ?>
                           <li>
                              <a<?php echo Utility::isActive('manager', $this->segments);?> href="<?php echo Url::url('admin/manager'); ?>"><?php echo Language::$word->ADM_FM; ?></a>
                           </li>
                        <?php endif; ?>
                        <?php if (Auth::hasPrivileges('manage_newsletter')): ?>
                           <li>
                              <a<?php echo Utility::isActive('mailer', $this->segments);?> href="<?php echo Url::url('admin/mailer'); ?>"><?php echo Language::$word->ADM_NEWSL; ?></a>
                           </li>
                        <?php endif; ?>
                        <?php if (Auth::hasPrivileges('manage_countries')): ?>
                           <li>
                              <a<?php echo Utility::isActive('countries', $this->segments);?> href="<?php echo Url::url('admin/countries'); ?>"><?php echo Language::$word->ADM_CNTR; ?></a>
                           </li>
                        <?php endif; ?>
                     </ul>
                  </li>
               </ul>
            </nav>
         </div>
         <div class="columns auto phone-order-2 mobile-order-2">
            <div class="wojo buttons" data-wdropdown="#dropdown-uMenu" id="uName">
               <div class="wojo transparent button tablet-hide phone-hide"><?php echo $this->auth->name; ?></div>
               <div class="wojo transparent icon button is-alone"><?php echo Utility::getInitials($this->auth->name); ?></div>
            </div>
            <div class="wojo dropdown top-left" id="dropdown-uMenu">
               <div class="wojo small circular center image">
                  <img src="<?php echo UPLOADURL; ?>/avatars/<?php echo ($this->auth->avatar)?: 'default.svg'; ?>" alt="">
               </div>
               <h5 class="text-size-small dimmed-text center-align"><?php echo $this->auth->name; ?></h5>
               <a class="item" href="<?php echo Url::url('admin/account'); ?>"><i class="icon person"></i>
                  <?php echo Language::$word->M_MYACCOUNT; ?></a>
               <a class="item" href="<?php echo Url::url('admin/password'); ?>"><i class="icon lock"></i>
                  <?php echo Language::$word->M_SUB2; ?></a>
               <a class="item" href="https://ckb.wojoscripts.com" target="_blank"><i class="icon life preserver"></i>
                  <?php echo Language::$word->HELP; ?></a>

               <a class="phone-hide item image<?php echo Session::cookieExists('CMSA_USERBG', 'login')? ' active' : null; ?>"><img src="<?php echo ADMINVIEW; ?>/images/login.jpg" data-name="login" alt=""></a>
               <a class="phone-hide item image<?php echo Session::cookieExists('CMSA_USERBG', 'login2')? ' active' : null; ?>"><img src="<?php echo ADMINVIEW; ?>/images/login2.jpg" data-name="login2" alt=""></a>
               <a class="phone-hide item image<?php echo Session::cookieExists('CMSA_USERBG', 'login3')? ' active' : null; ?>"><img src="<?php echo ADMINVIEW; ?>/images/login3.jpg" data-name="login3" alt=""></a>
               <a class="phone-hide item image<?php echo Session::cookieExists('CMSA_USERBG', 'login4')? ' active' : null; ?>"><img src="<?php echo ADMINVIEW; ?>/images/login4.jpg" data-name="login4" alt=""></a>
               <div class="divider"></div>
               <a class="item" href="<?php echo Url::url('admin/logout'); ?>"><i class="icon power"></i>
                  <?php echo Language::$word->LOGOUT; ?></a>
            </div>
         </div>
         <div class="columns auto phone-order-3 mobile-order-3">
            <a data-wdropdown="#dropdown-eMenu" class="wojo transparent icon button">
               <i class="icon widgets"></i>
            </a>
            <div class="wojo dropdown menu top-<?php echo (in_array(Core::$language, array('sa', 'ae', 'ir')))? 'left' : 'right'; ?>" id="dropdown-eMenu">
               <?php if (Auth::hasPrivileges('manage_layout')): ?>
                  <a class="item" href="<?php echo Url::url('admin/layout'); ?>"><i class="icon window sidebar"></i><?php echo Language::$word->ADM_LAYOUT; ?></a>
               <?php endif; ?>
               <a class="item" href="<?php echo Url::url('admin/modules'); ?>"><i class="icon puzzle"></i><?php echo Language::$word->MODULES; ?></a>
               <a class="item" href="<?php echo Url::url('admin/plugins'); ?>"><i class="icon icons"></i><?php echo Language::$word->PLUGINS; ?></a>
            </div>
         </div>
         <?php if (Auth::checkAcl('owner')): ?>
            <div class="columns auto phone-order-4 mobile-order-4">
               <a data-wdropdown="#dropdown-aMenu" class="wojo transparent icon button">
                  <i class="icon gears"></i>
               </a>
               <div class="wojo dropdown menu top-<?php echo (in_array(Core::$language, array('sa', 'ae', 'ir')))? 'left' : 'right'; ?>" id="dropdown-aMenu">
                  <a class="item" href="<?php echo Url::url('admin/roles'); ?>"><i class="icon lock"></i>
                     <?php echo Language::$word->ADM_PERMS; ?></a>
                  <a class="item" href="<?php echo Url::url('admin/transactions'); ?>"><i class="icon wallet"></i>
                     <?php echo Language::$word->ADM_TRANS; ?></a>
                  <a class="item" href="<?php echo Url::url('admin/utilities'); ?>"><i class="icon sliders vertical alt"></i>
                     <?php echo Language::$word->ADM_UTIL; ?></a>
                  <a class="item" href="<?php echo Url::url('admin/system'); ?>"><i class="icon laptop"></i>
                     <?php echo Language::$word->SYS_TITLE; ?></a>
                  <a class="item" href="<?php echo Url::url('admin/gateways'); ?>"><i class="icon credit card"></i>
                     <?php echo Language::$word->ADM_GATE; ?></a>
                  <a class="item" href="<?php echo Url::url('admin/trash'); ?>"><i class="icon trash"></i>
                     <?php echo Language::$word->ADM_TRASH; ?></a>
               </div>
            </div>
         <?php endif; ?>
         <div class="columns auto phone-order-5 mobile-order-5">
            <button type="button" class="wojo icon white button mobile-button"><i class="icon list"></i></button>
         </div>
      </div>
   </div>
   <div class="toolbar">
      <div class="wojo-grid">
         <div class="wojo small breadcrumb">
            <i class="icon house"></i><?php echo Url::crumbs(($this->crumbs ?? $this->segments), '//', Language::$word->HOME); ?>
         </div>
         <?php if ($this->caption or $this->subtitle): ?>
            <div class="caption">
               <?php if ($this->caption): ?>
                  <h4><?php echo $this->caption; ?></h4>
               <?php endif; ?>
               <?php if ($this->subtitle): ?>
                  <p><?php echo $this->subtitle; ?></p>
               <?php endif; ?>
            </div>
         <?php endif; ?>
      </div>
      <div class="shape">
         <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 1920 100.1">
            <path fill="#ebecee" d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z"></path>
         </svg>
      </div>
   </div>
</header>
<main>
   <div class="wojo-grid">
      <div class="mainContainer">