<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 12/4/2023 6:47 PM Gewa Exp $
    *
    */

   use Wojo\Date\Date;
   use Wojo\Module\Event\Event;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;
   use Wojo\Validator\Validator;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<?php if ($settings = Utility::findInArray($this->properties['all'], 'id', $this->properties['id'])): ?>
   <div class="wojo plugin segment<?php echo ($settings[0]->alt_class)? ' ' . $settings[0]->alt_class : null; ?>">
      <?php if ($settings[0]->show_title): ?>
         <h5><?php echo $settings[0]->title; ?></h5>
      <?php endif; ?>
      <?php if ($settings[0]->body): ?>
         <?php echo Url::out_url($settings[0]->body); ?>
      <?php endif; ?>
      <?php if ($data = Event::renderEvent()): ?>
         <div class="wojo relaxed divided list">
            <?php foreach ($data as $row): ?>
               <div class="item">
                  <div class="content">
                     <div class="wojo label" style="background-color:<?php echo $row->color; ?>;border-color:<?php echo $row->color; ?>">
                        <?php echo Date::doDate('MMM', $row->date_start); ?>
                        <?php echo Date::doDate('YYYY', $row->date_start); ?>
                     </div>
                     <h6 class="margin-top"><?php echo $row->title; ?></h6>
                     <i class="icon map marker"></i>
                     <?php echo $row->venue; ?> @<?php echo Date::doTime($row->time_start); ?>
                     <p class="text-size-small">
                        <?php echo Validator::sanitize($row->body, 'default', 70); ?>
                     </p>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>
         <?php unset($row); ?>
      <?php endif; ?>
   </div>
   <?php if ($settings[0]->jscode): ?>
      <script><?php echo $settings[0]->jscode; ?></script>
   <?php endif; ?>
<?php endif; ?>
