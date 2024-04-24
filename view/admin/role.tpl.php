<?php
   /**
    * role
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: role.tpl.php, v1.00 4/29/2023 7:19 PM Gewa Exp $
    *
    */

   use Wojo\Core\Router;
   use Wojo\Language\Language;
   use Wojo\Url\Url;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   $this->auth::checkOwner();
?>
<?php switch (Url::segment($this->segments)): case 'edit': ?>
   <div class="wojo form">
      <table class="wojo six column responsive table" id="pTable">
         <thead>
         <tr>
            <th class="disabled"><?php echo Language::$word->TYPE; ?></th>
            <th class="disabled"><?php echo Language::$word->ADD; ?></th>
            <th class="disabled"><?php echo Language::$word->EDIT; ?></th>
            <th class="disabled"><?php echo Language::$word->APPROVE; ?></th>
            <th class="disabled"><?php echo Language::$word->MANAGE; ?></th>
            <th class="disabled"><?php echo Language::$word->DELETE; ?></th>
         </tr>
         </thead>
         <tbody>
         <?php
            foreach ($this->result as $type => $rows):
               echo '<tr>';
               echo '<td>' . $type . '</td>';
               echo '<td>';
               foreach ($rows as $i => $row):
                  if (isset($row->mode) and $row->mode == 'add') {
                     $checked = ($row->active == 1)? ' checked="checked"' : null;
                     $is_owner = ($this->role->code == 'owner')? ' disabled="disabled"' : null;
                     echo '<div class="wojo fitted toggle checkbox" data-id="' . $row->id . '"><input id="perm_' . $row->id . '" 
                     type="checkbox" data-val="' . $row->active . '" value="' . $row->id . '" 
                     name="view-' . $row->id . '" ' . $is_owner . $checked . '><label for="perm_' . $row->id . '"></label>
                     <span data-tooltip="' . $row->description . '"><i class="icon question circle"></i></span></div> ';
                  }
               endforeach;
               echo '</td>';

               echo '<td>';
               foreach ($rows as $row):
                  if (isset($row->mode) and $row->mode == 'edit') {
                     $checked = ($row->active == 1)? ' checked="checked"' : null;
                     $is_owner = ($this->role->code == 'owner')? ' disabled="disabled"' : null;
                     echo '<div class="wojo fitted toggle checkbox" data-id="' . $row->id . '"><input id="perm_' . $row->id . '" 
                     type="checkbox" data-val="' . $row->active . '" value="' . $row->id . '" 
                     name="view-' . $row->id . '" ' . $is_owner . $checked . '><label for="perm_' . $row->id . '"></label>
                     <span data-tooltip="' . $row->description . '"><i class="icon question circle"></i></span></div>';
                  }
               endforeach;
               echo '</td>';

               echo '<td>';
               foreach ($rows as $row):
                  if (isset($row->mode) and $row->mode == 'approve') {
                     $checked = ($row->active == 1)? ' checked="checked"' : null;
                     $is_owner = ($this->role->code == 'owner')? ' disabled="disabled"' : null;
                     echo '<div class="wojo fitted toggle checkbox" data-id="' . $row->id . '"><input id="perm_' . $row->id . '" 
                     type="checkbox" data-val="' . $row->active . '" value="' . $row->id . '" 
                     name="view-' . $row->id . '" ' . $is_owner . $checked . '><label for="perm_' . $row->id . '"></label>
                     <span data-tooltip="' . $row->description . '"><i class="icon question circle"></i></span></div>';
                  }
               endforeach;
               echo '</td>';

               echo '<td>';
               foreach ($rows as $row):
                  if (isset($row->mode) and $row->mode == 'manage') {
                     $checked = ($row->active == 1)? ' checked="checked"' : null;
                     $is_owner = ($this->role->code == 'owner')? ' disabled="disabled"' : null;
                     echo '<div class="wojo fitted toggle checkbox" data-id="' . $row->id . '"><input id="perm_' . $row->id . '" 
                     type="checkbox" data-val="' . $row->active . '" value="' . $row->id . '" 
                     name="view-' . $row->id . '" ' . $is_owner . $checked . '><label for="perm_' . $row->id . '"></label>
                     <span data-tooltip="' . $row->description . '"><i class="icon question circle"></i></span></div>';
                  }
               endforeach;
               echo '</td>';

               echo '<td>';
               foreach ($rows as $row):
                  if (isset($row->mode) and $row->mode == 'delete') {
                     $checked = ($row->active == 1)? ' checked="checked"' : null;
                     $is_owner = ($this->role->code == 'owner')? ' disabled="disabled"' : null;
                     echo '<div class="wojo fitted toggle checkbox" data-id="' . $row->id . '"><input id="perm_' . $row->id . '" 
                     type="checkbox" data-val="' . $row->active . '" value="' . $row->id . '" 
                     name="view-' . $row->id . '" ' . $is_owner . $checked . '><label for="perm_' . $row->id . '"></label>
                     <span data-tooltip="' . $row->description . '"><i class="icon question circle"></i></span></div>';
                  }
               endforeach;
               echo '</td>';

               echo '</tr>';
            endforeach;
         ?>
         </tbody>
      </table>
   </div>
   <div class="center-align padding-top"><a href="<?php echo Url::url('admin/roles'); ?>" class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a></div>
   <script type="text/javascript">
      // <![CDATA[
      $(document).ready(function () {
         $("#pTable").on('click', '.wojo.checkbox input', function () {
            let status = $(this).prop('checked') ? 1 : 0;
            let id = $(this).parent().data('id');
            $.post("<?php echo ADMINURL . 'roles/action/';?>", {
               action: "role",
               id: id,
               active: status
            });
         });
      });
      // ]]>
   </script>
   <?php break; ?>
<?php default: ?>
   <div class="wojo cards screen-3 tablet-3 mobile-1">
      <?php foreach ($this->data as $row): ?>
         <div class="card">
            <div class="content">
               <img src="<?php echo ADMINVIEW; ?>images/<?php echo $row->code; ?>.svg" alt="">
               <h5 class="center-align"><?php echo $row->name; ?></h5>
               <p id="item_<?php echo $row->id; ?>" class="text-size-small grey-text"><?php echo $row->description; ?></p>
            </div>
            <div class="footer divided center-align">
               <a href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>"
                  class="wojo icon inverted negative button spaced"><i class="icon lock"></i></a>
               <a data-set='{"option":[{"action":"edit","id": <?php echo $row->id; ?>}], "label":"<?php echo Language::$word->M_INF01; ?>", "url":"roles/action/", "parent":"#item_<?php echo $row->id; ?>", "complete":"replace", "modalclass":"normal"}'
                  class="wojo icon primary inverted button action">
                  <i class="icon pencil"></i></a>
            </div>
         </div>
      <?php endforeach; ?>
   </div>
   <?php break; ?>
<?php endswitch; ?>