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
<div id="notFound">
   <div>
      <h1>404</h1>
      <h4><?php echo Language::$word->META_ERROR1; ?></h4>
   </div>
   <img src="<?php echo ADMINVIEW; ?>images/404.svg" alt="">
</div>
