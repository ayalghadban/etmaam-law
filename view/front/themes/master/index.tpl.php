<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 6/10/2023 1:24 PM Gewa Exp $
    *
    */

   use Wojo\Core\Content;
   use Wojo\Language\Language;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<main<?php echo Content::pageBg(); ?>>
   <!-- Validate page access-->
   <?php if (Content::validatePage()): ?>
      <!-- Run page-->
      <?php echo Content::parseContentData($this->data->{'body' . Language::$lang}); ?>
      <!-- Parse javascript -->
      <?php if ($this->data->jscode): ?>
         <script>
            <?php echo Validator::cleanOut(json_decode($this->data->jscode));?>
         </script>
      <?php endif; ?>
   <?php endif; ?>
</main>