<?php
   /**
    * 404
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: 404.tpl.php, v1.00 5/15/2023 9:17 AM Gewa Exp $
    *
    */

   use Wojo\Language\Language;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<main class="flex align-middle justify-center flex-column">
   <img class="wojo big image" src="<?php echo THEMEURL; ?>images/404.svg" alt="<?php echo Language::$word->META_ERROR1; ?>">
   <h1 class="margin-top">404</h1>
   <h5><?php echo Language::$word->META_ERROR1; ?></h5>
</main>