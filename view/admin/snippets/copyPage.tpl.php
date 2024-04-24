<?php
   /**
    * copyPage
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: copyPage.tpl.php, v1.00 5/7/2023 9:57 PM Gewa Exp $
    *
    */

   use Wojo\Language\Language;
   use Wojo\Utility\Utility;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="body">
   <div class="wojo small form">
      <form method="post" id="modal_form" name="modal_form">
         <div class="wojo lang tabs">
            <ul class="nav">
               <?php foreach ($this->core->langlist as $lang): ?>
                  <li<?php echo ($lang->abbr == $this->core->lang)? ' class="active"' : null; ?>>
                     <a class="lang-color <?php echo Utility::colorToWord($lang->color); ?>"
                        data-tab="lang_<?php echo $lang->abbr; ?>"><span
                          class="flag icon <?php echo $lang->abbr; ?>"></span><?php echo $lang->name; ?></a>
                  </li>
               <?php endforeach; ?>
            </ul>
            <div class="tab">
               <?php foreach ($this->core->langlist as $lang): ?>
                  <div data-tab="lang_<?php echo $lang->abbr; ?>" class="item">
                     <div class="wojo fields">
                        <div class="field basic">
                           <label class=""><?php echo Language::$word->PAG_NAME; ?><small><?php echo $lang->abbr; ?></small>
                              <i class="icon asterisk"></i></label>
                           <input type="text" placeholder="<?php echo Language::$word->NAME; ?>"
                                  name="title_<?php echo $lang->abbr ?>">
                        </div>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         </div>
      </form>
   </div>
</div>
<script>
   $('.wojo.tabs').wTabs();
</script>