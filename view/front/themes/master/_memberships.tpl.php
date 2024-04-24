<?php
   /**
    * _memberships
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _memberships.tpl.php, v1.00 6/22/2023 1:51 PM Gewa Exp $
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
      <h4><?php echo Language::$word->ADM_MEMBS; ?></h4>
      <p><?php echo Language::$word->M_INFO13; ?></p>
      <?php if ($this->memberships): ?>
         <div id="membershipSelect" class="wojo basic cards screen-3 tablet-2 mobile-1 phone-1 align-center">
            <?php foreach ($this->memberships as $row): ?>
               <div class="card<?php echo $this->user->membership_id == $row->id? ' active framed' : null; ?>" id="item_<?php echo $row->id; ?>">
                  <div class="content">
                     <figure class="wojo fluid image padding">
                        <?php if ($row->thumb): ?>
                           <img src="<?php echo UPLOADURL; ?>memberships/<?php echo $row->thumb; ?>" alt="">
                        <?php else: ?>
                           <img src="<?php echo UPLOADURL; ?>memberships/default.svg" alt="">
                        <?php endif; ?>
                     </figure>
                     <h5 class="text-color-primary center-align">
                        <?php echo Utility::formatMoney($row->price); ?>
                        <?php echo $row->{'title' . Language::$lang}; ?>
                     </h5>
                     <div class="wojo list">
                        <div class="item">
                           <?php echo Language::$word->MEM_REC1; ?>
                           <?php echo ($row->recurring)? Language::$word->YES : Language::$word->NO; ?>
                        </div>
                        <div class="item">
                           <?php echo $row->days; ?> @<?php echo Date::getPeriodReadable($row->period); ?>
                        </div>
                        <div class="item">
                           <span class="text-size-small"><?php echo $row->{'description' . Language::$lang}; ?></span>
                        </div>
                     </div>
                  </div>
                  <div class="footer">
                     <?php if ($this->user->membership_id != $row->id): ?>
                        <a class="wojo fluid primary button add-membership" data-id="<?php echo $row->id; ?>"><?php echo ($row->price <> 0)? Language::$word->SELECT : Language::$word->ACTIVATE; ?></a>
                     <?php endif; ?>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>
         <div id="mResult"></div>
      <?php endif; ?>
   </div>
</div>