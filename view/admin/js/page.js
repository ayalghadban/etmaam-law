(function ($) {
   'use strict';
   $.Page = function (settings) {
      const config = {
         url: '',
         lang: {
            nomemreq: '',
            select: '',

         }
      };
      if (settings) {
         $.extend(config, settings);
      }
      let $membership = $('#membership');

      $('#access_id').on('change', function () {
         const type = $(this).val();
         if (type === 'Membership') {
            $.get(config.url, {
               action: 'membershipList',
               type: type,
            }, function (json) {
               if (json.status === 'success') {
                  let html = '<a data-wdropdown="#membership_id" class="wojo secondary right fluid button">' + config.lang.select + '<i class="icon chevron down"></i></a>';
                  html += '<div class="wojo static dropdown small pointing top-left" id="membership_id">';
                  html += '<div class="row grid phone-1 mobile-1 tablet-2 screen-2">';
                  html += json.html;
                  html += '</div></div>';
                  $membership.html(html);
               }
            }, 'json');
         } else {
            $membership.html('<input disabled="disabled" type="text" placeholder="' + config.lang.nomemreq + '" name="na">');
         }
      });

      $('.removebg').on('click', function () {
         const parent = $(this).prev('input');
         $(parent).val('');
      });

      $('.mason .items').on('click', 'img', function () {
         $('.mason .items').find('img').removeClass('shadow boxed rounded');
         const parent = $(this).parent();
         let value = parent.data('img');
         $(this).addClass('shadow boxed rounded');
         $('input[name=main_image]').val(value);
      });

   };
})(jQuery);