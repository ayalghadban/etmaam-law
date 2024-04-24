(function ($) {
   'use strict';
   $.Index = function (settings) {
      const config = {
         is_dark: false
      };

      if (settings) {
         $.extend(config, settings);
      }

      $('#payment_chart').addClass('loading');
      $('#payment_chart2').addClass('loading');

      //counters
      $('.counter').each(function () {
         $(this).prop('Counter', 0).animate({
            Counter: $(this).text()
         }, {
            duration: 1500,
            step: function (now) {
               $(this).text(Math.ceil(now));
            }
         });
      });

      //chart
      /**
       * @property {string} totalsum
       * @property {string} totalsales
       * @property {string} amount_str
       * @property {string} sales_str
       */
      $.ajax({
         type: 'GET',
         url: config.url,
         data: {
            action: 'index'
         },
         dataType: 'json'
      }).done(function (json) {
         let legend = '';
         json.legend.map(function (val) {
            legend += val;
         });
         $('#legend').html(legend);
         Morris.Line({
            element: 'payment_chart',
            data: json.data,
            xkey: 'm',
            ykeys: json.label,
            labels: json.label,
            parseTime: false,
            gridTextFamily: 'Wojo Sans',
            gridTextWeight: 500,
            lineWidth: 4,
            pointSize: 6,
            lineColors: json.color,
            gridTextColor: config.is_dark ? 'rgba(255,255,255,0.6)' : 'rgba(0,0,0,0.6)',
            gridTextSize: 14,
            fillOpacity: '.75',
            hideHover: 'auto',
            preUnits: json.preUnits,
            behaveLikeLine: true,
            hoverCallback: function (index, json, content) {
               const text = $(content)[1].textContent;
               return content.replace(text, text.replace(json.preUnits, ''));
            },
            smooth: true,
            resize: true,
            xLabelAngle: 0,

         });
         $('#payment_chart').removeClass('loading');

         const chartOne = $('#chart1');
         const chartTwo = $('#chart2');
         $('#totalsum').html(json.totalsum);
         $('#totalsales span').html(json.totalsales);

         chartOne.attr('data-values', json.amount_str);
         chartTwo.attr('data-values', json.sales_str);

         const barValues1 = chartOne.attr('data-values').split(',');
         const barValueCount1 = barValues1.length;
         const barSpacing1 = 4;

         const barValues2 = chartTwo.attr('data-values').split(',');
         const barValueCount2 = barValues2.length;
         const barSpacing2 = 5;

         let chart2 = function () {
            chartTwo.sparkline(barValues2, {
               type: 'bar',
               height: 55,
               barWidth: Math.round((chartTwo.parent().width() - (barValueCount2 - 1) * barSpacing2) / barValueCount2),
               barSpacing: barSpacing2,
               zeroAxis: false,
               barColor: '#11cdef'
            });
         };
         let chart1 = function () {
            chartOne.sparkline(barValues1, {
               type: 'bar',
               height: 55,
               barWidth: Math.round((chartOne.parent().width() - (barValueCount1 - 1) * barSpacing1) / barValueCount1),
               barSpacing: barSpacing1,
               zeroAxis: false,
               barColor: '#026AA2'
            });
         };

         const sparkline = function () {
            $('.sparkline').sparkline('html', {
               enableTagOptions: true,
               tagOptionsPrefix: 'data'
            });
         };
         let sparkResize;
         $(window).resize(function () {
            clearTimeout(sparkResize);
            sparkResize = setTimeout(sparkline, 500);
            chart1();
            chart2();
         });

         sparkline();

         chart1();
         chart2();
      });

      //chart
      $.ajax({
         type: 'GET',
         url: config.url,
         data: {
            action: 'main'
         },
         dataType: 'json'
      }).done(function (json) {
         const data = json.data;
         json.legend.map(function (v) {
            return $('#legend2').append(v);
         });
         Morris.Area({
            element: 'payment_chart2',
            data: data,
            xkey: 'm',
            ykeys: json.label,
            labels: json.label,
            parseTime: false,
            gridTextFamily: 'Wojo Sans',
            gridTextWeight: 500,
            lineWidth: 4,
            pointSize: 6,
            lineColors: json.color,
            gridTextColor: config.is_dark ? 'rgba(255,255,255,0.6)' : 'rgba(0,0,0,0.6)',
            fillOpacity: '.65',
            hideHover: 'auto',
            xLabelAngle: 0,
            preUnits: json.preUnits,
            smooth: true,
            resize: true,
         });

         $('#payment_chart2').removeClass('loading');
      });

   };
})(jQuery);