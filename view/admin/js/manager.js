(function ($, window, document, undefined) {
   'use strict';
   const pluginName = 'Manager';

   function Plugin(element, options) {
      this.element = element;
      this._name = pluginName;
      this._defaults = $.fn.Manager.defaults;
      this.options = $.extend({}, this._defaults, options);
      this.init();
   }

   $.extend(Plugin.prototype, {
      init: function () {
         this.buildList();
         this.bindEvents();
      },
      /**
       *
       * @param dirname
       * @param type
       * @param ext
       * @param sorting
       * @property {string} dirsize
       * @property {string} filesize
       */
      buildList: function (dirname, type, ext, sorting) {
         const plugin = this;
         const element = this.element;
         type = Cookies.get('CMS_FLAYOUT') === 'undefined' ? 'table' : Cookies.set('CMS_FLAYOUT');

         $.ajax({
            type: 'GET',
            url: this.options.aurl + 'manager/action/',
            dataType: 'json',
            async: true,
            data: {
               action: 'files',
               layout: type,
               dir: dirname,
               exts: ext,
               sorting: sorting
            }
         }).done(function (json) {
            const template = plugin.renderTemplate(type, json);
            $(element).html(template).transition('scaleIn', {
               duration: 120
            });
            $('#tsizeDir span').html(json.dirsize);
            $('#tsizeFile span').html(json.filesize);

            if ($('#fileModal:visible').length > 0) {
               $('#fileModal').modal('refresh');
            }
         });
      },

      /**
       * Bind events that trigger methods
       * @property {string} ftime
       * @property {string} simple
       */
      bindEvents: function () {
         const plugin = this;
         const element = this.element;
         const lang = plugin.options.lang;
         const $fm = $('#fm');

         $('#togglePreview').on('click', function () {
            const icon = $(this).children();
            $(icon).toggleClass('expand collaps');
            $('#preview').toggle();
         });

         $fm.on('click', 'a.is_file', function () {
            const dataset = $(this).data('set');
            const url = plugin.options.dirurl + dataset.url;
            const murl = plugin.options.aview + 'images/mime/' + dataset.ext + '.svg';

            const is_image = (dataset.image === 'true') ? url : murl;
            if (dataset.name) {
               let template = '' +
                 '<img src="' + is_image + '" class="wojo center medium rounded shadow image" alt=""> ' +
                 '<div class="wojo small celled list margin-vertical"> ' +
                 '<div class="item">' + plugin.maxLength(dataset.name, 20) + '</div> ' +
                 '<div class="item">' + lang.size + ': ' + dataset.size + '</div> ' +
                 '<div class="item">' + lang.lastm + ': ' + dataset.ftime + '</div> ' +
                 '<div class="item"><a href="' + url + '" class="wojo small simple positive button"><i class="icon download"></i>' + lang.download + ' </a></div> ' +
                 '';
               if (dataset.ext === 'zip') {
                  template += '' +
                    '<div class="item"><a data-url="' + dataset.url + '/" class="wojo small simple positive button unzip"> ' + lang.unzip + '</a></div>';
               }
               template += '' +
                 '<div class="item"><a data-ext="' + dataset.ext + '" data-simple="' + dataset.simple + '" data-url="' + dataset.url + '" data-name="' + dataset.name + '" data-type="file" class="wojo small simple negative button delSingle"><i class="icon trash"></i>' + lang.delete + '</a></div>' +
                 '</div>';

               if (plugin.options.is_mce) {
                  template += '' +
                    '<div class="content-center"><a class="wojo small simple primary button insertMCE" data-url="' + url + '"><i class="icon customize"></i> ' + lang.insert + ' </a></div>';
               }
               $('#preview').html(template);
            }
         });

         //Browse directories
         $fm.on('click', 'a.is_dir', function () {
            const dataset = $(this).data('set');
            const items = plugin.filterDisplay();
            const folder = (dataset.files > 0) ? 'open' : 'closed';
            plugin.buildList(dataset.url, items.layout, items.filter, items.sorting);
            $('#fcrumbs').html('<a class="is_dir" data-set=\'{"url":""}\'>' + lang.home + '</a>  / ' + plugin.getCrumbs(dataset.url));
            if (dataset.name) {
               const template = '' +
                 '<img src="' + plugin.options.aview + 'images/mime/' + folder + '_folder.svg" class="wojo medium image" alt=""> ' +
                 '<div class="wojo small relaxed celled list"> ' +
                 '<div class="item">' + plugin.maxLength(dataset.name, 20) + '</div> ' +
                 '<div class="item">' + lang.items + ': ' + dataset.files + '</div> ' +
                 '<a data-url="' + dataset.url + '" data-name="' + dataset.name + '" data-type="dir" class="wojo small simple negative button item delSingle"><i class="icon trash"></i> ' + lang.delete + '</a> ' +
                 '</div>';
               $('#preview').html(template);
               $('input[name=dir]').val(dataset.url);
            }
         });

         //Delete multiple files/folders
         $fm.on('click', '.is_delete', function () {
            const $this = $(this);
            const checkedValues = $('#listView input:checkbox:checked').map(function () {
               return this.value;
            }).get();
            if (!$.isEmptyObject(checkedValues)) {
               $this.addClass('loading');
               $.post(plugin.options.aurl + 'manager/action/', {
                  action: 'delete',
                  items: checkedValues,
               }, function (json) {
                  if (json.type === 'success') {
                     $('#listView tr').each(function () {
                        if ($(this).find('input:checked').length) {
                           $(this).fadeOut(400, function () {
                              $(this).remove();
                           });
                           $this.removeClass('loading');
                        }
                     });

                  }
               }, 'json');
            }
         });

         //Delete single files/folders
         $fm.on('click', '.delSingle', function () {
            const dir = $(this).data('url');
            const type = $(this).data('type');
            const name = $(this).data('simple');

            $.post(plugin.options.aurl + 'manager/action/', {
               action: "delete",
               items: [dir],
            }, function (json) {
               if (json.type === 'success') {
                  if (type === "dir") {
                     $(element).html('<div class="wojo basic centered image"><img src="' + plugin.options.aview + 'images/empty.svg" alt=""></div>').transition('scaleIn');
                     $("#preview").html('');
                  } else {
                     $(element).find('[data-id=' + name + ']').remove();
                     $('#preview').html('<img class="wojo medium basic image" src="' + plugin.options.aview + 'images/empty.svg" alt="">');
                  }

               }
            }, "json");
         });


         //New Folder
         $fm.on('click', '#addFolder', function () {
            const $parent = $(this).parent('.input');
            const $field = $('input[name=foldername]');
            const items = plugin.filterDisplay();

            if ($field.val().length > 0) {
               $parent.addClass('loading');
               $.post(plugin.options.aurl + 'manager/action/', {
                  action: 'folder',
                  name: $field.val(),
                  dir: items.dir
               }, function (json) {
                  if (json.type === 'success') {
                     plugin.buildList(items.dir, items.layout, items.filter, items.sorting);
                     $parent.removeClass('loading');
                  }
               }, 'json');
            }

         });

         /* == Unzip == */
         $fm.on('click', '.unzip', function () {
            const url = $(this).data('url');
            $.post(plugin.options.aurl + 'manager/action/', {
               action: 'unzip',
               item: url,
            }, function (json) {
               if (json.type === 'success') {
                  const items = plugin.filterDisplay();
                  plugin.buildList(items.dir, items.layout, items.filter, items.sorting);
               }
            }, 'json');
         });

         /* == Check All == */
         $fm.on('change', '#selectAll', function () {
            const $checkbox = $('#listView').find(':checkbox');
            $checkbox.prop('checked', !$checkbox.prop('checked'));
            if ($checkbox.is(':checked')) {
               $('.is_delete').removeClass('disabled');
            } else {
               $('.is_delete').addClass('disabled');
            }
         });

         $('#result').on('change', 'input[type="checkbox"]', function () {
            if ($('#listView').find(':checkbox').is(':checked')) {
               $('.is_delete').removeClass('disabled');
            } else {
               $('.is_delete').addClass('disabled');
            }
         });

         //Type filter
         $('#ftype').on('click', 'a', function () {
            $('#ftype a').removeClass('active');
            const filter = $(this).data('type');
            $(this).addClass('active');
            const items = plugin.filterDisplay();
            plugin.buildList(items.dir, items.layout, filter, items.sorting);
         });

         //Sorting type
         $('.fileSort').on('change', function () {
            const sorting = $(this).val();
            const items = plugin.filterDisplay();
            plugin.buildList(items.dir, items.layout, items.filter, sorting);
         });

         //Display type
         $('#displayType').on('click', 'a', function () {
            const layout = $(this).data('type');
            const items = plugin.filterDisplay();

            $('#displayType a').not(this).removeClass('passive').addClass('simple');
            $(this).removeClass('simple').addClass('passive');

            Cookies.set('CMS_FLAYOUT', layout, {
               expires: 365,
               path: '/',
               sameSite: 'strict'
            });
            plugin.buildList(items.dir, layout, items.filter, items.sorting);
         });

         //File Upload
         $('#drag-and-drop-zone').on('click', function () {
            const items = plugin.filterDisplay();
            const $done = $('#done');
            $(this).wojoUpload({
               url: plugin.options.aurl + 'manager/action/',
               dataType: 'json',
               extraData: {
                  action: 'upload',
                  dir: items.dir
               },
               allowedTypes: '*',
               onBeforeUpload: function (id) {
                  plugin.update_file_status(id, 'primary', 'Uploading...');
               },
               onNewFile: function (id, file) {
                  plugin.add_file(id, file);
               },
               onUploadProgress: function (id, percent) {
                  plugin.update_file_progress(id, percent);
               },
               onUploadSuccess: function (id, data) {
                  if (data.type === 'error') {
                     plugin.update_file_status(id, '<i class="icon negative dash circle"></i>', data.message);
                     plugin.update_file_progress(id, 0);
                  } else {
                     const icon = '<i class="icon positive check circle"></i>';
                     const btn = '<img src="' + data.filename + '" class="wojo small shadow rounded image" alt="">';

                     plugin.update_file_status(id, icon, btn);
                     plugin.update_file_progress(id, 100);
                  }
               },
               onUploadError: function (id, message) {
                  plugin.update_file_status(id, '<i class="icon negative dash circle"></i>', message);
               },
               onFallbackMode: function (message) {
                  alert('Browser not supported: ' + message);
               },

               onComplete: function () {
                  $done.append('<a class="wojo mini primary button">' + lang.done + '</a>');
                  $done.on('click', 'a', function () {
                     plugin.buildList(items.dir, items.layout, items.filter, items.sorting);
                     $('#fileList').html('');
                     $('#done a').remove();
                  });
               }
            });
         });
      },

      add_file: function (id, file) {
         const template = '' +
           '<div class="item align-middle" id="uploadFile_' + id + '">' +
           '<div id="bStstus_' + id + '">' +
           '<div class="wojo icon primary button"><i class="icon file"></i></div>' +
           '</div>' +
           '<div class="content padding-left" id="contentFile_' + id + '">' +
           '<span class="wojo text-weight-500">' + file.name + '</span>' +
           '</div>' +
           '<div id="iStatus_' + id + '"><i class="icon info arrow bar up"></i></div>' +
           '<div class="wojo attached bottom tiny positive progress">' +
           '<div class="bar" data-percent="100"></div>' +
           '</div>' +
           '</div>';

         $('#fileList').prepend(template);
      },

      update_file_status: function (id, status, message) {
         $('#bStstus_' + id).html(message);
         $('#iStatus_' + id).html(status);
      },

      update_file_progress: function (id, percent) {
         let $uf = $('#uploadFile_' + id);
         $uf.find('.progress').wProgress();
         $uf.find('.progress').attr('data-percent', percent);
      },

      // trim long filenames
      maxLength: function (title, chars) {
         return (title.length > chars) ? title.substr(0, (chars - 3)) + '...' : title;
      },

      // display filter
      filterDisplay: function () {
         const layout = $('#displayType a.active').data('type');
         const filter = $('#ftype a.active').data('type');
         const dir = $('input[name=dir]').val();
         const sorting = $('.fileSort option:selected').val();
         return {
            dir: dir,
            layout: layout,
            filter: filter,
            sorting: sorting
         };
      },

      //do crumbs
      getCrumbs: function (dir) {
         const here = dir.split('/').slice(1);
         const parts = [];
         for (let i = 0; i < here.length; i++) {
            const text = here[i];
            const link = here.slice(0, i + 1).join('/');
            parts.push({
               text: text,
               link: link
            });
         }

         let crumbs = '';
         $.each(parts, function (index, value) {
            if ((parts.length - 1) !== index) {
               crumbs += '<a class="is_dir" data-set=\'{"url":"/' + value.link + '"}\'>' + value.text.substr(0, 1).toUpperCase() + value.text.substr(1) + '</a> / ';
            } else {
               crumbs += value.text.substr(0, 1).toUpperCase() + value.text.substr(1);
            }
         });
         return crumbs;
      },

      /**
       * Template
       * @param type
       * @param obj
       * @returns {string}
       * @property {string} directory
       * @property {string} is_image
       * @property {string} mime
       */
      renderTemplate: function (type, obj) {
         const plugin = this;
         let template = '';
         switch (type) {
            case 'list':
               template += '<div class="row grid horizontal small-gutters phone-1 mobile-1 tablet-2 screen-2">';
               if (obj.directory) {
                  $.each(obj.directory, function () {
                     const folder = (this.total > 0) ? 'folder open' : 'folder';
                     template += '<div class="columns" data-id="' + this.name + '">' +
                       '<a class="wojo simple icon message is_dir" data-set=\'{"name":"' + this.name + '", "files":"' + this.total + '", "url":"' + this.path + '"}\'> ' +
                       '<i class="icon large ' + folder + '"></i> ' +
                       '<div class="content"> ' +
                       '' + this.name + '' +
                       '<p>' + this.total + ' files</p>' +
                       '</div> ' +
                       '</a>' +
                       '</div>';
                  });
               }
               if (obj.files) {
                  $.each(obj.files, function () {
                     const is_image = (this.is_image) ? plugin.options.dirurl + 'thumbs/' + this.name : plugin.options.aview + 'images/mime/' + this.extension + '.svg';
                     const is_svg = this.extension === 'svg' ? plugin.options.dirurl + this.path : is_image;

                     template += '<div class="columns" data-id="' + this.simple + '" data-ext="' + this.extension + '">' +
                       '<div class="selectable"> ' +
                       '<a class="wojo simple icon message is_file" data-set=\'{"name":"' + this.name + '","simple":"' + this.simple + '", "image":"' + this.is_image + '", "ext":"' + this.extension + '", "ftime":"' + this.ftime + '", "size":"' + this.size + '", "url":"' + this.url + '"}\'> ' +
                       '<img src="' + is_svg + '" alt="" class="wojo center mini rounded shadow image">' +
                       '<div class="content"> ' +
                       '' + this.name + '' +
                       '<p>' + this.size + '</p>' +
                       '</div> ' +
                       '</div></a>' +
                       '</div>';

                  });
               }
               template += '</div>';
               break;

            case 'grid':
               template += '<div class="small mason">';
               if (obj.directory) {
                  $.each(obj.directory, function () {
                     const folder = (this.total > 0) ? 'open' : 'closed';
                     template += '<div class="items" data-id="' + this.name + '">' +
                       '<div class="wojo small simple segment"> ' +
                       '<div class="center-align">' +
                       '<a data-set=\'{"name":"' + this.name + '", "files":"' + this.total + '", "url":"' + this.path + '"}\' class="is_dir"> ' +
                       '<img alt="" src="' + plugin.options.aview + 'images/mime/' + folder + '_folder.svg" class="wojo center small image"> ' +
                       '</a> ' +
                       '</div> ' +
                       '<div class="wojo divider"></div>' +
                       '<span class="wojo text">' + this.name + '</span>' +
                       '<p class="text-size-small text-weight-500">' + this.total + ' files</p>' +
                       '</div> ' +
                       '</div>';
                  });
               }

               if (obj.files) {
                  $.each(obj.files, function () {
                     const dir = (this.extension === 'svg') ? this.dir + '/' : '/thumbs/';
                     const is_image = (this.is_image) ? plugin.options.dirurl + dir + this.name : plugin.options.aview + 'images/mime/' + this.extension + '.svg';
                     const is_svg = this.extension === 'svg' ? plugin.options.dirurl + this.path : is_image;

                     template += '<div class="columns" data-id="' + this.name + '">' +
                       '<div class="wojo small simple segment selectable">' +
                       '<div class="center-aligned">' +
                       '<a class="is_file" data-set=\'{"name":"' + this.name + '", "simple":"' + this.simple + '", "image":"' + this.is_image + '", "ext":"' + this.extension + '", "ftime":"' + this.ftime + '", "size":"' + this.size + '", "url":"' + this.url + '"}\'>' +
                       '<img alt="" src="' + is_svg + '" class="wojo center small rounded shadow image"></a>' +
                       '</div>' +
                       '<div class="wojo divider"></div>' +
                       '<span class="wojo text">' + this.name + '</span>' +
                       '<p class="text-size-small text-weight-500">' + this.size + '</p>' +
                       '</div>' +
                       '</div>';

                  });
               }

               template += '</div>';
               break;

            default:
               template += '<table class="wojo basic striped table">';
               if (!plugin.options.is_editor) {
                  template += '' +
                    '<thead> ' +
                    ' <tr> ' +
                    '<th colspan="4" class="auto"><div class="wojo toggle checkbox fitted"> ' +
                    '<input type="checkbox" name="master" value="1" id="selectAll"> ' +
                    '<label for="selectAll">&nbsp;</label> ' +
                    '</div></th> ' +
                    '</tr> ' +
                    '</thead>';
               }
               template += '<tbody id="listView">';
               if (obj.directory) {
                  $.each(obj.directory, function (key) {
                     const folder = (this.total > 0) ? 'folder open' : 'folder';
                     template += '<tr data-id="' + this.name + '">';
                     if (!plugin.options.is_editor) {
                        template += '' +
                          '<td class="auto"><div class="wojo small checkbox fitted">' +
                          '<input type="checkbox" name="' + this.name + '" value="' + this.path + '" id="dirView_' + key + '">' +
                          '<label for="dirView_' + key + '"></label>' +
                          '</div>' +
                          '</td>';
                     }
                     template += '' +
                       '<td class="auto"><i class="icon primary ' + folder + '"></i></td> ' +
                       '<td><a class="black is_dir" data-set=\'{"name":"' + this.name + '", "files":"' + this.total + '", "url":"' + this.path + '"}\'>' + this.name + '</a></td> ' +
                       '<td class="auto">' + this.total + ' <small>(items)</small></td>';
                     template += '</tr>';
                  });
               }

               if (obj.files) {
                  $.each(obj.files, function (key) {
                     const mime = this.mime.split('/');
                     let icon;
                     switch (mime[0]) {
                        case 'image':
                           icon = '<i class="icon image"></i>';
                           break;
                        case 'video':
                           icon = '<i class="icon camera film"></i>';
                           break;
                        case 'audio':
                           icon = '<i class="icon soundwave"></i>';
                           break;
                        default:
                           icon = '<i class="icon file"></i>';
                           break;
                     }

                     template += '<tr data-id="' + this.simple + '" data-ext="' + this.extension + '" class="selectable">';
                     if (!plugin.options.is_editor) {
                        template += '' +
                          '<td class="auto"><div class="wojo small checkbox fitted inline">' +
                          '<input type="checkbox" name="' + this.name + '" value="' + this.url + '" id="fileView_' + key + '">' +
                          '<label for="fileView_' + key + '"></label>' +
                          '</div>' +
                          '</td>';
                     }
                     template += '' +
                       '<td class="auto">' + icon + '</td>' +
                       '<td><a class="black is_file" data-set=\'{"name":"' + this.name + '", "simple":"' + this.simple + '", "image":"' + this.is_image + '", "ext":"' + this.extension + '", "ftime":"' + this.ftime + '", "size":"' + this.size + '", "url":"' + this.url + '"}\'>' + this.name + '</a></td>' +
                       '<td class="auto">' + this.size + '</td>';
                     template += '</tr>';
                  });
               }

               template += '</tbody>';
               template += '</table>';
               break;

         }

         return template;
      }

   });

   $.fn.Manager = function (options) {
      this.each(function () {
         if (!$.data(this, 'plugin_' + pluginName)) {
            $.data(this, 'plugin_' + pluginName, new Plugin(this, options));
         }
      });

      return this;
   };

   $.fn.Manager.defaults = {
      aview: '',
      aurl: '',
      dirurl: '',
      is_editor: false,
      is_mce: false,
      lang: {
         delete: 'Delete',
         insert: 'Insert',
         download: 'Download',
         unzip: 'Unzip',
         size: 'Size',
         lastm: 'Last Modified',
         items: 'items',
         done: 'Done',
         home: 'Home',
      }
   };

})(jQuery, window, document);