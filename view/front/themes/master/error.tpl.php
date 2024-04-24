<?php
   /**
    * error
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: error.tpl.php, v1.00 6/13/2023 9:33 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<main>
   <div class="flex justify-center align-middle flex-column width-full min-height-full padding-vertical">
      <div class="wojo info message"><?php echo $this->error; ?></div>
   </div>
</main>