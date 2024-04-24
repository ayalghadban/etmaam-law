<?php
   /**
    * dashboard
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: dashboard.tpl.php, v1.00 6/22/2023 1:08 PM Gewa Exp $
    *
    */

   use Wojo\File\File;
   use Wojo\Language\Language;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

?>
<main>
   <div class="bg-color-primary-inverted padding-big-top">
      <div class="wojo-grid">
         <div class="row gutters align-middle">
            <div class="columns auto phone-100">
               <input type="file" name="avatar" data-process="true" data-action="avatar" data-class="rounded small" data-type="image" data-exist="<?php echo UPLOADURL . 'avatars/' . ($this->auth->avatar)?: 'default.svg'; ?>" accept="image/png, image/jpeg">
            </div>
            <div class="columns">
               <h5><?php echo Language::$word->WELCOMEBACK; ?>
                  <span class="text-color-primary"><?php echo $this->auth->name; ?>! </span>
               </h5>
               <p>
                  <?php echo $this->auth->email; ?>
               </p>
            </div>
         </div>
         <div class="row gutters align-middle">
            <div class="columns mobile-100 phone-100">
               <div class="wojo navs">
                  <ul class="nav">
                     <li class="<?php echo count($this->segments) == 1? 'active' : 'normal'; ?>">
                        <a href="<?php echo Url::url($this->url); ?>">
                           <i class="icon box"></i><?php echo Language::$word->ADM_MEMBS; ?>
                        </a>
                     </li>
                     <li class="<?php echo (in_array('history', $this->segments))? 'active' : 'normal'; ?>">
                        <a href="<?php echo Url::url($this->url, 'history'); ?>">
                           <i class="icon time history"></i><?php echo Language::$word->HISTORY; ?>
                        </a>
                     </li>
                     <li class="<?php echo (in_array('settings', $this->segments))? 'active' : 'normal'; ?>">
                        <a href="<?php echo Url::url($this->url, 'settings'); ?>">
                           <i class="icon  person lines"></i><?php echo Language::$word->SETTINGS; ?>
                        </a>
                     </li>
                     <?php if (File::is_File(FMODPATH . 'digishop/_dashboard.tpl.php')): ?>
                        <li class="<?php echo (in_array('digishop', $this->segments))? 'active' : 'normal'; ?>">
                           <a href="<?php echo Url::url($this->url, 'digishop'); ?>">
                              <i class="icon download"></i><?php echo Language::$word->DOWNLOADS; ?>
                           </a>
                        </li>
                     <?php endif; ?>
                     <?php if (File::is_File(FMODPATH . 'shop/_dashboard.tpl.php')): ?>
                        <li class="<?php echo (in_array('shop', $this->segments))? 'active' : 'normal'; ?>">
                           <a href="<?php echo Url::url($this->url, 'shop'); ?>">
                              <i class="icon bag"></i><?php echo Language::$word->_MOD_SP_SUB13; ?>
                           </a>
                        </li>
                     <?php endif; ?>
                  </ul>
               </div>
            </div>
            <div class="columns auto mobile-100 phone-100">
               <a class="wojo small primary fluid button" href="<?php echo Url::url('logout'); ?>">
                  <i class="icon power"></i><?php echo Language::$word->LOGOUT; ?>
               </a>
            </div>
         </div>
      </div>
   </div>
   <?php switch (Url::segment($this->segments, 1)): case 'history': ?>
      <?php include_once THEMEBASE . '_history.tpl.php'; ?>
      <?php break; ?>
   <?php case 'settings': ?>
      <?php include_once THEMEBASE . '_settings.tpl.php'; ?>
      <?php break; ?>
   <?php case 'validate': ?>
      <?php include_once THEMEBASE . '_validate.tpl.php'; ?>
      <?php break; ?>
   <?php case 'digishop': ?>
      <?php if (File::is_File(FMODPATH . 'digishop/_dashboard.tpl.php')): ?>
         <?php include_once BASEPATH . 'view/front/modules/digishop/_dashboard.tpl.php'; ?>
      <?php endif; ?>
      <?php break; ?>
   <?php case 'shop': ?>
      <?php if (File::is_File(FMODPATH . 'shop/_dashboard.tpl.php')): ?>
         <?php include_once BASEPATH . 'view/front/modules/shop/_dashboard.tpl.php'; ?>
      <?php endif; ?>
      <?php break; ?>
   <?php default: ?>
      <?php include_once THEMEBASE . '_memberships.tpl.php'; ?>
      <?php break; ?>
   <?php endswitch; ?>
</main>
