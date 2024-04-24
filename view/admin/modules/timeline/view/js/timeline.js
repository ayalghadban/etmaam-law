(function ($) {
   "use strict";
   $.Timeline = function (settings) {
      const config = {
         url: "",
         upUrl: "",
      };
      if (settings) {
         $.extend(config, settings);
      }
      const $bconfig = $('#fbconf');
      const $rssconfig = $('#rssconf');
      const $sortable = $("#sortable");
      const $iframe = $('#iframe');

      // select type mode
      $("select[name=type]").on('change', function () {
         switch ($(this).val()) {
            case "facebook":
               $bconfig.show();
               $rssconfig.hide();
               break;
            case "rss":
               $bconfig.hide();
               $rssconfig.show();
               break;
            default:
               $('#fbconf, #rssconf').hide();
               break;
         }
      });

      $sortable.sortable({
         ghostClass: "outline",
         handle: ".handle",
         filter: ".remove",
         animation: 600,
         onFilter: function (ui) {
            $(ui.item).remove();
         }
      });

      //change type
      $('#tmType').change(function () {
         const selected = $(this).val();
         switch (selected) {
            case "iframe":
               $iframe.show();
               $('#imgfield, #bodyfield').hide();
               break;

            case "gallery":
               $('#iframe, #bodyfield').hide();
               $('#imgfield').show();
               break;

            default:
               $iframe.hide();
               $('#bodyfield').show();
               break;
         }
      });

      //select images
      $('.multipick').on('click', function () {
         $.get(config.url + 'filepicker.php', {
            pickFile: 1,
            editor: true
         }, function (data) {
            $('<div class="wojo big modal"><div class="dialog" role="document"><div class="content">' + data + '</div></div></div>').modal();
            $("#result").on('click', '.is_file', function () {
               const dataset = $(this).data('set');
               if (dataset.image === "true") {
                  const iparent = $(this).closest('.selectable');
                  if ($(iparent).hasClass('wojo outline')) {
                     $(iparent).removeClass('wojo outline');
                  } else {
                     $(iparent).addClass('wojo outline');
                  }
               }
               if ($("#result .wojo.outline").length > 0) {
                  $("#fInsert").parent().removeClass('hide-all');
               } else {
                  $("#fInsert").parent().addClass('hide-all');
               }
            });

            $("#fInsert").on('click', function () {
               let html = '';
               $("#result .wojo.outline").each(function () {
                  const dataset = $(this).find('.is_file').data('set');
                  html +=
                    '<div class="columns"> ' +
                    '<div class="wojo compact segment center-align"> ' +
                    '<div class="handle"><i class="icon grip horizontal"></i></div> ' +
                    '<img src="' + config.upUrl + dataset.url + '" alt="" class="wojo rounded image"> ' +
                    '<input type="hidden" name="images[]" value="' + dataset.url + '">' +
                    '<a class="wojo mini icon negative simple button remove"><i class="icon x alt"></i></a> ' +
                    '</div> ' +
                    '</div>';

               });
               $("#sortable").prepend(html);
               $.modal.close();
            });
         });
      });
   };
})(jQuery);