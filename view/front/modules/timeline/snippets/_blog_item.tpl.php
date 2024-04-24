<?php
   /**
    * _blog_item
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @var array $result
    * @copyright 2023
    * @version 6.20: _blog_item.tpl.php, v1.00 6/30/2023 11:31 AM Gewa Exp $
    *
    */

   use Wojo\Date\Date;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<?php foreach ($this->blog as $row): ?>
   <div class="items">
      <div class="wojo basic card">
         <div class="header divided">
            <div class="margin-small-bottom">
               <span class="wojo primary inverted label"><?php echo $row->year; ?></span>
               <span class="wojo primary inverted label"><?php echo Date::doDate('MMMM', $row->created); ?></span>
            </div>
            <h5 class="basic">
               <a href="<?php echo $row->link; ?>" class="dark"><?php echo $row->title; ?></a>
            </h5>
         </div>
         <?php if (count($row->thumb) > 1): ?>
            <div class="wojo carousel" data-wcarousel='{"autoplay":false,"dots":false,"loop":true, "arrows": true}'>
               <?php foreach ($row->thumb as $img): ?>
                  <img src="<?php echo $img; ?>" alt="<?php echo $row->title; ?>">
               <?php endforeach; ?>
            </div>
         <?php else: ?>
            <a href="<?php echo $row->link; ?>">
               <img src="<?php echo $row->thumb[0]; ?>" alt="<?php echo $row->title; ?>">
            </a>
         <?php endif; ?>
         <div class="content shadow-hard"><?php echo $row->content; ?></div>
      </div>
   </div>
<?php endforeach; ?>
