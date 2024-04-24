<?php
   /**
    * _blog_index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @var object $settings
    * @var object $result
    * @copyright 2023
    * @version 6.20: _blog_index.tpl.php, v1.00 6/30/2023 9:39 AM Gewa Exp $
    *
    */

   use Wojo\Date\Date;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

?>
<h3><?php echo $settings->name; ?></h3>
<div id="timeline" class="mason big <?php echo ($settings->colmode == 'dual')? 'two' : 'one'; ?>">
   <?php foreach ($result as $row): ?>
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
</div>
<input type="hidden" id="pageno" value="1">
<input type="hidden" id="pageid" value="0">
<div id="itemloader" class="wojo basic simple segment"></div>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      const $tl = $('#timeline');
      const $il = $('#itemloader');
      $il.on('inview', function (event, isInView) {
         if (isInView) {
            let nextPage = parseInt($('#pageno').val()) + 1;
            $il.addClass('loading');
            $.ajax({
               type: 'POST',
               url: "<?php echo SITEURL . 'timeline/action/';?>",
               data: {
                  action: 'pagination',
                  type: 'blog',
                  perpage: '<?php echo $settings->showmore; ?>',
                  maxitems: '<?php echo $settings->maxitems; ?>',
                  id: $('#pageid').val(),
                  pageno: nextPage
               },
               dataType: 'json',
               success: function (json) {
                  if (json.status === 'success') {
                     setTimeout(function () {
                        $tl.append(json.html);
                        $('.wojo.carousel', $tl).not('.slick-initialized').each(function() {
                           let set = $(this).data('wcarousel');
                           $(this).slick(set);
                        });
                        $il.removeClass('loading');
                     }, 1000);
                     $('#pageno').val(nextPage);
                  } else {
                     $il.hide();
                  }
               }
            });
         }
      });
   });
   // ]]>
</script>