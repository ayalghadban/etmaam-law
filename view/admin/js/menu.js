(function ($) {
   'use strict';
   $.Menu = function (settings) {
      const config = {
         url: '',
         lang: {
            delMsg3: 'Trash',
            delMsg8: 'The item will remain in Trash for 30 days. To remove it permanently, go to Trash and empty it.',
            canBtn: 'Cancel',
            nonBtn: 'None',
            trsBtn: 'Move to Trash',
         }
      };
      if (settings) {
         $.extend(config, settings);
      }

      const $page_id = $('#page_id');
      const $webid = $('#webid');
      const $contentid = $('#contentid');
      const $mIcons = $('#mIcons');

      $mIcons.find('i[class="' + $('input[name=icon]').val() + '"]').parent().toggleClass('primary simple');

      $('#contenttype').on('change', function () {
         const $icon = $(this).parent();
         const option = $(this).val();
         if (option === '') {
            $contentid.show();
            $webid.hide();
            $page_id.html('<option value="0">' + config.lang.nonBtn + '</option>');
            $page_id.prop('name', 'page_id');
         } else {
            $icon.addClass('loading');
            $.get(config.url + 'ajax/', {
               action: 'contentType',
               type: option,
            }, function (json) {
               switch (json.type) {
                  case 'page':
                     $contentid.show();
                     $webid.hide();
                     $page_id.html(json.message);
                     $page_id.prop('name', 'page_id');
                     break;

                  case 'module':
                     $contentid.show();
                     $webid.hide();
                     $page_id.html(json.message);
                     $page_id.prop('name', 'mod_id');
                     break;

                  default:
                     $contentid.hide();
                     $webid.show();
                     $page_id.prop('name', 'web_id');
                     break;
               }

               $icon.removeClass('loading');
            }, 'json');
         }
      });

      /* == Toggle Menu icons == */
      $mIcons.on('click', '.button', function () {
         const micon = $('input[name=icon]');
         $('#mIcons .button.primary').not(this).removeClass('primary').toggleClass('simple');
         $(this).toggleClass('primary simple');
         micon.val($(this).hasClass('primary') ? $(this).children().attr('class') : '');
      });

      /* == Toggle Menu parents == */
      $('#sortlist > ul > li:has(> ul)').addClass('parent');
      $('#sortlist > ul > li.parent > .content .handle').after('<div class="arrow"><i class="icon chevron down"></i></div>');
      $('#sortlist .arrow').on('click', function () {
         $(this).find('.icon').toggleClass('down up');
         const parent = $(this).closest('li');
         $(parent).children('ul').fadeToggle(150);
         event.preventDefault()
      });

      $('.wojo.nestable').sortable({
         group: 'nested',
         animation: 150,
         fallbackOnBody: true,
         swapThreshold: 0.65,
         handle: '.handle',
         ghostClass: 'ghost',
         onUpdate: function () {
            const items = this.toArray();
            $.ajax({
               cache: false,
               type: 'post',
               url: config.url + 'menus/action/',
               dataType: 'json',
               data: {
                  action: 'sort',
                  sorting: items
               }
            });
         },
      });
   };
})(jQuery);