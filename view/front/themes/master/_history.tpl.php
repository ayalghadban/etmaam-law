<?php
   /**
    * _history
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _history.tpl.php, v1.00 6/22/2023 2:07 PM Gewa Exp $
    *
    */
   
   use Wojo\Date\Date;
   use Wojo\Language\Language;
   use Wojo\Utility\Utility;
   
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="wojo-grid">
   <div class="padding-big-vertical">
      <h4><?php echo Language::$word->HISTORY; ?></h4>
      <p><?php echo Language::$word->M_INFO14; ?></p>
      <?php if ($this->history): ?>
         <div class="wojo segment">
            <table class="wojo basic responsive table">
               <thead>
               <tr>
                  <th><?php echo Language::$word->NAME; ?></th>
                  <th><?php echo Language::$word->MEM_ACT; ?></th>
                  <th><?php echo Language::$word->MEM_EXP; ?></th>
                  <th class="auto"><?php echo Language::$word->MEM_REC1; ?></th>
                  <th class="auto"></th>
               </tr>
               </thead>
               <?php foreach ($this->history as $row): ?>
                  <tr>
                     <td><?php echo $row->title; ?></td>
                     <td><?php echo Date::doDate('long_date', $row->activated); ?></td>
                     <td><?php echo Date::doDate('long_date', $row->expire); ?></td>
                     <td class="center-align"><?php echo Utility::isPublished($row->recurring); ?></td>
                     <td class="center-align">
                        <a href="<?php echo SITEURL . 'dashboard/action/?action=invoice&amp;id=' . $row->transaction_id; ?>">
                           <i class="icon download"></i>
                        </a>
                     </td>
                  </tr>
               <?php endforeach; ?>
            </table>
         </div>
      <?php endif; ?>
   </div>
</div>