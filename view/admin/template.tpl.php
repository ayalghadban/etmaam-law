<?php
   /**
    * template
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: template.tpl.php, v1.00 5/9/2023 7:10 PM Gewa Exp $
    *
    */

   use Wojo\Auth\Auth;
   use Wojo\Core\Router;
   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   if (!Auth::hasPrivileges('manage_email')): print Message::msgError(Language::$word->NOACCESS);
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
                        <div class="field">
                           <label><?php echo Language::$word->NAME; ?><small><?php echo $lang->abbr; ?></small>
                              <i class="icon asterisk"></i></label>
                           <div class="wojo large basic input">
                              <input type="text" placeholder="<?php echo Language::$word->NAME; ?>"
                                     value="<?php echo $this->data->{'name_' . $lang->abbr}; ?>"
                                     name="name_<?php echo $lang->abbr ?>">
                           </div>
                        </div>
                        <div class="field">
                           <label><?php echo Language::$word->ET_SUBJECT; ?><small><?php echo $lang->abbr; ?></small></label>
                           <div class="wojo large basic input">
                              <input type="text" placeholder="<?php echo Language::$word->ET_SUBJECT; ?>"
                                     value="<?php echo $this->data->{'subject_' . $lang->abbr}; ?>"
                                     name="subject_<?php echo $lang->abbr; ?>">
                           </div>
                        </div>
                     </div>
                     <div class="wojo fields">
                        <div class="basic field">
									<textarea class="bodypost" name="body_<?php echo $lang->abbr; ?>">
                              <?php echo str_replace(array('[SITEURL]', '[LOGO]'), array(SITEURL, $this->core->plogo), $this->data->{'body_' . $lang->abbr}); ?></textarea>
                           <p class="wojo small icon-text text-color-negative"><i class="icon exclamation square"></i>
                              <?php echo Language::$word->NOTEVAR; ?></p>
                        </div>
                     </div>
                     <div class="wojo divider"></div>
                     <div class="wojo fields">
                        <div class="field basic">
                           <label><?php echo Language::$word->ET_DESC; ?><small><?php echo $lang->abbr; ?></small></label>
                           <div class="wojo small input">
                              <input type="text" placeholder="<?php echo Language::$word->ET_DESC; ?>"
                                     value="<?php echo $this->data->{'help_' . $lang->abbr}; ?>"
                                     name="help_<?php echo $lang->abbr; ?>">
                           </div>
                        </div>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('admin/templates'); ?>"
            class="wojo wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-route="admin/templates/action/" data-action="update" name="dosubmit"
                 class="wojo primary button"><?php echo Language::$word->ET_UPDATE; ?></button>
      </div>
      <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
   </form>
   <?php break; ?>
<?php default: ?>
   <?php if (!$this->data): ?>
      <div class="center-align">
         <img src="<?php echo ADMINVIEW; ?>images/notfound.svg" alt="" class="wojo big inline image">
         <div class="margin-small-top">
            <p class="wojo small icon alert inverted attached compact message"><i
                 class="icon exclamation triangle"></i><?php echo Language::$word->ET_INFO; ?></p>
         </div>
      </div>
   <?php else: ?>
      <table class="wojo responsive table">
         <thead>
         <tr>
            <th class="disabled"></th>
            <th data-sort="string"><?php echo Language::$word->ET_NAME; ?></th>
            <th data-sort="string"><?php echo Language::$word->ET_SUBJECT; ?></th>
            <th class="disabled"><?php echo Language::$word->ACTIONS; ?></th>
         </tr>
         </thead>
         <tbody>
         <?php foreach ($this->data as $row): ?>
            <tr id="item_<?php echo $row->id; ?>">
               <td class="auto"><span class="wojo small simple label"><?php echo $row->id; ?></span></td>
               <td><a href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>" class="inverted">
                     <?php echo $row->{'name' . Language::$lang}; ?></a></td>
               <td><?php echo $row->{'subject' . Language::$lang}; ?></td>
               <td class="auto"><a href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>"
                                   class="wojo icon primary inverted circular button"><i class="icon pencil"></i></a></td>
            </tr>
         <?php endforeach; ?>
         </tbody>
      </table>
   <?php endif; ?>
   <?php break; ?>
<?php endswitch; ?>