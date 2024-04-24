<?php
   /**
    * layout
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: layout.tpl.php, v1.00 5/12/2023 11:45 AM Gewa Exp $
    *
    */

   use Wojo\Auth\Auth;
   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   if (!Auth::hasPrivileges('manage_layout')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<div class="row gutters justify-center">
   <div class="columns screen-30 tablet-30 mobile-100">
      <?php if ($this->modulelist): ?>
         <div class="wojo form">
            <select name="mod_id">
               <option value="0"><?php echo Language::$word->LMG_SUB1; ?></option>
               <?php echo Utility::loopOptions($this->modulelist, 'id', 'title' . Language::$lang, Validator::get('mod_id')); ?>
            </select>
         </div>
      <?php endif; ?>
   </div>
</div>
<div class="wojo<?php echo ($this->layoutlist->mod)? null : ' readonly'; ?>">
   <div class="row gutters">
      <div class="columns">
         <div class="wojo segment">
            <a data-wdropdown="#dropdown-top" data-section="top"
               class="wojo small top left simple icon attached button pEdit">
               <i class="icon disabled distribute horizontal"></i>
            </a>
            <div class="wojo static dropdown small top-left" id="dropdown-top">
               <a class="icon wojo simple icon button loading">
                  <i class="icon spinning circle"></i>
               </a>
            </div>
            <a data-section="top"
               class="wojo small top right simple attached button pAdd"><?php echo Language::$word->LMG_TOP; ?>
               <i class="icon small chevron down"></i>
            </a>
            <div class="margin-top">
               <ul data-position="top" class="wojo sortable list">
                  <?php if ($topside = Utility::findInArray($this->layoutlist->row, 'place', 'top')): ?>
                     <?php foreach ($topside as $row): ?>
                        <li class="item" data-id="<?php echo $row->plug_id; ?>" id="item_<?php echo $row->plug_id; ?>">
                           <div class="content">
                              <div class="handle">
                                 <i class="icon grip horizontal"></i>
                              </div>
                              <div class="text"><?php echo $row->title; ?></div>
                              <div class="actions">
                                 <a>
                                    <i class="icon negative x alt"></i>
                                 </a>
                              </div>
                           </div>
                        </li>
                     <?php endforeach; ?>
                     <?php unset($row); ?>
                  <?php endif; ?>
               </ul>
            </div>
         </div>
      </div>
   </div>
   <div class="row gutters">
      <div class="columns screen-40">
         <div class="wojo segment">
            <a data-section="left"
               class="wojo small top right simple attached button pAdd"><?php echo Language::$word->LMG_LEFT; ?>
               <i class="icon small chevron down"></i>
            </a>
            <ul data-position="left" class="wojo sortable list">
               <?php if ($leftlide = Utility::findInArray($this->layoutlist->row, 'place', 'left')): ?>
                  <?php foreach ($leftlide as $row): ?>
                     <li class="item" data-id="<?php echo $row->plug_id; ?>" id="item_<?php echo $row->plug_id; ?>">
                        <div class="content">
                           <div class="handle">
                              <i class="icon grip horizontal"></i>
                           </div>
                           <div class="text"><?php echo Validator::truncate($row->title, 40); ?></div>
                           <div class="actions">
                              <a>
                                 <i class="icon negative x alt"></i>
                              </a>
                           </div>
                        </div>
                     </li>
                  <?php endforeach; ?>
                  <?php unset($row); ?>
               <?php endif; ?>
            </ul>
         </div>
      </div>
      <div class="columns">
         <div class="wojo segment">
            <span class="wojo small simple fluid button"><?php echo Language::$word->LMG_MAIN; ?></span>
         </div>
      </div>
      <div class="columns screen-40">
         <div class="wojo segment">
            <a data-section="right"
               class="wojo small top right simple attached button pAdd"><?php echo Language::$word->LMG_RIGHT; ?>
               <i class="icon small chevron down"></i>
            </a>
            <div class="margin-top">
               <ul data-position="right" class="wojo sortable list">
                  <?php if ($rightside = Utility::findInArray($this->layoutlist->row, 'place', 'right')): ?>
                     <?php foreach ($rightside as $row): ?>
                        <li class="item" data-id="<?php echo $row->plug_id; ?>" id="item_<?php echo $row->plug_id; ?>">
                           <div class="content">
                              <div class="handle">
                                 <i class="icon grip horizontal"></i>
                              </div>
                              <div class="text"><?php echo Validator::truncate($row->title, 40); ?></div>
                              <div class="actions">
                                 <a>
                                    <i class="icon negative x alt"></i>
                                 </a>
                              </div>
                           </div>
                        </li>
                     <?php endforeach; ?>
                     <?php unset($row); ?>
                  <?php endif; ?>
               </ul>
            </div>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="columns">
         <div class="wojo segment">
            <a data-wdropdown="#dropdown-bottom" data-section="bottom"
               class="wojo small top left simple icon attached button pEdit">
               <i class="icon disabled distribute horizontal"></i>
            </a>
            <div class="wojo static dropdown small top-left" id="dropdown-bottom">
               <a class="icon wojo simple icon button loading">
                  <i class="icon spinning circle"></i>
               </a>
            </div>
            <a data-section="bottom"
               class="wojo small top right simple attached button pAdd"><?php echo Language::$word->LMG_BOTTOM; ?>
               <i class="icon small chevron down"></i>
            </a>
            <ul data-position="bottom" class="wojo sortable list">
               <?php if ($bottomside = Utility::findInArray($this->layoutlist->row, 'place', 'bottom')): ?>
                  <?php foreach ($bottomside as $row): ?>
                     <li class="item" data-id="<?php echo $row->plug_id; ?>" id="item_<?php echo $row->plug_id; ?>">
                        <div class="content">
                           <div class="handle">
                              <i class="icon grip horizontal"></i>
                           </div>
                           <div class="text"><?php echo $row->title; ?></div>
                           <div class="actions">
                              <a>
                                 <i class="icon negative x alt"></i>
                              </a>
                           </div>
                        </div>
                     </li>
                  <?php endforeach; ?>
                  <?php unset($row); ?>
               <?php endif; ?>
            </ul>
         </div>
      </div>
   </div>
</div>
<script src="<?php echo SITEURL; ?>assets/sortable.js"></script>
<script src="<?php echo ADMINVIEW; ?>js/layout.js"></script>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $.Layout({
         url: "<?php echo ADMINURL;?>",
         lurl: "<?php echo Url::url('admin/layout');?>",
         mod_id: [<?php echo json_encode($this->layoutlist->mod);?>],
         type: '<?php echo $this->layoutlist->type;?>',
         lang: {
            edit: "<?php echo Language::$word->EDIT;?>",
            delete: "<?php echo Language::$word->DELETE;?>",
            insert: "<?php echo Language::$word->INSERT;?>",
            cancel: "<?php echo Language::$word->CLOSE;?>"
         }
      });
   });
   // ]]>
</script>