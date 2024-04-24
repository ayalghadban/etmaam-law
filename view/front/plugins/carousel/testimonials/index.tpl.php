<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 6/26/2023 9:21 AM Gewa Exp $
    *
    */

   use Wojo\Core\Core;
   use Wojo\Language\Language;
   use Wojo\Plugin\Carousel\Carousel;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

?>
<?php if ($row = Carousel::render($this->properties['plugin_id'])): ?>
   <div class="wojo carousel" data-wcarousel='<?php echo (in_array(Core::$language, array('he', 'ae', 'ir')))? str_replace('"rtl":false', '"rtl":true', $row->settings) : $row->settings; ?>'>
      <?php echo Url::out_url($row->{'body' . Language::$lang}); ?>
   </div>
<?php endif; ?>