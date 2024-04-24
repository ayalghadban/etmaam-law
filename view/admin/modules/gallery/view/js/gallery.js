(function ($) {
   "use strict";
   $.Gallery = function (settings) {
      const config = {
         url: "",
         dir: "",
         grid: '.mason',
         sortable: '',
         lang: {
            done: 'Done',
         }
      };
      if (settings) {
         $.extend(config, settings);
      }

      // Resize images
      $('#doResize').on('click', function () {
         const $button = $(this);
         const w = $("input[name=thumb_w]").val();
         const h = $("input[name=thumb_h]").val();
         const method = $('input[name=resize]:checked').val();
         const dir = $("input[name=dir]").val();
         $button.addClass('loader').prop('disabled', true);
         $.post(config.url, {
            action: 'resize',
            resize: method,
            thumb_w: w,
            thumb_h: h,
            dir: dir,
         }, function (json) {
            setTimeout(function () {
               $($button).removeClass("loader").prop("disabled", false);
            }, 500);
            $.wNotice({
               autoclose: 12000,
               type: json.type,
               title: json.title,
               text: json.message
            });
         }, "json");
      });

      // sort albums/photos
      $('#reorder').on('click', function () {
         if ($(this).children().hasClass('grid')) {
            $(this).children().toggleClass('grid check');
            $("#dragNotice").show();
            $('.content', config.sortable).hide();
            //$(config.sortable).removeClass('mason');
            //$(config.sortable).addClass('blocks screen-5 tablet-4 mobile-3 phone-1 gutters');
            $(config.sortable).addClass('four');
            const type = ($.inArray("photos", $.url().segment()) === -1) ? 'sortAlbums' : 'sortPhotos';
            $(config.sortable).sortable({
               animation: 150,
               ghostClass: "outline",
               onUpdate: function () {
                  const order = this.toArray();
                  $.post(config.url, {
                     action: "sort",
                     type: type,
                     sorting: order
                  }, function () {
                  }, "json");

               }
            });
         } else {
            $(config.sortable).addClass('loader');
            $(this).children().toggleClass('check grid');
            $("#dragNotice").hide();
            $('.content', config.sortable).show();
            $(config.sortable).removeClass('four');
            //$(config.sortable).removeClass('blocks screen-5 tablet-4 mobile-3 phone-1 gutters');
            //$(config.sortable).addClass('mason');
            $(config.sortable).removeClass('loader');
         }
      });

      // assign poster
      $(config.sortable).on('click', '.poster', function () {
         const $this = $(this);
         const $icon = $(this).children('.icon');
         $.post(config.url, {
            action: "poster",
            thumb: $(this).data('poster'),
            id: $.url().segment(-1)
         }, function (json) {
            if (json.type === "success") {
               const $item = $(config.sortable).find('.menu .item.disabled');
               $item.children().toggleClass('check image');
               $item.toggleClass('disabled poster');
               $this.toggleClass('poster disabled');
               $icon.toggleClass('image check');
            }
         }, "json");
      });

      //File Upload
      $('#drag-and-drop-zone').on('click', function () {
         $(this).wojoUpload({
            url: config.url,
            dataType: 'json',
            extraData: {
               action: "upload",
               dir: config.dir
            },
            allowedTypes: '*',
            onBeforeUpload: function (id) {
               update_file_status(id, 'primary', 'Uploading...');
            },
            onNewFile: function (id, file) {
               add_file(id, file);
            },
            onUploadProgress: function (id, percent) {
               update_file_progress(id, percent);
            },
            onUploadSuccess: function (id, data) {
               if (data.type === "error") {
                  update_file_status(id, '<i class="icon negative dash circle"></i>', data.message);
                  update_file_progress(id, 0);
               } else {
                  const icon = '<i class="icon positive check circle"></i>';
                  const btn = '<img src="' + data.filename + '" class="wojo small shadow rounded image" alt="">';

                  update_file_status(id, icon, btn);
                  update_file_progress(id, 100);
               }
            },
            onUploadError: function (id, message) {
               update_file_status(id, '<i class="icon negative dash circle"></i>', message);
            },
            onFallbackMode: function (message) {
               alert('Browser not supported: ' + message);
            },

            onComplete: function () {
               let done = '#done';
               if ($(done).length === 0) {
                  $("#fileList").after('<div id="done" class="margin-vertical"><a class="wojo small primary button"><i class="icon check"></i>' + config.lang.done + '</a></div>');
               }

               $(done).on('click', 'a', function () {
                  buildList($.url().segment(-1));
                  $('#fileList').html('');
                  $("#done").remove();
               });
            }
         });
      });

      function add_file(id, file) {
         const template = '' +
           '<div class="item align-middle" id="uploadFile_' + id + '">' +
           '<div id="bStstus_' + id + '">' +
           '<div class="wojo icon primary button"><i class="icon file"></i></div>' +
           '</div>' +
           '<div class="content padding-left" id="contentFile_' + id + '">' +
           '<span class="text-weight-500">' + file.name + '</span>' +
           '</div>' +
           '<div id="iStatus_' + id + '"><i class="icon info arrow bar up"></i></div>' +
           '<div class="wojo attached bottom tiny positive progress">' +
           '<div class="bar" data-percent="100"></div>' +
           '</div>' +
           '</div>';

         $('#fileList').prepend(template);
      }

      function update_file_status(id, status, message) {
         $('#bStstus_' + id).html(message);
         $('#iStatus_' + id).html(status);
      }

      function update_file_progress(id, percent) {
         let $uf = $('#uploadFile_' + id);
         $uf.find('.progress').wProgress();
         $uf.find('.progress .bar').attr("data-percent", percent);
      }

      function buildList(id) {
         $(config.grid).addClass('loading');
         $.get(config.url, {
            action: "load",
            id: id,
         }, function (json) {
            if (json.type === "success") {
               $(config.grid).html(json.html);
            }
            $(config.grid).removeClass('loading');
         }, "json");
      }
   };
})(jQuery);