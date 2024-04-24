<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 6/21/2023 9:24 AM Gewa Exp $
    *
    */
   
   use Wojo\Date\Date;
   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Module\Event\Event;
   use Wojo\Url\Url;
   
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   
   $data = Event::render();
?>
<!-- Start Event Manager -->
<div class="center-align margin-bottom">
   <h2><?php echo Language::$word->_MOD_EM_TITLE3; ?></h2>
   <p class="wojo primary inverted label"><?php echo str_replace('[YEAR]', Date::doDate('yyyy', Date::today()), Language::$word->_MOD_EM_SUB3); ?></p>
</div>
<?php if ($data): ?>
   <?php foreach ($data as $date => $rows): ?>
      <div class="text-color-primary text-weight-600"><?php echo Date::doDate('MMMM YYYY', $date); ?></div>
      <div class="wojo divided feed">
         <?php foreach ($rows as $row): ?>
            <div class="wojo event">
               <div class="label">
                  <div class="wojo attached basic segment center-align" style="background:<?php echo $row->color; ?>">
                     <span class="text-color-white text-weight-600 text-size-big"><?php echo Date::doDate('dd', $row->date_start); ?></span>
                     <p class="text-color-white"><?php echo Date::doDate('MMM', $row->date_start); ?></p>
                  </div>
               </div>
               <div class="content">
                  <div class="summary align-middle">
                     <?php echo $row->title; ?>
                     <div class="date">
                        <i class="icon time"></i>
                        <?php echo $row->time_start; ?> - <?php echo $row->time_end; ?></div>
                  </div>
                  <div class="text">
                     <?php echo Url::out_url($row->body); ?>
                  </div>
                  <div class="meta">
                     <div class="wojo small horizontal divided list">
                        <div class="item">
                           <i class="icon primary calendar"></i>
                           <?php echo Date::doDate('short_date', $row->date_start); ?>
                           <?php if ($row->date_end > $row->date_start): ?>
                              - <?php echo Date::doDate('short_date', $row->date_end); ?>
                           <?php endif; ?>
                        </div>
                        <?php if ($row->venue): ?>
                           <div class="item">
                              <i class="icon primary geo alt"></i>
                              <?php echo $row->venue; ?>
                           </div>
                        <?php endif; ?>
                        <?php if ($row->contact_phone): ?>
                           <div class="item">
                              <i class="icon primary phone"></i>
                              <?php echo $row->contact_phone; ?>
                           </div>
                        <?php endif; ?>
                        <?php if ($row->contact_person): ?>
                           <div class="item">
                              <i class="icon primary person"></i>
                              <?php echo $row->contact_person; ?>
                           </div>
                        <?php endif; ?>
                     </div>
                  </div>
               </div>
            </div>
         <?php endforeach; ?>
      </div>
   <?php endforeach; ?>
<?php else: ?>
   <?php echo Message::msgSingleInfo(Language::$word->_MOD_EM_NOEVENTSF); ?>
<?php endif; ?>