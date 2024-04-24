<?php
   /**
    * _user_history
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: _user_history.tpl.php, v1.00 5/10/2023 1:48 PM Gewa Exp $
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
<div class="row justify-end">
   <div class="columns auto mobile-100 phone-100">
      <a href="<?php echo ADMINURL . 'users/action/?action=exportPayments&amp;id=' . $this->data->id; ?>"
         class="wojo secondary fluid button">
         <i class="icon wysiwyg table"></i><?php echo Language::$word->EXPORT; ?></a>
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
<div class="wojo tabs">
   <ul class="nav">
      <li class="active">
         <a data-tab="mem">
            <i class="icon person badge"></i>
            <?php echo Language::$word->ADM_MEMBS; ?></a>
      </li>
      <li>
         <a data-tab="pay">
            <i class="icon credit card"></i>
            <?php echo Language::$word->TRX_PAY; ?></a>
      </li>
   </ul>
   <div class="wojo tab">
      <div data-tab="mem" class="item">
         <table class="wojo responsive basic table">
            <thead>
            <tr>
               <th><?php echo Language::$word->NAME; ?></th>
               <th><?php echo Language::$word->MEM_ACT; ?></th>
               <th><?php echo Language::$word->MEM_EXP; ?></th>
               <th class="auto"><?php echo Language::$word->MEM_REC1; ?></th>
            </tr>
            </thead>
            <?php if ($this->mlist): ?>
               <?php foreach ($this->mlist as $mrow): ?>
                  <tr>
                     <td>
                        <a href="<?php echo Url::url('/admin/memberships/edit', $mrow->membership_id); ?>"><?php echo $mrow->title; ?></a>
                     </td>
                     <td><?php echo Date::doDate('long_date', $mrow->activated); ?></td>
                     <td><?php echo Date::doDate('long_date', $mrow->expire); ?></td>
                     <td class="center-align"><?php echo Utility::isPublished($mrow->recurring); ?></td>
                  </tr>
               <?php endforeach; ?>
            <?php endif; ?>
         </table>
      </div>
      <div data-tab="pay" class="item">
         <table class="wojo responsive basic table">
            <thead>
            <tr>
               <th><?php echo Language::$word->NAME; ?></th>
               <th><?php echo Language::$word->TRX_AMOUNT; ?></th>
               <th><?php echo Language::$word->TRX_TAX; ?></th>
               <th><?php echo Language::$word->TRX_COUPON; ?></th>
               <th><?php echo Language::$word->TRX_TOTAMT; ?></th>
               <th><?php echo Language::$word->CREATED; ?></th>
               <th class="auto"><?php echo Language::$word->STATUS; ?></th>
            </tr>
            </thead>
            <?php if ($this->plist): ?>
               <?php foreach ($this->plist as $prow): ?>
                  <tr>
                     <td>
                        <a href="<?php echo Url::url('/admin/memberships/edit', $prow->membership_id); ?>"><?php echo $prow->title; ?></a>
                     </td>
                     <td><?php echo $prow->rate_amount; ?></td>
                     <td><?php echo $prow->tax; ?></td>
                     <td><?php echo $prow->coupon; ?></td>
                     <td><?php echo $prow->total; ?></td>
                     <td><?php echo Date::doDate('short_date', $prow->created); ?></td>
                     <td class="center-align"><?php echo Utility::isPublished($prow->status); ?></td>
                  </tr>
               <?php endforeach; ?>
            <?php endif; ?>
         </table>
         <div class="wojo small primary inverted label"><?php echo Language::$word->TRX_TOTAMT; ?>
            <?php echo Utility::formatMoney(Stats::doArraySum($this->plist, 'total')); ?></div>
      </div>
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
         url: "<?php echo ADMINURL . 'users/action/';?>",
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
            gridTextSize: 12,
            fillOpacity: '.75',
            hideHover: 'auto',
            preUnits: json.preUnits,
            hoverCallback: function (index, json, content) {
               const text = $(content)[1].textContent;
               return content.replace(text, text.replace(json.preUnits, ""));
            },
            smooth: true,
            resize: true,
         });
         $("#payment_chart").parent().removeClass('loading');
      });
   });
   // ]]>
</script>