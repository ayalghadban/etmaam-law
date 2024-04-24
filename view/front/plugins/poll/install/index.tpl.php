<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 6/14/2023 9:55 AM Gewa Exp $
    *
    */

   use Wojo\Core\Session;
   use Wojo\Language\Language;
   use Wojo\Plugin\Poll\Poll;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

?>
<!-- Poll -->
<?php if ($settings = Utility::findInArray($this->properties['all'], 'id', $this->properties['id'])): ?>
   <div class="wojo poll plugin segment<?php echo ($settings[0]->alt_class)? ' ' . $settings[0]->alt_class : null; ?>">
      <?php if ($settings[0]->show_title): ?>
         <h3 class="center-align"><?php echo $settings[0]->title; ?></h3>
      <?php endif; ?>
      <?php if ($settings[0]->body): ?>
         <?php echo Url::out_url($settings[0]->body); ?>
      <?php endif; ?>
      <?php if ($data = Poll::render($this->properties['plugin_id'])): ?>
         <?php $voted = Session::cookieExists('CMSPRO_voted', $settings[0]->plugin_id); ?>
         <?php foreach ($data as $rows): ?>
            <h5 class="center-align"><?php echo $rows->name; ?></h5>
            <div class="wojo relaxed celled list pollResult margin-top" style="display:<?php echo $voted? 'block' : 'none'; ?>">
               <?php foreach ($rows->opts as $i => $row): ?>
                  <?php $percent = Utility::doPercent($row->total, $rows->totals); ?>
                  <div class="item relative">
                     <div class="content"><?php echo $row->value; ?></div>
                     <div class="content auto">
                        <span data-total-id="<?php echo $row->oid; ?>" class="wojo small secondary inverted label"><?php echo $row->total; ?></span>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
            <div class="wojo relaxed celled list pollDisplay margin-top" style="display:<?php echo $voted? 'none' : 'block'; ?>">
               <?php foreach ($rows->opts as $i => $row): ?>
                  <a class="item align-middle dovote" data-poll='{"id":<?php echo $rows->id; ?>,"oid":<?php echo $row->oid; ?>,"total":<?php echo $row->total; ?>}'>
                     <i class="icon bar chart alt"></i>
                     <div class="content">
                        <div class="header"><?php echo $row->value; ?></div>
                     </div>
                  </a>
               <?php endforeach; ?>
            </div>
         <?php endforeach; ?>
         <div class="center-align padding-top hide-all goBack">
            <a class="text-size-small pollBack"><?php echo Language::$word->BACK; ?></a>
         </div>
         <div class="center-align padding-top goFront" style="display:<?php echo $voted? 'none' : 'block'; ?>">
            <a class="wojo small primary button pollVote spaced"><?php echo Language::$word->_PLG_PL_VOTE; ?></a>
            <a class="text-color-primary pollView"><?php echo Language::$word->_PLG_PL_RESULT; ?></a>
         </div>
      <?php endif; ?>
   </div>
<?php endif; ?>