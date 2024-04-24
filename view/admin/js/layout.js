(function ($) {
   'use strict';
   $.Layout = function (settings) {
      const config = {
         url: '',
         lurl: '',
         mod_id: 0,
         type: '',
         lang: {
            edit: 'Edit',
            delete: 'Delete',
            insert: 'Insert',
            cancel: 'Cancel',
         }
      };
      const $sortable = $('.wojo.sortable');
      if (settings) {
         $.extend(config, settings);
      }

      document.ontouchmove = function () {
         return true;
      };

      if ($('ul[data-position=top]').children().length) {
         $('.pEdit[data-section=top]').find('.icon.distribute.horizontal').removeClass('disabled');
      }
      if ($('ul[data-position=bottom]').children().length) {
         $('.pEdit[data-section=bottom]').find('.icon.distribute.horizontal').removeClass('disabled');
      }

      $sortable.sortable({
         ghostClass: 'ghost',
         group: 'name',
         handle: '.handle',
         animation: 600,
         onStart: function (ui) {
            $(ui.item).css({
               'width': 'auto'
            });
         },
         onUpdate: function (ui) {
            const items = this.toArray();
            const position = $(ui.item).parent().attr('data-position');

            $.post(config.url + 'layout/action/', {
               action: 'sort',
               position: position,
               items: items,
               type: config.type,
               mod: config.mod_id
            });

         },
         onAdd: function (ui) {
            let position = $(ui.item).parent().data('position');
            let items = [];
            $('[data-position=' + position + ']').children().each(function () {
               items.push($(this).data('id'));
            });

            $.post(config.url + 'layout/action/', {
               action: 'sort',
               position: position,
               items: items,
               type: config.type,
               mod: config.mod_id
            });
         }
      });

      // change module
      $('select[name=mod_id]').on('change', function () {
         let id = $(this).val();
         let mod = '?mod_id=' + id;
         window.location.href = config.lurl + mod;

      });

      // Add plugins
      $('.pAdd').on('click', function () {
         let $this = $(this);
         let section = $this.data('section');

         let idin = [];
         $('.wojo.sortable li').each(function () {
            idin.push($(this).attr('data-id'));
         });

         let data = {
            action: 'free',
            section: section,
            ids: idin,
         };

         $.get(config.url + 'layout/action/', data, function (json) {
            let actions = '' +
              '<div class="footer">' +
              '<button type="button" class="wojo small simple button" data="modal:close">' + config.lang.cancel + '</button>' +
              '<button type="button" class="wojo small positive button" data="modal:ok">' + config.lang.insert + '</button>' +
              '</div>';

            $('<div class="wojo normal modal"><div class="dialog" role="document"><div class="content">' +
              '' + json.html + '' +
              '' + actions + '' +
              '</div></div></div>').modal().on('click', '[data="modal:ok"]', function () {
               const $activeModal = $('.wojo.modal .wojo.list div.active');
               let items = '';
               let allitems = [];
               $activeModal.each(function () {
                  let id = $(this).data('id');
                  let text = $(this).text();
                  allitems.push($(this).data('id'));
                  items +=
                    '<li class="item" data-id="' + id + '" id="item_' + id + '">' +
                    '<div class="content">' +
                    '<div class="handle"><i class="icon grip horizontal"></i></div>' +
                    '<div class="text">' + text + '</div>' +
                    '<div class="actions"><a><i class="icon negative x alt"></i></a></div>' +
                    '</div>' +
                    '</li>';
               });
               if (items) {
                  $('ul[data-position="' + section + '"]').append(items);
                  $.post(config.url + 'layout/action/', {
                     action: 'insert',
                     position: section,
                     items: allitems,
                     type: config.type,
                     mod: config.mod_id
                  });
               }

               $activeModal.remove();
               ('#pEdit').children('.icon.distribute.horizontal').removeClass('disabled');
            });

            $('.wojo.modal .wojo.list').on('click', 'a', function () {
               if ($(this).parent().hasClass('active')) {
                  $(this).parent().removeClass('active');
               } else {
                  $(this).parent().addClass('active');
               }
            });

         }, 'json');
      });

      // Edit plugin spaces
      $('.pEdit').on('click', function () {
         let $this = $(this);
         let section = $this.data('section');

         let idin = [];
         $('ul[data-position=' + section + ']').children().each(function () {
            idin.push($(this).attr('data-id'));
         });

         let data = {
            action: 'layout',
            section: section,
            ids: idin,
            mod: config.mod_id
         };
         if (idin.length > 0) {
            $.get(config.url + 'layout/action/', data, function (json) {
               setTimeout(function () {
                  $('#dropdown-' + section).html(json.html);
                  $('.rangeslider').wRange();
               }, 500);

               $('#dropdown-' + section).on('click', '.update', function () {
                  let items = $('.layform').serializeArray();
                  $.post(config.url + 'layout/action/', {
                     action: 'update',
                     position: section,
                     items: items,
                     type: config.type,
                     mod: config.mod_id
                  });
               });
            }, 'json');
         } else {
            $('#dropdown-' + section).html(' ');
         }
      });

      $sortable.on('click', '.actions a', function () {
         let parent = $(this).closest('li');
         let data = {
            action: 'delete',
            id: $(this).closest('li').data('id'),

            type: config.type,
            mod: config.mod_id
         };

         $.post(config.url + 'layout/action/', data, function (json) {
            if (json.type === 'success') {
               $(parent).remove();
            }
         }, 'json');
      });
   };
})(jQuery);