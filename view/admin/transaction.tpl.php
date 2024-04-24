<?php
   /**
    * transaction
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: transaction.tpl.php, v1.00 5/4/2023 9:07 PM Gewa Exp $
    *
    */

   use Wojo\Core\Router;
   use Wojo\Date\Date;
   use Wojo\Language\Language;
   use Wojo\Stats\Stats;
   use Wojo\Url\Url;
   use Wojo\Utility\Utility;

   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   $this->auth::checkOwner();
?>
<div class="wojo top attached card" id="pData">
   <div class="content">
      <div class="row small-gutters align-middle">
         <div class="columns auto">
            <a data-wdropdown="#dropdown-timeRange" class="wojo primary icon button" id="timeRange">
               <i class="icon three dots vertical"></i>
            </a>
            <div class="wojo small dropdown pointing menu top-left" id="dropdown-timeRange">
               <a class="item" data-value="all"><?php echo Language::$word->ALL; ?></a>
               <a class="item" data-value="day"><?php echo Language::$word->TODAY; ?></a>
               <a class="item" data-value="week"><?php echo Language::$word->THIS_WEEK; ?></a>
               <a class="item" data-value="month"><?php echo Language::$word->THIS_MONTH; ?></a>
               <a class="item" data-value="year"><?php echo Language::$word->THIS_YEAR; ?></a>
            </div>
         </div>
         <div class="columns right-align">
            <div id="legend" class="wojo small horizontal list"></div>
         </div>
      </div>
      <div id="payment_chart" class="height400"></div>
   </div>
</div>
<div class="wojo form margin-top">
   <form method="get" id="wojo_form" action="<?php echo Url::url(Router::$path); ?>" name="wojo_form">
      <div class="row align-middle justify-center gutters">
         <div class="columns screen-30 tablet-40 mobile-100 phone-100">
            <div class="wojo icon input">
               <input name="fromdate" type="text" placeholder="<?php echo Language::$word->FROM; ?>" readonly id="fromdate">
               <i class="icon calendar range"></i>
            </div>
         </div>
         <div class="columns screen-30 tablet-40 mobile-100 phone-100">
            <div class="wojo action input">
               <input name="enddate" type="text" placeholder="<?php echo Language::$word->TO; ?>" readonly id="enddate">
               <button id="doDates" class="wojo icon primary inverted button">
                  <i class="icon search"></i>
               </button>
            </div>
         </div>
         <div class="columns auto phone-hide">
            <a href="<?php echo Url::url(Router::$path); ?>" class="wojo secondary icon button">
               <i class="icon time history"></i>
            </a>
         </div>
         <div class="columns auto phone-hide">
            <a href="<?php echo ADMINURL . 'transactions/action/?action=export' . Url::query(); ?>" class="wojo primary icon button">
               <i class="icon wysiwyg table"></i>
            </a>
         </div>
      </div>
   </form>
</div>
<?php if (!$this->data): ?>
   <div class="center-align">
      <img src="<?php echo ADMINVIEW; ?>images/empty.svg" alt="" class="wojo big inline image">
      <div class="margin-small-top">
         <p class="wojo small icon alert inverted attached compact message">
            <i class="icon exclamation triangle"></i><?php echo Language::$word->SYSTEM_ERR3; ?></p>
      </div>
   </div>
<?php else: ?>
   <div class="wojo simple segment margin-bottom">
      <table class="wojo basic responsive table">
         <thead>
         <tr>
            <th class="disabled center-align">
               <i class="icon disabled id"></i>
            </th>
            <th><?php echo Language::$word->ITEM; ?></th>
            <th><?php echo Language::$word->USER; ?></th>
            <th><?php echo Language::$word->TRX_PP; ?></th>
            <th><?php echo Language::$word->TRX_TOTAMT; ?></th>
            <th><?php echo Language::$word->CREATED; ?></th>
         </tr>
         </thead>
         <?php foreach ($this->data as $row): ?>
            <tr id="item_<?php echo $row->id; ?>">
               <td class="auto">
                  <span class="wojo small dark inverted label"><?php echo $row->id; ?></span>
               </td>
               <td><?php echo $row->title; ?></td>
               <td><?php echo $row->name; ?></td>
               <td><?php echo $row->pp; ?></td>
               <td><?php echo $row->total; ?></td>
               <td data-sort-value="<?php echo strtotime($row->created); ?>"><?php echo Date::doDate('short_date', $row->created); ?></td>
            </tr>
         <?php endforeach; ?>
      </table>
      <div class="wojo small primary inverted label"><?php echo Language::$word->TRX_TOTAMT; ?><?php echo Utility::formatMoney(Stats::doArraySum($this->data, 'total')); ?></div>
   </div>
   <div class="margin-top padding-small-horizontal">
      <div class="row gutters align-middle">
         <div class="columns mobile-100 phone-100">
            <div class="text-size-small text-weight-500"><?php echo Language::$word->TOTAL . ': ' . $this->pager->items_total; ?>
               / <?php echo Language::$word->CURPAGE . ': ' . $this->pager->current_page . ' ' . Language::$word->OF . ' ' . $this->pager->num_pages; ?></div>
         </div>
         <div class="columns mobile-100 phone-100 auto"><?php echo $this->pager->display(); ?></div>
      </div>
   </div>
<?php endif; ?>
<script type="text/javascript" src="<?php echo SITEURL; ?>assets/morris.min.js"></script>
<script type="text/javascript" src="<?php echo SITEURL; ?>assets/raphael.min.js"></script>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      function getStats(range) {
         $("#pData").addClass('loading');
         $("#payment_chart").empty();
         $.ajax({
            type: 'GET',
            url: "<?php echo ADMINURL . 'transactions/action/';?>",
            data: {
               action: "sales",
               timerange: range
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
               gridTextWeight: 500,
               gridTextColor: "rgba(0,0,0,0.6)",
               gridTextSize: 14,
               fillOpacity: '.1',
               hideHover: 'auto',
               preUnits: json.preUnits,
               hoverCallback: function (index, json, content) {
                  let text = $(content)[1].textContent;
                  return content.replace(text, text.replace(json.preUnits, ""));
               },
               smooth: true,
               resize: true,
            });
            $("#pData").removeClass('loading');
         });
      }

      getStats('all');

      $("#dropdown-timeRange").on('click', '.item', function () {
         $("#payment_chart").html('');
         getStats($(this).data('value'));
      });
   });
   // ]]>
</script>