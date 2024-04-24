(function ($) {
   "use strict";
   $.Poll = function (settings) {
      const config = {
         url: "",
         lang: {
            optext: "",

         }
      };
      if (settings) {
         $.extend(config, settings);
      }
      const $item = $("#item");
      $('#btnAdd').on('click', function () {
         const html = ('' +
           '<div class="field new">' +
           '<div class="wojo icon input">' +
           '<input type="text" placeholder="' + config.lang.optext + '" value="' + config.lang.optext + '" name="value[]">' +
           '<i class="icon negative x alt selectable"></i>' +
           '</div>' +
           '</div>');
         $('#item').append(html);
      });
      $item.on('click', '.icon.x', function () {
         $(this).parents('.field').remove();
         const id = $(this).prev('input').data('id');
         if ($.inArray("edit", $.url().segment()) && id) {
            $.post(config.url, {
               action: "delete",
               type: "option",
               id: id
            });
         }
      });
      let timeout;
      $item.on('keyup', '.old input', function () {
         window.clearTimeout(timeout);
         const value = $(this).val();
         const id = $(this).data('id');
         if (value.length > 1) {
            timeout = window.setTimeout(function () {
               $.post(config.url, {
                  action: "rename",
                  id: id,
                  value: value
               });
            }, 700);
         }
      });

   };
})(jQuery);