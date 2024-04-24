<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 6/26/2023 11:16 AM Gewa Exp $
    *
    */
   
   use Wojo\Date\Date;
   use Wojo\Language\Language;
   use Wojo\Plugin\Rss\Rss;
   
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<?php if ($row = Rss::getRssById($this->properties['plugin_id'])) : ?>
   <?php $items = Rss::render($row->url, $row->items); ?>
   <div class="wojo feed">
      <?php for ($x = 0;$x < $items[1];$x++) : ?>
         <?php $title = str_replace(' & ', ' &amp; ', $items[0][$x]['title']); ?>
         <?php $link = $items[0][$x]['link']; ?>
         <?php $content = $items[0][$x]['content']; ?>
         <?php $has_image = preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $content, $image); ?>
         <div class="event">
            <?php if ($has_image == 1) : ?>
               <div class="label">
                  <img src="<?php echo $image['src']; ?>" alt=""/>
               </div>
            <?php endif; ?>
            <div class="content">
               <div class="summary">
                  <a href="<?php echo $link; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a>
                  <?php $date = ($row->show_date)? Date::doDate('long_date', $items[0][$x]['date']) : null; ?>
                  <small class="date"><em><?php echo Language::$word->_PLG_RSS_POST_DATE . ' ' . $date; ?></em></small>
               </div>
               <?php if ($row->show_desc): ?>
                  <?php $description = $items[0][$x]['desc']; ?>
                  <?php $description = strip_tags($description, ''); ?>
                  <?php if ($row->max_words > 0): ?>
                     <?php $arr = explode(' ', $description); ?>
                     <?php if ($row->max_words < count($arr)): ?>
                        <?php $description = ''; ?>
                        <?php $w_cnt = 0; ?>
                        <?php foreach ($arr as $w) : ?>
                           <?php $description .= $w . ' '; ?>
                           <?php $w_cnt = $w_cnt + 1; ?>
                           <?php if ($w_cnt == $row->max_words) : ?>
                              <?php break; ?>
                           <?php endif; ?>
                        <?php endforeach; ?>
                        <?php $description .= ' ...'; ?>
                     <?php endif; ?>
                  <?php endif; ?>
                  <div class="extra text"><?php echo $description; ?> </div>
                  <div class="meta">
                     <a href="<?php echo $link; ?>" title="<?php echo $title; ?>"><?php echo Language::$word->CONTINUE_R; ?> &raquo;</a>
                  </div>
               <?php endif; ?>
            </div>
         </div>
      <?php endfor; ?>
   </div>
<?php endif; ?>
