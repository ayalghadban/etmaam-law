(function ($) {
   'use strict';
   
   $.Master = function (settings) {
      const config = {
         weekstart: 0,
         ampm: 0,
         url: '',
         lang: {
            monthsFull: '',
            monthsShort: '',
            weeksFull: '',
            weeksShort: '',
            weeksMed: '',
            today: 'Today',
            now: 'Now',
            button_text: 'Choose file...',
            empty_text: 'No file...',
            sel_pic: 'Choose image...',
         }
      };

      const $hh = $('header');
      const $slider = $('.wSlider');
      const $mr = $('#mResult')

      if (settings) {
         $.extend(config, settings);
      }

      /* == Navigation Menu == */
      $('.wojo.menu').wMenu({
         breakpoint: 768,
         showArrows: true,
         arrow: '<i class="icon plus alt"></i>'
      });

      /* == Vertical Menus == */
      $("ul.vertical-menu").find('ul.menu-submenu').parent().prepend('<i class=\"icon chevron down\"></i>');
      $('ul.vertical-menu').on('click','.chevron.down', function () {
         let icon = this;
         $(this).siblings('ul.vertical-menu ul.menu-submenu').slideToggle(200);
         $(icon).toggleClass('vertically flipped');
      });

      if ($hh.length) {
         $(window).on('scroll', function () {
            if ($(window).scrollTop() >= $hh.height()) {
               $hh.addClass('fixed');
            } else {
               $hh.removeClass('fixed');
            }
         });

         let scrollTop = $(window).scrollTop();
         if (scrollTop >= $hh.height()) {
            $hh.addClass('fixed');
         } else {
            $hh.removeClass('fixed');
         }
      }

      $('.wojo.progress').wProgress();
      $('.wojo.accordion').wAccordion();
      $('.wojo.input.number').wNumber();
      $('.wojo.backgrounds').wVback();

      //Lightbox
      $('.lightbox').wLightbox();

      /* == Tabs == */
      $('.wojo.tabs').wTabs();

      //Poll
      $('.poll').Poll({
         url: config.surl + 'plugin/poll/action/'
      });

      //Comments
      $('#comments').Comments({
         url: config.surl + 'comments/',
         murl: config.url + 'modules/comments/'
      });

      /* == Input focus == */
      $(document).on('focusout', '.wojo.input input, .wojo.input textarea', function () {
         $('.wojo.input').removeClass('focus');
      });
      $(document).on('focusin', '.wojo.input input, .wojo.input textarea', function () {
         $(this).closest('.input').addClass('focus');
      });

      $(document).find('[data-image]').each(function () {
         let img = $(this).attr('data-image');
         $(this).attr('style', 'background-image: url(' + config.surl + 'uploads/' + img + ')');
      });

      //wojo carousel
      $('.wojo.carousel').each(function() {
         let set = $(this).data('wcarousel');
         $(this).slick(set);
      });

      //wojo slider
      $slider.on('init', function () {
         $('.slick-active .ws-layer').each(function () {
            let animation = $(this).data('animation');
            $(this).addClass('animate ' + animation);
         });
      });

      $slider.each(function () {
         let set = $(this).data('wslider');
         $(this).slick({
            dots: set.dots,
            arrows: set.arrows,
            autoplay: set.autoplay
         });
         $(this).on('beforeChange', function () {
            $('.ws-layer', this).each(function () {
               let animation = $(this).data('animation');
               $(this).removeClass('animate ' + animation).addClass('hidden');
            });
         });

         $(this).on('afterChange', function (event, slick, slide) {
            let $active = $(slick.$slides.get(slide));
            $active.find('.ws-layer').each(function () {
               let animation = $(this).data('animation');
               $(this).addClass('animate ' + animation).removeClass('hidden');
            });
         });
      });

      if ($.browser.desktop) {
         $('[data-wanimate]').Aos();
      }

      /* == Scroll to element == */
      $(document).on('click', '[data-scroll="true"]', function (event) {
         event.preventDefault();
         event.stopPropagation();
         let target = $(this).attr('href');
         let offset = $(this).attr('data-offset');
         let parent = $(this).attr('data-parent');

         if (typeof parent !== 'undefined' && parent !== false) {
            $(parent).find('a').removeClass('active');
            $(this).addClass('active');
         }

         $('html,body').animate({
            scrollTop: $(target).offset().top - parseInt(offset)
         }, 1000);
         return false;
      });

      // Scroll to top
      $(window).scroll(function () {
         if ($(this).scrollTop() > 100) {
            $('#back-to-top').stop(true, true).fadeIn(500);
         } else {
            $('#back-to-top').stop(true, true).fadeOut(300);
         }
      });

      $('#back-to-top').on('click', function () {
         $('html,body').animate({
            scrollTop: $('body').offset().top
         }, 1000);
         return false;
      });

      /* == Clear Session Debug Queries == */
      $('#debug-panel').on('click', 'a.clear_session', function () {
         $.post(config.surl + 'ajax/', {
            action: 'debugSession'
         });
         $(this).css('color', '#222');
      });

      /* == Master Form == */
      $(document).on('click', 'button[name=dosubmit]', function () {
         let $button = $(this);
         let action = $(this).data('action');
         let $form = $(this).closest('form');
         let asseturl = $(this).data('url');
         let hide = $(this).data('hide');

         function showResponse(json) {
            setTimeout(function () {
               $($button).removeClass('loading').prop('disabled', false);
            }, 500);

            if (json.type === 'success' && json.redirect) {
               setTimeout(function () {
                  $('body').transition('scaleOut', {
                     duration: 600,
                     complete: function () {
                        window.location.href = json.redirect;
                     }
                  });
               }, 5000);
            }
            if (json.type === 'success' && hide) {
               $form.transition('fadeOut', {
                  duration: 5000,
                  complete: function () {
                     $form.hide();
                  }
               });
            }
            $.wNotice({
               autoclose: 12000,
               type: json.type,
               title: json.title,
               text: json.message
            });
         }

         function showLoader() {
            $($button).addClass('loading').prop('disabled', true);
         }

         let options = {
            target: null,
            beforeSubmit: showLoader,
            success: showResponse,
            type: 'post',
            url: config.surl + asseturl,
            data: {
               action: action
            },
            dataType: 'json'
         };

         $($form).ajaxForm(options).submit();
      });

      /* == Avatar Upload == */
      $('[data-type="image"]').wavatar({
         text: config.lang.sel_pic,
         validators: {
            maxWidth: 1200,
            maxHeight: 1200
         },
         reject: function (file, errors) {
            if (errors.mimeType) {
               $.wNotice({
                  autoclose: 4000,
                  type: 'error',
                  title: 'Error',
                  text: file.name + ' must be an image.'
               });
            }
            if (errors.maxWidth || errors.maxHeight) {
               $.wNotice({
                  autoclose: 4000,
                  type: 'error',
                  title: 'Error',
                  text: file.name + ' must be width:1200px, and height:1200px  max.'
               });
            }
         },
         accept: function () {
            if ($(this).data('process')) {
               let action = $(this).data('action');
               let data = new FormData();
               data.append(action, $(this).prop('files')[0]);
               data.append('action', 'avatar');

               $.ajax({
                  type: 'POST',
                  processData: false,
                  contentType: false,
                  data: data,
                  url: config.surl + 'dashboard/action/',
                  dataType: 'json',
               });
            }
         }
      });

      /* == Membership Select == */
      $('.add-membership').on('click', function () {
         $('#membershipSelect .card').removeClass('active');
         $(this).closest('.card').addClass('active');
         let id = $(this).data('id');
         $.post(config.surl + 'dashboard/action/', {
            action: 'membership',
            id: id
         }, function (json) {
            $mr.html(json.message);
            $('html,body').animate({
               scrollTop: $mr.offset().top
            }, 1000);
         }, 'json');
      });

      /* == Coupon Select == */
      /**
       * @property {string} tax
       * @property {string} gtotal
       * @property {string} is_full
       */
      $mr.on('click', '#cinput', function () {
         let id = $(this).data('id');
         let $this = $(this);
         let $icon = $(this).children();
         let $parent = $(this).parent();
         const $input = $('input[name=coupon]');
         const $disc = $('.disc');
         if (!$input.val()) {
            $parent.transition('shake');
         } else {
            $icon.removeClass('search').addClass('spinner circles spinning');
            $.post(config.surl + 'dashboard/action/', {
               action: 'coupon',
               id: id,
               code: $input.val()
            }, function (json) {
               if (json.type === 'success') {
                  $this.replaceWith('<a class="wojo small icon simple passive button"><i class="icon check"></i></a');
                  $input.prop('disabled', true);
                  $('.totaltax').html(json.tax);
                  $('.totalamt').html(json.gtotal);
                  $disc.html(json.disc);
                  if (json.is_full === 100) {
                     $('#activateCoupon').show();
                     $('#gateList').hide();
                  } else {
                     $('#activateCoupon').hide();
                     $('#gateList').show();
                  }
               } else {
                  $parent.transition('shake');
                  $icon.removeClass('spinner circles spinning').addClass('search');
               }
            }, 'json');
         }
      });

      /* == Activate Coupon == */
      $(document).on('click', '.activateCoupon', function () {
         const $this = $(this);
         $this.addClass('loading');
         $.post(config.surl + 'dashboard/action/', {
            action: 'activateCoupon',
         }, function (json) {
            if (json.type === 'success') {
               window.location.href = config.surl + 'dashboard/';
            }
            $this.removeClass('loading');
         }, 'json');
      });

      /* == Gateway Select == */
      $mr.on('click', '.sGateway', function() {
         let button = $(this);
         $('#mResult .sGateway').removeClass('primary');
         button.addClass('primary loading');
         let id = $(this).data('id');
         $.post(config.surl + 'dashboard/action/', {
            action: 'gateway',
            cache: false,
            id: id
         }, function(json) {
            $('#mResult #gdata').html(json.message);
            $('html,body').animate({
               scrollTop: $('#gdata').offset().top - 40
            }, 500);
            button.removeClass('loading');
         }, 'json');
      });

      /* == Login check == */
      if (config.loginCheck) {
         setTimeout(function () {
            $.post(config.surl + 'login/action/', {
               action: 'check',
            }, function (json) {
               if (json.type === 'error') {
                  window.location.href = config.surl + 'logout/';
               }
            }, 'json');
         }, 30 * 1000);
      }

      /* == Language Switcher == */
      $('#dropdown-langChange').on('click', 'a', function () {
         Cookies.set('LANG_CMSPRO', $(this).data('value'), {
            expires: 120,
            path: '/'
         });
         $('main').transition('scaleOut', {
            duration: 2000,
            complete: function () {
               window.location.href = config.surl;
            }
         });
         return false;
      });

      // convert logo svg to editable
      $('.logo img').each(function () {
         let $img = $(this);
         let imgID = $img.attr('id');
         let imgClass = $img.attr('class');
         let imgURL = $img.attr('src');

         $.get(imgURL, function (data) {
            let $svg = $(data).find('svg');
            if (typeof imgID !== 'undefined') {
               $svg = $svg.attr('id', imgID);
            }
            if (typeof imgClass !== 'undefined') {
               $svg = $svg.attr('class', imgClass + ' replaced-svg');
            }
            $svg = $svg.removeAttr('xmlns:a');
            $img.replaceWith($svg);
         }, 'xml');

      });
    
   };
})(jQuery);