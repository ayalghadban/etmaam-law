<?php
   /**
    * system
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: system.tpl.php, v1.00 4/30/2023 10:12 AM Gewa Exp $
    *
    */

   use Wojo\Database\Database;
   use Wojo\File\File;
   use Wojo\Language\Language;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   $this->auth::checkOwner();
?>
<div class="wojo tabs">
   <ul class="nav">
      <li class="active"><a data-tab="cms"><i class="icon wojologo alt"></i>
            <?php echo Language::$word->SYS_CMS_INF; ?></a>
      </li>
      <li><a data-tab="php"><i class="icon code"></i>
            <?php echo Language::$word->SYS_PHP_INF; ?></a>
      </li>
      <li><a data-tab="server"><i class="icon display"></i>
            <?php echo Language::$word->SYS_SER_INF; ?></a>
      </li>
      <li><a data-tab="dbtables"><i class="icon server"></i>
            <?php echo Language::$word->SYS_DBTABLE_INF; ?><span data-tooltip="<?php echo Language::$word->SYS_DBOPTIMIZE; ?>" data-set='{"option":[{"action":"optimize", "id":1}], "url":"system/", "complete":"replace", "parent":"#dbtables", "mode":"instant"}' class="action"><i class="icon speedometer"></i></span></a>
      </li>
   </ul>
   <div class="wojo simple segment tab">
      <div data-tab="cms" class="item">
         <table class="wojo two basic column table">
            <thead>
            <tr>
               <th colspan="2"><?php echo Language::$word->SYS_CMS_INF; ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
               <td><?php echo Language::$word->SYS_CMS_VER; ?>:</td>
               <td>v<?php echo $this->core->wojov; ?></td>
            </tr>
            <tr>
               <td><?php echo Language::$word->SYS_ROOT_URL; ?>:</td>
               <td><?php echo SITEURL; ?></td>
            </tr>
            <tr>
               <td><?php echo Language::$word->SYS_ROOT_PATH; ?>:</td>
               <td><?php echo BASEPATH; ?></td>
            </tr>
            <tr>
               <td><?php echo Language::$word->SYS_UPL_URL; ?>:</td>
               <td><?php echo UPLOADURL; ?></td>
            </tr>
            <tr>
               <td><?php echo Language::$word->SYS_UPL_PATH; ?>:</td>
               <td><?php echo UPLOADS; ?></td>
            </tr>
            <tr>
               <td><?php echo Language::$word->SYS_ADMVIEW; ?>:</td>
               <td><?php echo ADMINBASE; ?></td>
            </tr>
            <tr>
               <td><?php echo Language::$word->SYS_DEF_LANG; ?>:</td>
               <td><?php echo strtoupper($this->core->lang); ?></td>
            </tr>
            </tbody>
         </table>
      </div>
      <div data-tab="php" class="item hide-all">
         <table class="wojo two basic column table">
            <thead>
            <tr>
               <th colspan="2"><?php echo Language::$word->SYS_PHP_INF; ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
               <td><?php echo Language::$word->SYS_PHP_VER; ?>:</td>
               <td><?php echo phpversion(); ?></td>
            </tr>
            <tr>
               <?php $gdinfo = gd_info(); ?>
               <td><?php echo Language::$word->SYS_GD_VER; ?>:</td>
               <td><?php echo $gdinfo['GD Version']; ?></td>
            </tr>
            <tr>
               <td><?php echo Language::$word->SYS_MQR; ?>:</td>
               <td><?php echo (ini_get('magic_quotes_gpc'))? Language::$word->ON : Language::$word->OFF; ?></td>
            </tr>
            <tr>
               <td><?php echo Language::$word->SYS_LOG_ERR; ?>:</td>
               <td><?php echo (ini_get('log_errors'))? Language::$word->ON : Language::$word->OFF; ?></td>
            </tr>
            <tr>
               <td><?php echo Language::$word->SYS_MEM_LIM; ?>:</td>
               <td><?php echo ini_get('memory_limit'); ?></td>
            </tr>
            <tr>
               <td><?php echo Language::$word->SYS_RG; ?>:</td>
               <td><?php echo (ini_get('register_globals'))? Language::$word->ON : Language::$word->OFF; ?></td>
            </tr>
            <tr>
               <td><?php echo Language::$word->SYS_SM; ?>:</td>
               <td><?php echo (ini_get('safe_mode'))? Language::$word->ON : Language::$word->OFF; ?></td>
            </tr>
            <tr>
               <td>Mb String:</td>
               <td><?php echo (extension_loaded('mbstring'))? Language::$word->ON : Language::$word->OFF; ?></td>
            </tr>
            <tr>
               <td>Intl:</td>
               <td><?php echo (extension_loaded('intl'))? Language::$word->ON : Language::$word->OFF; ?></td>
            </tr>
            <tr>
               <td>cURL:</td>
               <td><?php echo (extension_loaded('curl'))? Language::$word->ON : Language::$word->OFF; ?></td>
            </tr>
            <tr>
               <td><?php echo Language::$word->SYS_UMF; ?>:</td>
               <td><?php echo ini_get('upload_max_filesize'); ?></td>
            </tr>
            <tr>
               <td><?php echo Language::$word->SYS_PMF; ?>:</td>
               <td><?php echo ini_get('post_max_size'); ?></td>
            </tr>
            <tr>
               <td><?php echo Language::$word->SYS_SSP; ?>:</td>
               <td><?php echo ini_get('session.save_path'); ?></td>
            </tr>
            </tbody>
         </table>
      </div>
      <div data-tab="server" class="item hide-all">
         <table class="wojo two basic column table">
            <thead>
            <tr>
               <th colspan="2"><?php echo Language::$word->SYS_SER_INF; ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
               <td><?php echo Language::$word->SYS_SER_OS; ?>:</td>
               <td><?php echo php_uname('s') . ' (' . php_uname('r') . ')'; ?></td>
            </tr>
            <tr>
               <td><?php echo Language::$word->SYS_SER_API; ?>:</td>
               <td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
            </tr>
            <tr>
               <td><?php echo Language::$word->SYS_SER_DB; ?>:</td>
               <td><?php echo Database::Go()->getAttribute(PDO::ATTR_CLIENT_VERSION); ?></td>
            </tr>
            <tr>
               <td><?php echo Language::$word->SYS_DBV; ?>:</td>
               <td><?php echo Database::Go()->getAttribute(PDO::ATTR_SERVER_VERSION); ?></td>
            </tr>
            <tr>
               <td><?php echo Language::$word->SYS_MEMALO; ?>:</td>
               <td><?php echo ini_get('memory_limit'); ?></td>
            </tr>
            <tr>
               <td><?php echo Language::$word->SYS_STS; ?>:</td>
               <td><?php echo File::getSize(disk_free_space('.')); ?></td>
            </tr>
            </tbody>
         </table>
      </div>
      <div id="dbtables" data-tab="dbtables" class="item hide-all">
         <table class="wojo basic responsive table">
            <thead>
            <tr>
               <th colspan="2"><?php echo Language::$word->SYS_DBREPAIRING; ?></th>
               <th colspan="2"><?php echo Language::$word->SYS_DBOPTIMIZING; ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($this->data as $row): ?>
               <tr>
                  <td><?php echo $row; ?></td>
                  <td>
                     <span class="icon-text right"><?php echo Language::$word->SYS_DBSTATUS; ?></span>
                  </td>
                  <td><?php echo $row; ?></td>
                  <td>
                     <span class="icon-text right"><?php echo Language::$word->SYS_DBSTATUS; ?> </span>
                  </td>
               </tr>
            <?php endforeach; ?>
            </tbody>
         </table>
      </div>
   </div>
</div>