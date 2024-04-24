<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 6/9/2023 1:32 PM Gewa Exp $
    *
    */

   use Wojo\Language\Language;
   use Wojo\Message\Message;
   use Wojo\Module\Faq\Faq;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   $data = Faq::render();
   $cats = Faq::categoryTree();

?>
   <div class="center-align margin-bottom">
      <h3><?php echo Language::$word->_MOD_FAQ_SUB; ?></h3>
      <p><?php echo Language::$word->_MOD_FAQ_INFO; ?></p>
   </div>
<?php if ($data): ?>
   <div class="row gutters">
      <div class="columns relative screen-20 tablet-20 mobile-hide phone-hide">
         <?php if ($cats): ?>
            <div class="wojo native sticky">
               <div class="wojo nav list" id="navFaq">
                  <?php foreach ($cats as $crow): ?>
                     <div class="item">
                        <a href="#cat_<?php echo $crow->{'name' . Language::$lang}; ?>" data-parent="#navFaq" data-scroll="true" data-offset="150"><?php echo $crow->{'name' . Language::$lang}; ?></a>
                     </div>
                  <?php endforeach; ?>
               </div>
            </div>
         <?php endif; ?>
      </div>
      <div class="columns screen-80 tablet-80 mobile-100 phone-100" id="context">
         <?php foreach ($data as $cat): ?>
            <h5>
               <?php echo $cat['name']; ?>
            </h5>
            <?php foreach ($cat['items'] as $row) : ?>
               <div class="wojo accordion" id="cat_<?php echo $cat['name']; ?>">
                  <section>
                     <h6 class="summary">
                        <a><?php echo $row['question']; ?></a>
                     </h6>
                     <div class="details">
                        <?php echo $row['answer']; ?>
                     </div>
                  </section>
               </div>
            <?php endforeach; ?>
         <?php endforeach; ?>
      </div>
   </div>
<?php else: ?>
   <?php echo Message::msgSingleInfo(Language::$word->_MOD_FAQ_NOFAQF); ?>
<?php endif; ?>