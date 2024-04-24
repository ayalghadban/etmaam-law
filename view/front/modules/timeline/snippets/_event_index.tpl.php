<?php
   /**
    * _event_index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @var object $settings
    * @var object $result
    * @version 6.20: _event_index.tpl.php, v1.00 12/5/2023 1:06 PM Gewa Exp $
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
               <div class="margin-bottom">
                  <div class="margin-small-bottom">
                     <span class="wojo primary inverted label"><?php echo $row->year; ?></span>
                     <span class="wojo primary inverted label"><?php echo Date::doDate('MMMM', $row->created); ?></span>
                     <span class="wojo primary inverted label"><?php echo $row->venue; ?></span>
                  </div>
                  <h5><?php echo $row->title; ?></h5>
                  <p class="text-size-small"><span><?php echo $row->contact; ?></span> | <span><?php echo $row->phone; ?></span></p>
               </div>
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
                  type: 'event',
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