<?php
   /**
    * _language_translate
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _language_translate.tpl.php, v1.00 5/8/2023 9:18 AM Gewa Exp $
    *
    */

   use Wojo\Language\Language;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="wojo form">
   <div class="row gutters align-middle">
      <div class="columns screen-50 mobile-100">
         <div class="wojo action icon input">
            <input id="filter" type="text" placeholder="<?php echo Language::$word->SEARCH; ?>">
            <i class="icon search"></i>
         </div>
      </div>
      <div class="columns screen-25 mobile-50 phone-100">
         <select name="pgroup" id="pgroup" data-abbr="<?php echo $this->row->abbr; ?>">
            <option data-type="all" value="all"><?php echo Language::$word->LG_SUB4; ?></option>
            <?php foreach ($this->sections as $rows): ?>
               <option data-type="filter" value="<?php echo $rows; ?>"><?php echo $rows; ?></option>
            <?php endforeach; ?>
            <?php unset($rows); ?>
         </select>
      </div>
      <div class="columns screen-25 mobile-50 phone-100">
         <select name="group" id="group" data-abbr="<?php echo $this->row->abbr; ?>">
            <option value="all"><?php echo Language::$word->LG_SUB3; ?></option>
            <optgroup label="<?php echo Language::$word->PLUGINS; ?>">
               <?php foreach ($this->pluglang as $rows): ?>
                  <option data-type="plugins" data-key="<?php echo $rows; ?>" value="<?php echo 'plugins' . '/' . strtolower($rows) . '.plugin.json'; ?>"><?php echo $rows; ?></option>
               <?php endforeach; ?>
               <?php unset($rows); ?>
            </optgroup>
            <optgroup label="<?php echo Language::$word->MODULES; ?>">
               <?php foreach ($this->modlang as $rows): ?>
                  <option data-type="modules" data-key="<?php echo $rows; ?>" value="<?php echo 'modules' . '/' . strtolower($rows) . '.module.json'; ?>"><?php echo $rows; ?></option>
               <?php endforeach; ?>
               <?php unset($rows); ?>
            </optgroup>
         </select>
      </div>
   </div>
</div>
<?php $i = 0; ?>
<table class="wojo responsive table" id="editable" data-url="ajax/">
   <thead>
   <tr>
      <th><?php echo Language::$word->NAME; ?></th>
      <th class="auto right-align"><?php echo Language::$word->LG_KEY; ?></th>
   </tr>
   </thead>
   <?php foreach ($this->data as $key => $row) : ?>
      <?php $i++; ?>
      <tr>
         <td>
            <span data-editable="true" data-set='{"action": "phrase", "id": <?php echo $i; ?>,"key":"<?php echo $key; ?>", "path":"<?php echo $this->row->abbr; ?>/lang.json", "url":"languages/action/"}'><?php echo $row; ?></span>
         </td>
         <td class="auto right-align"><span class="wojo mini secondary label"><?php echo $key; ?></span></td>
      </tr>
   <?php endforeach; ?>
</table>