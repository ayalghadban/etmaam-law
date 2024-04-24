<?php
   /**
    * loadDatabaseBackup
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: loadDatabaseBackup.tpl.php, v1.00 5/11/2023 11:44 AM Gewa Exp $
    *
    */

   use Wojo\File\File;
   use Wojo\Language\Language;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="item align-middle">
   <div class="content">
      <span class="text-size-small text-weight-700">-1.</span>
      <?php echo str_replace('.sql', '', $this->backup); ?></div>
   <div class="content auto"><span
        class="wojo small dark inverted label"><?php echo File::getFileSize($this->dbdir . $this->backup, 'kb', true); ?></span>
      <a href="<?php echo UPLOADURL . 'backups/' . $this->backup; ?>" data-content="<?php echo Language::$word->DOWNLOAD; ?>"
         class="wojo icon positive inverted circular button button">
         <i class="download icon"></i>
      </a>
      <a
        data-set='{"option":[{"restore": "restoreBackup","title": "<?php echo $this->backup; ?>","id":1}],"action":"restore","parent":".item"}'
        class="wojo icon primary inverted circular button data">
         <i class="icon time history"></i>
      </a>
      <a
        data-set='{"option":[{"delete": "deleteBackup","title": "<?php echo $this->backup; ?>","id":1}],"action":"delete","parent":".item"}'
        class="wojo icon negative inverted circular button data">
         <i class="icon trash"></i>
      </a>
   </div>
</div>