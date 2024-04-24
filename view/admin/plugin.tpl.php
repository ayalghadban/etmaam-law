<?php
   /**
    * plugin
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: plugin.tpl.php, v1.00 5/12/2023 7:18 PM Gewa Exp $
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
   if (!Auth::hasPrivileges('manage_plugins')): print Message::msgError(Language::$word->NOACCESS);
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
            <div class="tab spaced">
               <?php foreach ($this->langlist as $lang): ?>
                  <div data-tab="lang_<?php echo $lang->abbr; ?>" class="item">
                     <div class="wojo fields">
                        <div class="field five wide">
                           <label><?php echo Language::$word->NAME; ?>
                              <small><?php echo $lang->abbr; ?></small>
                              <i class="icon asterisk"></i>
                           </label>
                           <div class="wojo basic large input">
                              <input type="text" placeholder="<?php echo Language::$word->NAME; ?>"
                                     value="<?php echo $this->data->{'title_' . $lang->abbr}; ?>"
                                     name="title_<?php echo $lang->abbr ?>">
                           </div>
                        </div>
                        <div class="field five wide">
                           <label><?php echo Language::$word->DESCRIPTION; ?>
                              <small><?php echo $lang->abbr; ?></small>
                           </label>
                           <div class="wojo basic large input">
                              <input type="text" placeholder="<?php echo Language::$word->DESCRIPTION; ?>"
                                     value="<?php echo $this->data->{'info_' . $lang->abbr}; ?>"
                                     name="info_<?php echo $lang->abbr; ?>">
                           </div>
                        </div>
                     </div>
                     <div class="wojo fields">
                        <div class="field">
									<textarea class="bodypost" placeholder="<?php echo Language::$word->CONTENT; ?>"
                                     name="body_<?php echo $lang->abbr; ?>"><?php echo Url::out_url($this->data->{'body_' . $lang->abbr}); ?></textarea>
                        </div>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field ">
               <label><?php echo Language::$word->PLG_CLASS; ?></label>
               <input type="text" placeholder="<?php echo Language::$word->PLG_CLASS; ?>"
                      value="<?php echo $this->data->alt_class; ?>" name="alt_class">
            </div>
            <div class="field">
               <label><?php echo Language::$word->PLG_SHOWTITLE; ?></label>
               <div class="wojo checkbox radio toggle fitted inline">
                  <input name="show_title" type="radio" value="1"
                         id="show_title_1" <?php echo Validator::getChecked($this->data->show_title, 1); ?>>
                  <label for="show_title_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio toggle fitted inline">
                  <input name="show_title" type="radio" value="0"
                         id="show_title_0" <?php echo Validator::getChecked($this->data->show_title, 0); ?>>
                  <label for="show_title_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
            <div class="field">
               <label><?php echo Language::$word->ACTIVE; ?></label>
               <div class="wojo checkbox radio toggle fitted inline">
                  <input name="active" type="radio" value="1"
                         id="active_1" <?php echo Validator::getChecked($this->data->active, 1); ?>>
                  <label for="active_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio toggle fitted inline">
                  <input name="active" type="radio" value="0"
                         id="active_0" <?php echo Validator::getChecked($this->data->active, 0); ?>>
                  <label for="active_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('admin/plugins'); ?>"
            class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-route="admin/plugins/action/" data-action="update" name="dosubmit"
                 class="wojo primary button"><?php echo Language::$word->PLG_SUB2; ?></button>
      </div>
      <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
   </form>
   <?php break; ?>
<?php case 'new': ?>
   <!-- Start new -->
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
            <div class="tab spaced">
               <?php foreach ($this->langlist as $lang): ?>
                  <div data-tab="lang_<?php echo $lang->abbr; ?>" class="item">
                     <div class="wojo fields">
                        <div class="field five wide">
                           <label><?php echo Language::$word->NAME; ?>
                              <small><?php echo $lang->abbr; ?></small>
                              <i class="icon asterisk"></i>
                           </label>
                           <div class="wojo basic large input">
                              <input type="text" placeholder="<?php echo Language::$word->NAME; ?>"
                                     name="title_<?php echo $lang->abbr ?>">
                           </div>
                        </div>
                        <div class="field five wide">
                           <label><?php echo Language::$word->DESCRIPTION; ?>
                              <small><?php echo $lang->abbr; ?></small>
                           </label>
                           <div class="wojo basic large input">
                              <input type="text" placeholder="<?php echo Language::$word->DESCRIPTION; ?>"
                                     name="info_<?php echo $lang->abbr; ?>">
                           </div>
                        </div>
                     </div>
                     <div class="wojo fields">
                        <div class="field">
									<textarea class="bodypost" placeholder="<?php echo Language::$word->CONTENT; ?>"
                                     name="body_<?php echo $lang->abbr; ?>"></textarea>
                        </div>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field ">
               <label><?php echo Language::$word->PLG_CLASS; ?></label>
               <input type="text" placeholder="<?php echo Language::$word->PLG_CLASS; ?>" name="alt_class">
            </div>
            <div class="field">
               <label><?php echo Language::$word->PLG_SHOWTITLE; ?></label>
               <div class="wojo checkbox radio toggle fitted inline">
                  <input name="show_title" type="radio" value="1" id="show_title_1">
                  <label for="show_title_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio toggle fitted inline">
                  <input name="show_title" type="radio" value="0" id="show_title_0" checked="checked">
                  <label for="show_title_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
            <div class="field">
               <label><?php echo Language::$word->ACTIVE; ?></label>
               <div class="wojo checkbox radio toggle fitted inline">
                  <input name="active" type="radio" value="1" id="active_1" checked="checked">
                  <label for="active_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio toggle fitted inline">
                  <input name="active" type="radio" value="0" id="active_0">
                  <label for="active_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('admin/plugins'); ?>"
            class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-route="admin/plugins/action/" data-action="add" name="dosubmit"
                 class="wojo primary button"><?php echo Language::$word->PLG_SUB1; ?></button>
      </div>
   </form>
   <?php break; ?>
<?php default: ?>
   <!-- Start default -->
   <div class="row gutters align-middle justify-between">
      <div class="columns screen-40 tablet-50 mobile-100">
         <div class="wojo form">
            <div class="wojo small icon ajax input">
               <input name="find" placeholder="<?php echo Language::$word->SEARCH; ?>" type="text" data-page="Plugin" data-type="page">
               <i class="icon search"></i>
               <div class="results"></div>
            </div>
         </div>
      </div>
      <div class="columns auto mobile-100 phone-100">
         <a href="<?php echo Url::url(Router::$path, 'new/'); ?>" class="wojo small secondary fluid button">
            <i class="icon plus alt"></i><?php echo Language::$word->PLG_SUB1; ?></a>
      </div>
   </div>
   <div class="center-align margin-vertical">
      <?php echo Validator::alphaBits(Url::url(Router::$path)); ?>
   </div>
   <?php if (!$this->data): ?>
      <div class="center-align">
         <img src="<?php echo ADMINVIEW; ?>images/notfound.svg" alt="" class="wojo big inline image">
         <div class="margin-small-top">
            <p class="wojo small icon alert inverted attached compact message">
               <i class="icon exclamation triangle"></i><?php echo Language::$word->PLG_NOPLG; ?></p>
         </div>
      </div>
   <?php else: ?>
      <div class="row grid phone-1 mobile-1 tablet-2 screen-3 gutters">
         <?php foreach ($this->data as $row): ?>
            <?php if ($this->auth->checkPlugAcl($row->plugalias)): ?>
               <div class="columns" id="item_<?php echo $row->id; ?>">
                  <div class="wojo simple segment">
                     <div class="center-align">
                        <img src="<?php echo $row->icon? APLUGINURL . $row->icon : ADMINVIEW . 'images/basic_plugin.svg'; ?>"
                             class="wojo normal inline image" alt="">
                        <h6 class="truncate margin-top margin-small-bottom"><?php echo $row->{'title' . Language::$lang}; ?></h6>
                     </div>
                     <div class="row justify-center">
                        <div class="columns<?php echo (!$row->hasconfig)? ' auto' : null; ?>">
                           <a href="<?php echo Url::url(Router::$path . '/edit', $row->id); ?>"
                              class="wojo icon primary inverted circular button">
                              <i class="icon pencil"></i>
                           </a>
                           <a data-set='{"option":[{"action": "<?php echo $row->plugalias? 'delete' : 'trash'; ?>","title": "<?php echo Validator::sanitize($row->{'title' . Language::$lang}, 'chars'); ?>","id":<?php echo $row->id; ?>}],"action":"<?php echo $row->plugalias? 'delete' : 'trash'; ?>","parent":"#item_<?php echo $row->id; ?>", "url":"plugins/action/"}'
                              class="wojo icon negative inverted circular button data">
                              <i class="icon trash"></i>
                           </a>
                        </div>
                        <?php if ($row->hasconfig): ?>
                           <div class="columns auto">
                              <a href="<?php echo Url::url(Router::$path, $row->plugalias); ?>"
                                 class="wojo icon dark inverted circular button">
                                 <i class="icon gears"></i>
                              </a>
                           </div>
                        <?php endif; ?>
                     </div>
                  </div>
               </div>
            <?php endif; ?>
         <?php endforeach; ?>
      </div>
   <?php endif; ?>
   <div class="padding-small-horizontal">
      <div class="row gutters align-middle">
         <div class="columns auto mobile-100 phone-100">
            <div class="text-size-small text-weight-500"><?php echo Language::$word->TOTAL . ': ' . $this->pager->items_total; ?>
               / <?php echo Language::$word->CURPAGE . ': ' . $this->pager->current_page . ' ' . Language::$word->OF . ' ' . $this->pager->num_pages; ?></div>
         </div>
         <div class="columns mobile-100 right-align"><?php echo $this->pager->display(); ?></div>
      </div>
   </div>
   <?php break; ?>
<?php endswitch; ?>