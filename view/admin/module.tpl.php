<?php
   /**
    * module
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: module.tpl.php, v1.00 5/12/2023 7:53 PM Gewa Exp $
    *
    */

   use Wojo\Auth\Auth;
   use Wojo\Core\Router;
   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   if (!Auth::hasPrivileges('manage_modules')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<?php switch (Url::segment($this->segments)): case 'edit': ?>
   <!-- Start edit -->
   <form method="post" id="wojo_form" name="wojo_form">
      <div class="wojo form">
         <div class="wojo lang tabs">
            <ul class="nav">
               <?php foreach ($this->langlist as $lang): ?>
                  <li<?php echo ($lang->abbr == $this->core->lang)? ' class="active"' : null; ?>>
                     <a class="lang-color <?php echo Utility::colorToWord($lang->color); ?>"
                        data-tab="lang_<?php echo $lang->abbr; ?>"><span
                          class="flag icon <?php echo $lang->abbr; ?>"></span><?php echo $lang->name; ?></a>
                  </li>
               <?php endforeach; ?>
            </ul>
            <div class="tab gutters">
               <?php foreach ($this->langlist as $lang): ?>
                  <div data-tab="lang_<?php echo $lang->abbr; ?>" class="item">
                     <div class="wojo fields">
                        <div class="field">
                           <label><?php echo Language::$word->NAME; ?>
                              <small><?php echo $lang->abbr; ?></small>
                              <i class="icon asterisk"></i>
                           </label>
                           <div class="wojo huge fluid input">
                              <input type="text" placeholder="<?php echo Language::$word->NAME; ?>"
                                     value="<?php echo $this->data->{'title_' . $lang->abbr}; ?>"
                                     name="title_<?php echo $lang->abbr ?>">
                           </div>
                        </div>
                        <div class="field">
                           <label><?php echo Language::$word->DESCRIPTION; ?>
                              <small><?php echo $lang->abbr; ?></small>
                           </label>
                           <div class="wojo huge fluid input">
                              <input type="text" placeholder="<?php echo Language::$word->DESCRIPTION; ?>"
                                     value="<?php echo $this->data->{'info_' . $lang->abbr}; ?>"
                                     name="info_<?php echo $lang->abbr; ?>">
                           </div>
                        </div>
                     </div>
                     <div class="wojo fields">
                        <div class="field">
                           <label><?php echo Language::$word->METAKEYS; ?>
                              <small><?php echo $lang->abbr; ?></small>
                           </label>
                           <textarea class="small" placeholder="<?php echo Language::$word->METAKEYS; ?>"
                                     name="keywords_<?php echo $lang->abbr; ?>"><?php echo $this->data->{'keywords_' . $lang->abbr}; ?></textarea>
                        </div>
                        <div class="field">
                           <label><?php echo Language::$word->METADESC; ?>
                              <small><?php echo $lang->abbr; ?></small>
                           </label>
                           <textarea class="small" placeholder="<?php echo Language::$word->METADESC; ?>"
                                     name="description_<?php echo $lang->abbr; ?>"><?php echo $this->data->{'description_' . $lang->abbr}; ?></textarea>
                        </div>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('admin/modules'); ?>"
            class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-route="admin/modules/action/" data-action="update" name="dosubmit"
                 class="wojo primary button"><?php echo Language::$word->MDL_SUB1; ?></button>
      </div>
      <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
   </form>
   <?php break; ?>
<?php default: ?>
   <!-- Start default -->
   <?php if (!$this->data): ?>
      <div class="center-align">
         <img src="<?php echo ADMINVIEW; ?>images/notfound.svg" alt="" class="wojo big inline image">
         <div class="margin-small-top">
            <p class="wojo small icon alert inverted attached compact message">
               <i class="icon exclamation triangle"></i><?php echo Language::$word->MDL_NOMOD; ?></p>
         </div>
      </div>
   <?php else: ?>
      <div class="row grid phone-1 mobile-1 tablet-2 screen-3 gutters">
         <?php foreach ($this->data as $row): ?>
            <?php if ($this->auth->checkModAcl($row->modalias)): ?>
               <div class="columns" id="item_<?php echo $row->id; ?>">
                  <div class="wojo simple segment">
                     <div class="center-align">
                        <img src="<?php echo $row->icon? AMODULEURL . $row->icon : SITEURL . 'assets/images/basic_plugin.svg'; ?>"
                             class="wojo normal inline image" alt="">
                        <h6 class="truncate margin-top margin-small-bottom"><?php echo $row->{'title' . Language::$lang}; ?></h6>
                     </div>
                     <div class="row justify-center">
                        <div class="columns">
                           <a href="<?php echo Url::url(Router::$path . '/edit', $row->id); ?>"
                              class="wojo icon inverted primary circular button">
                              <i class="icon pencil"></i>
                           </a>
                           <a data-set='{"option":[{"delete":"deleteModule","title": "<?php echo Validator::sanitize($row->{'title' . Language::$lang}, 'chars'); ?>","id":<?php echo $row->id; ?>}],"action":"delete","parent":"#item_<?php echo $row->id; ?>"}'
                              class="wojo icon inverted negative circular button data">
                              <i class="icon trash"></i>
                           </a>
                        </div>
                        <div class="columns auto">
                           <a href="<?php echo Url::url(Router::$path, $row->modalias); ?>"
                              class="wojo icon dark inverted circular button">
                              <i class="icon gears"></i>
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
            <?php endif; ?>
         <?php endforeach; ?>
      </div>
   <?php endif; ?>
   <?php break; ?>
<?php endswitch; ?>