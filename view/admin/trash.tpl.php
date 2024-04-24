<?php
   /**
    * trash
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: trash.tpl.php, v1.00 5/31/2023 9:33 AM Gewa Exp $
    *
    */

   use Wojo\Language\Language;
   use Wojo\Utility\Utility;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   $this->auth::checkOwner();
?>
   <div class="row small-gutters justify-end">
      <?php if ($this->data): ?>
         <div class="columns auto">
            <a data-set='{"option":[{"action": "empty","title": "<?php echo Validator::sanitize(Language::$word->TRS_TEMPTY, 'chars'); ?>","id":0}],"action":"delete","parent":"#self", "url":"trash/action/"}' class="wojo negative button data"><?php echo Language::$word->TRS_TEMPTY; ?></a>
         </div>
      <?php endif; ?>
   </div>
<?php if (!$this->data): ?>
   <div class="center-align">
      <img src="<?php echo ADMINVIEW; ?>images/notfound.svg" alt="" class="wojo huge inline image">
      <div class="margin-small-top">
         <p class="wojo small icon alert inverted attached compact message">
            <i class="icon exclamation triangle"></i><?php echo Language::$word->TRS_NOTRS; ?></p>
      </div>
   </div>
<?php else: ?>
   <?php foreach ($this->data as $type => $rows): ?>
      <?php switch ($type): ?>
<?php case 'user': ?>
            <div class="wojo simple segment">
               <table class="wojo small basic table">
                  <thead>
                  <tr>
                     <th colspan="2"><h5><?php echo Language::$word->ADM_USERS; ?></h5></th>
                  </tr>
                  </thead>
                  <?php foreach ($rows as $row): ?>
                     <?php $dataset = Utility::jSonToArray($row->dataset); ?>
                     <tr id="user_<?php echo $dataset->id; ?>">
                        <td><?php echo $dataset->fname; ?>
                           <?php echo $dataset->lname; ?></td>
                        <td class="auto">
                           <a data-set='{"option":[{"action": "restore","title": "<?php echo Validator::sanitize($dataset->fname . ' ' . $dataset->lname, 'chars'); ?>","id": "<?php echo $row->id; ?>", "type":"user"}],"action":"restore","subtext":"<?php echo Validator::sanitize(Language::$word->DELCONFIRM11,
                             'chars'); ?>", "parent":"#user_<?php echo $dataset->id; ?>", "url":"trash/action/"}' class="wojo small positive button data">
                              <?php echo Language::$word->RESTORE; ?>
                           </a>
                           <a data-set='{"option":[{"action": "delete","title": "<?php echo Validator::sanitize($dataset->fname . ' ' . $dataset->lname, 'chars'); ?>","id": "<?php echo $row->id; ?>", "type":"user"}],"action":"delete", "parent":"#user_<?php echo $dataset->id; ?>", "url":"trash/action/"}' class="wojo small negative button data">
                              <?php echo Language::$word->TRS_DELGOOD; ?>
                           </a>
                        </td>
                     </tr>
                  <?php endforeach; ?>
                  <?php unset($dataset); ?>
               </table>
            </div>
            <?php break; ?>
         <?php case 'membership': ?>
            <div class="wojo simple segment">
               <table class="wojo small basic table">
                  <thead>
                  <tr>
                     <th colspan="2"><h5><?php echo Language::$word->ADM_MEMBS; ?></h5></th>
                  </tr>
                  </thead>
                  <?php foreach ($rows as $row): ?>
                     <?php $dataset = Utility::jSonToArray($row->dataset); ?>
                     <tr id="membership_<?php echo $dataset->id; ?>">
                        <td><?php echo $dataset->{'title_en'}; ?></td>
                        <td class="auto">
                           <a data-set='{"option":[{"action": "restore","title": "<?php echo Validator::sanitize($dataset->{'title_en'}, 'chars'); ?>","id": "<?php echo $row->id; ?>", "type":"membership"}],"action":"restore","subtext":"<?php echo Validator::sanitize(Language::$word->DELCONFIRM11,
                             'chars'); ?>", "parent":"#membership_<?php echo $dataset->id; ?>", "url":"trash/action/"}' class="wojo small positive button data">
                              <?php echo Language::$word->RESTORE; ?>
                           </a>
                           <a data-set='{"option":[{"action": "delete","title": "<?php echo Validator::sanitize($dataset->{'title_en'}, 'chars'); ?>","id": "<?php echo $row->id; ?>","type":"membership"}],"action":"delete", "parent":"#membership_<?php echo $dataset->id; ?>", "url":"trash/action/"}' class="wojo small negative button data">
                              <?php echo Language::$word->TRS_DELGOOD; ?>
                           </a>
                        </td>
                     </tr>
                  <?php endforeach; ?>
                  <?php unset($dataset); ?>
               </table>
            </div>
            <?php break; ?>
         <?php case 'page': ?>
            <div class="wojo simple segment">
               <table class="wojo small basic table">
                  <thead>
                  <tr>
                     <th colspan="2"><h5><?php echo Language::$word->ADM_PAGES; ?></h5></th>
                  </tr>
                  </thead>
                  <?php foreach ($rows as $row): ?>
                     <?php $dataset = Utility::jSonToArray($row->dataset); ?>
                     <tr id="page_<?php echo $dataset->id; ?>">
                        <td><?php echo $dataset->{'title_en'}; ?></td>
                        <td class="auto">
                           <a data-set='{"option":[{"action": "restore","title": "<?php echo Validator::sanitize($dataset->{'title_en'}, 'chars'); ?>","id": "<?php echo $row->id; ?>","type":"page"}],"action":"restore","subtext":"<?php echo Validator::sanitize(Language::$word->DELCONFIRM11,
                             'chars'); ?>", "parent":"#page_<?php echo $dataset->id; ?>", "url":"trash/action/"}' class="wojo small positive button data">
                              <?php echo Language::$word->RESTORE; ?>
                           </a>
                           <a data-set='{"option":[{"action": "delete","title": "<?php echo Validator::sanitize($dataset->{'title_en'}, 'chars'); ?>","id": "<?php echo $row->id; ?>","type":"page"}],"action":"delete", "parent":"#page_<?php echo $dataset->id; ?>", "url":"trash/action/"}' class="wojo small negative button data">
                              <?php echo Language::$word->TRS_DELGOOD; ?>
                           </a>
                        </td>
                     </tr>
                  <?php endforeach; ?>
                  <?php unset($dataset); ?>
               </table>
            </div>
            <?php break; ?>
         <?php case 'coupon': ?>
            <div class="wojo simple segment">
               <table class="wojo small basic table">
                  <thead>
                  <tr>
                     <th colspan="2"><h5><?php echo Language::$word->ADM_COUPONS; ?></h5></th>
                  </tr>
                  </thead>
                  <?php foreach ($rows as $row): ?>
                     <?php $dataset = Utility::jSonToArray($row->dataset); ?>
                     <tr id="coupon_<?php echo $dataset->id; ?>">
                        <td><?php echo $dataset->title; ?></td>
                        <td class="auto">
                           <a data-set='{"option":[{"action": "restore","title": "<?php echo Validator::sanitize($dataset->title, 'chars'); ?>","id": "<?php echo $row->id; ?>","type":"coupon"}],"action":"restore","subtext":"<?php echo Validator::sanitize(Language::$word->DELCONFIRM11,
                             'chars'); ?>", "parent":"#coupon_<?php echo $dataset->id; ?>", "url":"trash/action/"}' class="wojo small positive button data">
                              <?php echo Language::$word->RESTORE; ?>
                           </a>
                           <a data-set='{"option":[{"action": "delete","title": "<?php echo Validator::sanitize($dataset->title, 'chars'); ?>","id": "<?php echo $row->id; ?>","type":"coupon"}],"action":"delete", "parent":"#coupon_<?php echo $dataset->id; ?>", "url":"trash/action/"}' class="wojo small negative button data">
                              <?php echo Language::$word->TRS_DELGOOD; ?>
                           </a>
                        </td>
                     </tr>
                  <?php endforeach; ?>
                  <?php unset($dataset); ?>
               </table>
            </div>
            <?php break; ?>
         <?php case 'menu': ?>
            <div class="wojo simple segment">
               <table class="wojo small basic table">
                  <thead>
                  <tr>
                     <th colspan="2"><h5><?php echo Language::$word->ADM_MENUS; ?></h5></th>
                  </tr>
                  </thead>
                  <?php foreach ($rows as $row): ?>
                     <?php $dataset = Utility::jSonToArray($row->dataset); ?>
                     <tr id="menu_<?php echo $dataset->id; ?>">
                        <td><?php echo $dataset->{'name_en'}; ?></td>
                        <td class="auto">
                           <a data-set='{"option":[{"action": "restore","title": "<?php echo Validator::sanitize($dataset->{'name_en'}, 'chars'); ?>","id": "<?php echo $row->id; ?>","type":"menu"}],"action":"restore","subtext":"<?php echo Validator::sanitize(Language::$word->DELCONFIRM11,
                             'chars'); ?>", "parent":"#menu_<?php echo $dataset->id; ?>", "url":"trash/action/"}' class="wojo small positive button data">
                              <?php echo Language::$word->RESTORE; ?>
                           </a>
                           <a data-set='{"option":[{"action": "delete","title": "<?php echo Validator::sanitize($dataset->{'name_en'}, 'chars'); ?>","id": "<?php echo $row->id; ?>","type":"menu"}],"action":"delete", "parent":"#menu_<?php echo $dataset->id; ?>", "url":"trash/action/"}' class="wojo small negative button data">
                              <?php echo Language::$word->TRS_DELGOOD; ?>
                           </a>
                        </td>
                     </tr>
                  <?php endforeach; ?>
                  <?php unset($dataset); ?>
               </table>
            </div>
            <?php break; ?>
         <?php case 'plugin': ?>
            <div class="wojo simple segment">
               <table class="wojo small basic table">
                  <thead>
                  <tr>
                     <th colspan="2"><h5><?php echo Language::$word->PLUGINS; ?></h5></th>
                  </tr>
                  </thead>
                  <?php foreach ($rows as $row): ?>
                     <?php $dataset = Utility::jSonToArray($row->dataset); ?>
                     <tr id="plugin_<?php echo $dataset->id; ?>">
                        <td><?php echo $dataset->{'title_en'}; ?></td>
                        <td class="auto">
                           <a data-set='{"option":[{"action": "restore","title": "<?php echo Validator::sanitize($dataset->{'title_en'}, 'chars'); ?>","id": "<?php echo $row->id; ?>","type":"plugin"}],"action":"restore","subtext":"<?php echo Validator::sanitize(Language::$word->DELCONFIRM11,
                             'chars'); ?>", "parent":"#plugin_<?php echo $dataset->id; ?>", "url":"trash/action/"}' class="wojo small positive button data">
                              <?php echo Language::$word->RESTORE; ?>
                           </a>
                           <a data-set='{"option":[{"action": "delete","title": "<?php echo Validator::sanitize($dataset->{'title_en'}, 'chars'); ?>","id": "<?php echo $row->id; ?>","type":"plugin"}],"action":"delete", "parent":"#plugin_<?php echo $dataset->id; ?>", "url":"trash/action/"}' class="wojo small negative button data">
                              <?php echo Language::$word->TRS_DELGOOD; ?>
                           </a>
                        </td>
                     </tr>
                  <?php endforeach; ?>
                  <?php unset($dataset); ?>
               </table>
            </div>
            <?php break; ?>
         <?php endswitch; ?>
   <?php endforeach; ?>
<?php endif; ?>