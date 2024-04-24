<?php
   /**
    * _memberships_history
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _memberships_history.tpl.php, v1.00 5/10/2023 4:08 PM Gewa Exp $
    *
    */

   use Wojo\Date\Date;
   use Wojo\Language\Language;
   use Wojo\Stats\Stats;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<?php if (!$this->plist): ?>
   <div class="center-align">
      <img src="<?php echo ADMINVIEW; ?>images/empty.svg" alt="" class="wojo big inline image">
      <div class="margin-small-top">
         <p class="wojo small icon alert inverted attached compact message">
            <i
              class="icon exclamation triangle"></i><?php echo Language::$word->SYSTEM_ERR3; ?></p>
      </div>
   </div>
<?php else: ?>
   <div class="row justify-end">
      <div class="columns auto mobile-100 phone-100">
         <a href="<?php echo ADMINURL . 'memberships/action/?action=export&amp;id=' . $this->data->id; ?>"
            class="wojo small secondary fluid button">
            <i class="icon wysiwyg table"></i><?php echo Language::$word->EXPORT; ?>
         </a>
      </div>
   </div>
   <div class="wojo spaced card">
      <div class="content">
         <div class="right-align">
            <div id="legend" class="wojo small horizontal list"></div>
         </div>
         <div id="payment_chart" class="height300"></div>
      </div>
   </div>
   <table class="wojo basic responsive table">
      <thead>
      <tr>
         <th><?php echo Language::$word->USER; ?></th>
         <th><?php echo Language::$word->TRX_AMOUNT; ?></th>
         <th><?php echo Language::$word->TRX_TAX; ?></th>
         <th><?php echo Language::$word->TRX_COUPON; ?></th>
         <th><?php echo Language::$word->TRX_TOTAMT; ?></th>
         <th><?php echo Language::$word->CREATED; ?></th>
      </tr>
      </thead>
      <?php foreach ($this->plist as $row): ?>
         <tr>
            <td>
               <a href="<?php echo Url::url('admin/users/edit', $row->user_id); ?>"><?php echo $row->name; ?></a>
            </td>
            <td><?php echo $row->rate_amount; ?></td>
            <td><?php echo $row->tax; ?></td>
            <td><?php echo $row->coupon; ?></td>
            <td><?php echo $row->total; ?></td>
            <td data-sort-value="<?php echo strtotime($row->created); ?>"
                class="auto"><?php echo Date::doDate('short_date', $row->created); ?></td>
         </tr>
      <?php endforeach; ?>
   </table>
   <div class="wojo small primary inverted label"><?php echo Language::$word->TRX_TOTAMT; ?>
      <?php echo Utility::formatMoney(Stats::doArraySum($this->plist, 'total')); ?>
   </div>
   <div class="padding-small-horizontal">
      <div class="row gutters align-middle">
         <div class="columns auto mobile-100 phone-100">
            <div class="text-size-small text-weight-500"><?php echo Language::$word->TOTAL . ': ' . $this->pager->items_total; ?>
               / <?php echo Language::$word->CURPAGE . ': ' . $this->pager->current_page . ' ' . Language::$word->OF . ' ' . $this->pager->num_pages; ?></div>
         </div>
         <div class="columns mobile-100 right-align"><?php echo $this->pager->display(); ?></div>
      </div>
   </div>
   <script src="<?php echo SITEURL; ?>assets/morris.min.js"></script>
   <script src="<?php echo SITEURL; ?>assets/raphael.min.js"></script>
   <script type="text/javascript">
      // <![CDATA[
      $(document).ready(function () {
         $("#payment_chart").parent().addClass('loading');
         $.ajax({
            type: 'GET',
            url: "<?php echo ADMINURL . 'memberships/action/';?>",
            data: {
               id: $.url().segment(-1),
               action: "chart"
            },
            dataType: 'json'
         }).done(function (json) {
            let legend = '';
            json.legend.map(function (val) {
               legend += val;
            });
            $("#legend").html(legend);
            Morris.Line({
               element: 'payment_chart',
               data: json.data,
               xkey: 'm',
               ykeys: json.label,
               labels: json.label,
               parseTime: false,
               lineWidth: 4,
               pointSize: 6,
               lineColors: json.color,
               gridTextFamily: "Wojo Sans",
               gridTextColor: "rgba(0,0,0,0.6)",
               gridTextWeight: 500,
               gridTextSize: 12,
               fillOpacity: '.75',
               hideHover: 'auto',
               preUnits: json.preUnits,
               smooth: true,
               resize: true,
            });
            $("#payment_chart").parent().removeClass('loading');
         });
      });
      // ]]>
   </script>
<?php endif; ?>