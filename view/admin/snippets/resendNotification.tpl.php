<?php
   /**
    * resendNotification
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: resendNotification.tpl.php, v1.00 5/10/2023 2:37 PM Gewa Exp $
    *
    */

   use Wojo\Core\Filter;
   use Wojo\Language\Language;
   use Wojo\Message\Message;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   if (!$this->data) : Message::invalid('ID' . Filter::$id);
      return; endif;
?>
<div class="body">
   <div class="wojo small form">
      <form method="post" id="modal_form" name="modal_form">
         <p><?php echo str_replace('[NAME]', '<span class="text-weight-700">' . $this->data->email . '</span>', Language::$word->M_INFO4); ?></p>
      </form>
   </div>
</div>