<?php
   /**
    * backup
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: backup.tpl.php, v1.00 5/11/2023 10:16 AM Gewa Exp $
    *
    */

   use Wojo\Auth\Auth;
   use Wojo\File\File;
   use Wojo\Language\Language;
   use Wojo\Message\Message;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   if (!Auth::hasPrivileges('manage_backup')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<div class="row gutters justify-end">
   <div class="columns auto mobile-100 phone-100">
      <a data-set='{"option":[{"action":"backup", "id":1}], "mode":"instant", "url":"backup/action/", "complete":"prepend", "parent":"#backupList"}'
         class="wojo small secondary fluid button action">
         <i class="icon plus alt"></i><?php echo Language::$word->DBM_ADD; ?>
      </a>
   </div>
</div>
<div class="wojo segment">
   <div class="wojo small divided responsive list" id="backupList">
      <?php if ($this->data): ?>
         <?php foreach ($this->data as $i => $row): ?>
            <?php $i++; ?>
            <?php $latest = ($row == $this->core->backup)? ' bg-color-alert-inverted' : null; ?>
            <div class="item align-middle<?php echo $latest; ?>">
               <div class="content">
                  <span class="text-size-small text-weight-500"><?php echo $i; ?>.</span>
                  <?php echo str_replace('.sql', '', $row); ?></div>
               <div class="content auto">
                  <span class="wojo small dark inverted label"><?php echo File::getFileSize($this->dbdir . $row, 'kb', true); ?></span>
                  <a href="<?php echo UPLOADURL . 'backups/' . $row; ?>"
                     data-tooltip="<?php echo Language::$word->DOWNLOAD; ?>"
                     class="wojo icon positive inverted circular button button">
                     <i class="download icon"></i>
                  </a>
                  <a data-set='{"option":[{"action": "restore","title": "<?php echo $row; ?>","id":1}],"url":"backup/action/","action":"restore","parent":".item"}'
                     data-tooltip="<?php echo Language::$word->RESTORE; ?>"
                     class="wojo icon primary inverted circular button data">
                     <i class="icon time history"></i>
                  </a>
                  <a data-set='{"option":[{"delete": "deleteBackup","title": "<?php echo $row; ?>","id":1}],"action":"delete","parent":".item"}'
                     class="wojo icon negative inverted circular button data">
                     <i class="icon trash"></i>
                  </a>
               </div>
            </div>
         <?php endforeach; ?>
         <?php unset($row); ?>
      <?php endif; ?>
   </div>
</div>