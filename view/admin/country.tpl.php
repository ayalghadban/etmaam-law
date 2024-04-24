<?php
   /**
    * country
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: country.tpl.php, v1.00 5/12/2023 9:48 AM Gewa Exp $
    *
    */

   use Wojo\Auth\Auth;
   use Wojo\Core\Router;
   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Url\Url;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   if (!Auth::hasPrivileges('manage_countries')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<?php switch (Url::segment($this->segments)): case 'edit': ?>
   <!-- Start edit -->
   <form method="post" id="wojo_form" name="wojo_form">
      <div class="wojo simple segment form margin-bottom">
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->NAME; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input type="text" placeholder="<?php echo Language::$word->NAME; ?>" value="<?php echo $this->data->name; ?>"
                      name="name">
            </div>
            <div class="field">
               <label><?php echo Language::$word->CNT_ABBR; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input type="text" placeholder="<?php echo Language::$word->CNT_ABBR; ?>" value="<?php echo $this->data->abbr; ?>"
                      name="abbr">
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->TRX_TAX; ?></label>
               <div class="wojo input">
                  <input type="text" placeholder="<?php echo Language::$word->TRX_TAX; ?>" value="<?php echo $this->data->vat; ?>"
                         name="vat">
                  <div class="wojo simple label">%</div>
               </div>
            </div>
            <div class="field">
               <label><?php echo Language::$word->SORTING; ?></label>
               <input type="text" placeholder="<?php echo Language::$word->SORTING; ?>"
                      value="<?php echo $this->data->sorting; ?>" name="sorting">
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->STATUS; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="active" type="radio" value="1"
                         id="active_1" <?php echo Validator::getChecked($this->data->active, 1); ?>>
                  <label for="active_1"><?php echo Language::$word->ACTIVE; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="active" type="radio" value="0"
                         id="active_0" <?php echo Validator::getChecked($this->data->active, 0); ?>>
                  <label for="active_0"><?php echo Language::$word->INACTIVE; ?></label>
               </div>
            </div>
            <div class="field">
               <label><?php echo Language::$word->DEFAULT; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="home" type="radio" value="1" id="home_1" <?php echo Validator::getChecked($this->data->home, 1); ?>>
                  <label for="home_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="home" type="radio" value="0" id="home_0" <?php echo Validator::getChecked($this->data->home, 0); ?>>
                  <label for="home_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('admin/countries'); ?>"
            class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-route="admin/countries/action/" data-action="update" name="dosubmit"
                 class="wojo primary button"><?php echo Language::$word->CNT_UPDATE; ?></button>
      </div>
      <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
   </form>
   <?php break; ?>
<?php default: ?>
   <?php if (!$this->data): ?>
      <div class="center-align">
         <img src="<?php echo ADMINVIEW; ?>images/notfound.svg" alt="" class="wojo big inline image">
         <div class="margin-small-top">
            <p class="wojo small icon alert inverted attached compact message">
               <i
                 class="icon exclamation triangle"></i><?php echo Language::$word->CNT_NOCOUNTRY; ?></p>
         </div>
      </div>
   <?php else: ?>
      <div class="wojo simple segment">
         <table class="wojo basic responsive table" id="editable">
            <thead>
            <tr>
               <th class="center-align"></th>
               <th><?php echo Language::$word->NAME; ?></th>
               <th><?php echo Language::$word->CNT_ABBR; ?></th>
               <th><?php echo Language::$word->TAX; ?></th>
               <th><?php echo Language::$word->SORTING; ?></th>
               <th class="center-align"><?php echo Language::$word->ACTIONS; ?></th>
            </tr>
            </thead>
            <?php foreach ($this->data as $row): ?>
               <tr id="item_<?php echo $row->id; ?>">
                  <td class="auto">
                     <span class="wojo small dark inverted label"><?php echo $row->id; ?></span>
                  </td>
                  <td>
                     <a href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>">
                        <?php echo $row->name; ?></a>
                  </td>
                  <td>
                     <span class="wojo small label"><?php echo $row->abbr; ?></span>
                  </td>
                  <td><span data-editable="true"
                            data-set='{"action": "tax", "id": <?php echo $row->id; ?>, "url":"countries/action/"}'><?php echo $row->vat; ?></span>%
                  </td>
                  <td><?php echo $row->sorting; ?></td>
                  <td class="auto">
                     <a href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>"
                        class="wojo icon circular primary inverted button">
                        <i class="icon pencil"></i>
                     </a>
                  </td>
               </tr>
            <?php endforeach; ?>
         </table>
      </div>
   <?php endif; ?>
   <?php break; ?>
<?php endswitch; ?>