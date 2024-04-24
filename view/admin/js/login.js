(function ($) {
   'use strict';
   $.Login = function (settings) {
      const config = {
         aurl: 'adminurl',
         surl: 'siteurl',
         lang: {
            now: 'Now',

         }
      };

      const $lf = $('#loginform');
      const $pf = $('#passform');
      if (settings) {
         $.extend(config, settings);
      }


      $('#backto').on('click', function () {
         $lf.slideDown();
         $pf.slideUp();
      });
      $('#passreset').on('click', function () {
         $lf.slideUp();
         $pf.slideDown();
      });

      $('#doSubmit').on('click', function () {
         const $btn = $(this);
         $btn.addClass('loading').prop('disabled', true);
         const username = $('input[name=username]').val();
         const password = $('input[name=password]').val();
         $.ajax({
            type: 'post',
            url: config.surl + 'login/',
            data: {
               action: 'login',
               username: username,
               password: password
            },
            dataType: 'json',
            success: function (json) {
               if (json.type === 'error') {
                  $.wNotice({
                     autoclose: 6000,
                     type: json.type,
                     title: json.title,
                     text: json.message
                  });
               } else {
                  window.location.href = (json.user === 'member') ? config.surl + 'dashboard/' : config.aurl;
               }
               $btn.removeClass('loading').prop('disabled', false);
            }
         });
      });

      $('#dopass').on('click', function () {
         const $btn = $(this);
         $btn.addClass('loading');
         const email = $('input[name=pEmail]').val();
         const fname = $('input[name=fname]').val();
         $.ajax({
            type: 'post',
            url: config.surl + 'login/action/',
            data: {
               action: 'reset',
               email: email,
               fname: fname
            },
            dataType: 'json',
            success: function (json) {
               $.wNotice({
                  autoclose: 6000,
                  type: json.type,
                  title: json.title,
                  text: json.message
               });
               if (json.type === 'success') {
                  $btn.prop('disabled', true);
                  $('input[name=pEmail]').val('');
               }
               $btn.removeClass('loading');
            }
         });
      });
   };
})(jQuery);