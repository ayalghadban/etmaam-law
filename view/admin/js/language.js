(function ($) {
   'use strict';
   $.Language = function (settings) {
      const config = {
         url: '',
      };
      if (settings) {
         $.extend(config, settings);
      }

      $('#filter').on('keyup', function () {
         let filter = $(this).val();
         $('span[data-editable=true]').each(function () {
            if ($(this).text().search(new RegExp(filter, 'i')) < 0) {
               $(this).parents('tr').fadeOut();
            } else {
               $(this).parents('tr').fadeIn();
            }
         });
      });

      $('#pgroup').on('change', function () {
         const $element = $('#pgroup option:selected');
         const sel = $element.val();
         const type = $element.data('type');
         const abbr = $(this).data('abbr');
         const $edt = $('#editable');
         $.get(config.url + 'languages/action/', {
            action: 'section',
            type: type,
            section: sel,
            abbr: abbr
         }, function (json) {
            $edt.html(json.html).fadeIn('slow');
            $edt.editableTableWidget();
         }, 'json');
      });

      $('#group').on('change', function () {
         const $element = $('#group option:selected');
         const sel = $element.val();
         const type = $element.data('type');
         const $edt = $('#editable');
         let abbr = $(this).data('abbr');
         let key = $element.data('key');
         $('#group').parent().addClass('loading');
         if (sel === 'all') {
            window.location.href = $.url().attr('source');
         }
         $.get(config.url + 'languages/action/', {
            action: 'file',
            type: type,
            key: key,
            section: sel,
            abbr: abbr
         }, function (json) {
            if (json.type === 'success') {
               $edt.html(json.html).fadeIn('slow');
               $edt.editableTableWidget();
            } else {
               $.wNotice({
                  autoclose: 12000,
                  type: json.type,
                  title: json.title,
                  text: decodeURIComponent(json.message)
               });
            }

            $('#group').parent().removeClass('loading');
         }, 'json');

      });

      $('.colorButton').wojocolors({
         opacity: 0,
         format: 'hex',
         mode: 'swatches',
         theme: 'wojo input color',
         swatches: ['#1abc9c', '#16a085', '#2ecc71', '#27ae60', '#3498db', '#2980b9', '#9b59b6', '#8e44ad', '#34495e', '#2c3e50', '#f1c40f', '#f39c12', '#e67e22', '#d35400', '#e74c3c', '#c0392b', '#ecf0f1', '#bdc3c7', '#95a5a6', '#7f8c8d'],
         change: function (color) {
            let id = $(this).data('id');
            let data = {
               action: 'color',
               color: color,
               id: id
            };
            $.post(config.url + 'languages/action/', data);

         },
      });
   };
})(jQuery);