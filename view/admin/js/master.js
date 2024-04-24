(function ($) {
   'use strict';
   $.Master = function (settings) {
      const config = {
         weekstart: 0,
         ampm: 0,
         url: '',
         aurl: '',
         surl: '',
         editorCss: '',
         lang: {
            monthsFull: '',
            monthsShort: '',
            weeksFull: '',
            weeksShort: '',
            weeksMed: '',
            weeksSmall: '',
            dateFormat: 'mm/dd/yyyy',
            today: 'Today',
            now: 'Now',
            selPic: 'Select Picture',
            delBtn: 'Delete Record',
            trsBtn: 'Move to Trash',
            arcBtn: 'Move to Archive',
            uarcBtn: 'Restore From Archive',
            restBtn: 'Restore Item',
            canBtn: 'Cancel',
            clear: 'Clear',
            sellected: 'Selected',
            allBtn: 'Select All',
            allSel: 'all selected',
            sellOne: 'Select option',
            doSearch: 'Search ...',
            noMatch: 'No matches for',
            ok: 'OK',
            delMsg1: 'Are you sure you want to delete this record?',
            delMsg2: 'This action cannot be undone!!!',
            delMsg3: 'Trash',
            delMsg5: 'Move [NAME] to the archive?',
            delMsg6: 'Remove [NAME] from the archive?',
            delMsg7: 'Restore [NAME]?',
            delMsg8: 'The item will remain in Trash for 30 days. To remove it permanently, go to Trash and empty it.',
            delMsg9: 'This action will restore item to it\'s original state',
            working: 'working...'
         }
      };
      const $fromdate = $('#fromdate');
      const $enddate = $('#enddate');

      if (settings) {
         $.extend(config, settings);
      }

      /* == Navigation Menu == */
      $('.wojo.menu').wMenu({
         breakpoint: 959,
         showArrows: true,
         arrow: '<i class="icon plus alt"></i>'
      });

      /* == Background Image == */
      $(document).on('click', '#dropdown-uMenu a.item.image', function () {
         const image = $(this).find('img').attr('src');
         const name = $(this).find('img').data('name');
         $('header.main').css('background-image', 'url(' + image + ')');

         Cookies.set('CMSA_USERBG', name, {
            expires: 360,
            path: '/',
            sameSite: 'strict'
         });
      });

      /* == Transitions == */
      $(document).on('click', '[data-slide="true"]', function () {
         const trigger = $(this).data('trigger');
         $(trigger).slideToggle(100);
      });

      /* == Input focus == */
      $(document).on('focusout', '.wojo.input input, .wojo.input textarea', function () {
         $('.wojo.input').removeClass('focus');
      });
      $(document).on('focusin', '.wojo.input input, .wojo.input textarea', function () {
         $(this).closest('.input').addClass('focus');
      });

      /* == Range Slider == */
      $('input[type="range"]').wRange();

      /* == Tabs == */
      $('.wojo.tabs').wTabs();

      /* == Input Tags == */
      $('.wojo.tags').wTags();

      /* == Progress Bars == */
      $('.wojo.progress').wProgress();

      /* == Accordion == */
      $('.wojo.accordion').wAccordion();

      /* == Dimmable content == */
      $(document).on('change', '.is_dimmable', function () {
         const dataset = $(this).data('set');
         const status = $('input[type=checkbox]', this).is(':checked') ? 1 : 0;
         const result = $.extend(true, dataset.option[0], {
            'active': status
         });
         $.post(config.aurl + dataset.url, result);
         $(dataset.parent).toggleClass('active');
         $(this).closest('.card').toggleClass('dimmed');
      });

      /* == Datepicker == */
      $('.datepick').wDate({
         months: config.lang.monthsFull,
         short_months: config.lang.monthsShort,
         days_of_week: config.lang.weeksFull,
         short_days: config.lang.weeksShort,
         days_min: config.lang.weeksSmall,
         selected_format: 'DD, mmmm d',
         month_head_format: 'mmmm yyyy',
         format: config.lang.dateFormat,
         clearBtn: true,
         todayBtn: true,
         cancelBtn: true,
         clearBtnLabel: config.lang.clear,
         cancelBtnLabel: config.lang.canBtn,
         okBtnLabel: config.lang.ok,
         todayBtnLabel: config.lang.today,
      }).on('datechanged', function (event) {
         if ($(this).attr('data-element')) {
            const element = $(this).data('element');
            const parent = $(this).data('parent');

            const date = new Date(event.date);
            const day = date.getDate();
            const month = config.lang.monthsFull[date.getMonth()];
            const year = date.getFullYear();
            const formatted = month + ' ' + day + ', ' + year;

            $(parent).html(formatted);
            if ($(element).is(':input')) {
               $(element).val(event.date).trigger('change');
            } else {
               $(element).html(formatted);
            }
         }
      });

      $('.timepick').wTime({
         timeFormat: 'hh:mm:ss.000', // format of the time value (data-time attribute)
         format: 'hh:mm t', // format of the input value
         is24: true, // format 24 hour header
         readOnly: true, // determines if input is readonly
         hourPadding: true, // determines if display value has zero padding for hour value less than 10 (i.e. 05:30 PM); 24-hour format has padding by default
         btnNow: config.lang.now,
         btnOk: config.lang.ok,
         btnCancel: config.lang.canBtn,
      });

      /* == From/To date range == */
      $fromdate.wDate({
         rangeTo: $enddate,
         clearBtn: true,
         todayBtn: true,
         cancelBtn: true,
         format: config.lang.dateFormat,
         days_min: config.lang.weeksSmall,
         clearBtnLabel: config.lang.clear,
         cancelBtnLabel: config.lang.canBtn,
         okBtnLabel: config.lang.ok,
         todayBtnLabel: config.lang.today,
      });
      $enddate.wDate({
         rangeFrom: $fromdate,
         clearBtn: true,
         todayBtn: true,
         cancelBtn: true,
         format: config.lang.dateFormat,
         days_min: config.lang.weeksSmall,
         clearBtnLabel: config.lang.clear,
         cancelBtnLabel: config.lang.canBtn,
         okBtnLabel: config.lang.ok,
         todayBtnLabel: config.lang.today,
      });

      /* == Inline Edit == */
      $('#editable, .wedit').on('validate', '[data-editable]', function (e, val) {
         if (val === '') {
            return false;
         }

      }).on('change', '[data-editable]', function (e, val) {
         const dataset = $(this).data('set');
         const $this = $(this);
         const result = $.extend(true, dataset, {
            title: val,
         });

         $.ajax({
            type: 'POST',
            url: config.aurl + dataset.url,
            dataType: 'json',
            data: result,
            beforeSend: function () {
               $this.animate({
                  opacity: 0.2
               }, 800);
            },
            success: function (json) {
               $this.animate({
                  opacity: 1
               }, 800);
               setTimeout(function () {
                  $this.html(json.title).fadeIn('slow');
               }, 1000);
            }
         });
      }).editableTableWidget();

      /* == Avatar Upload == */
      $('[data-type="image"]').wavatar({
         text: config.lang.selPic,
         validators: {
            maxWidth: 3200,
            maxHeight: 1800
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
                  text: file.name + ' must be width:3200px, and height:1800px  max.'
               });
            }
         }
      });

      /* == Basic color picker == */
      $('[data-wcolor="simple"]').each(function () {
         const set = $(this).data('color');
         $(this).wojocolors({
            color: set.color,
            opacity: 1,
            format: set.format,
            mode: 'swatches',
            defaultValue: set.color,
            theme: 'wojo input color',
            swatches: ['#1abc9c', '#16a085', '#2ecc71', '#27ae60', '#3498db', '#2980b9', '#9b59b6', '#8e44ad', '#34495e', '#2c3e50', '#f1c40f', '#f39c12', '#e67e22', '#d35400', '#e74c3c', '#c0392b', '#ecf0f1', '#bdc3c7', '#95a5a6', '#7f8c8d']
         });
      });

      /* == Full color picker == */
      $('[data-wcolor="full"]').each(function () {
         const set = $(this).data('color');
         $(this).wojocolors({
            mode: 'full',
            opacity: set.opacity,
            color: set.color,
            format: set.format,
            defaultValue: set.color,
            theme: 'wojo input color',
            change: function (color) {
               //return color;
            },
         });
      });

      /* == Editor == */
      $('.bodypost').redactor({
         replaceTags: {
            'b': 'strong',
            'strike': 'del'
         },
         plugins: ['alignment', 'fontcolor', 'wicons', 'video', 'imagemanager', 'definedlinks', 'fullscreen'],
         definedlinks: config.aurl + 'ajax/?action=getlinks',
         imageUpload: config.aurl + 'ajax/',
         imageManagerJson: config.aurl + 'ajax/?action=getImages',
         imageData: {
            action: 'eupload',
         },
         callbacks: {
            image: {
               uploadError: function (json) {
                  $.wNotice({
                     autoclose: 12000,
                     type: json.type,
                     title: json.title,
                     text: json.message
                  });
               }
            },
            file: {
               uploadError: function (json) {
                  $.wNotice({
                     autoclose: 12000,
                     type: json.type,
                     title: json.title,
                     text: json.message
                  });
               }
            }
         }
      });

      $('.altpost').redactor({
         minHeight: '200px',
         plugins: ['alignment', 'fontcolor', 'definedlinks', 'fullscreen'],
      });

      /* == Clear Session Debug Queries == */
      $('#debug-panel').on('click', 'a.clear_session', function () {
         $.post(config.aurl + 'ajax/', {
            action: 'debugSession'
         });
         $(this).css('color', '#222');
      });

      /* == Single File Picker == */
      $(document).on('click', '.filepicker', function () {
         const parent = $(this).data('parent');
         const type = $(this).data('ext');
         const update = $(this).data('update');

         $.get(config.url + 'filepicker.php', {
            pickFile: 1,
            editor: true
         }, function (data) {
            $('<div class="wojo big modal"><div class="dialog" role="document"><div class="content">' + data + '</div></div></div>').modal();
            $('#result').on('click', '.is_file', function () {
               const dataset = $(this).data('set');
               switch (type) {
                  case 'images':
                     if (dataset.image === 'true') {
                        $(parent).val(dataset.url);
                        $.modal.close();
                     }
                     break;
                  case 'videos':
                     if (dataset.ext === 'mp4' || dataset.ext === 'ogv' || dataset.ext === 'wembm') {
                        $(parent).val(dataset.url);
                        $.modal.close();
                     }
                     break;
               }
               if (update) {
                  $(update.id).attr('src', update.src + dataset.url);
               }
            });
         });
      });

      /* == Search == */
      let timeout;
      $(document).on('keyup', '.wojo.ajax.input input', function () {
         window.clearTimeout(timeout);
         const $container = $('.wojo.ajax.input .results');
         const $this = $(this);
         const $icon = $(this).next('.icon');
         const srch_string = $(this).val();

         const page = $(this).data('page');
         const type = $(this).data('type');
         let url = '';
         $container.empty();

         if (type === 'modules' || type === 'plugins') {
            url = config.aurl + type + '/' + page + '/ajax/';
         } else {
            url = config.aurl + 'ajax/';
         }

         if (srch_string.length > 3) {
            $icon.removeClass('search').addClass('spinning spinner');
            timeout = window.setTimeout(function () {
               $.ajax({
                  type: 'post',
                  dataType: 'json',
                  url: url,
                  data: {
                     value: srch_string,
                     action: 'search' + page
                  },
                  success: function (json) {
                     if (json.status === 'success') {
                        $container.html(json.html).fadeIn();
                        $this.on('blur', function () {
                           $container.fadeOut();
                        });
                     }
                     $icon.removeClass('spinning spinner').addClass('search');
                     $(document).on('click', function (event) {
                        if (!($(event.target).is($this))) {
                           $container.fadeOut();
                        }
                     });
                  },
                  error: function () {
                     $icon.removeClass('spinning spinner').addClass('search');
                  }
               });
            }, 700);
         }
         return false;
      });

      /* == Master Form == */
      $(document).on('click', 'button[name=dosubmit]', function () {
         const $button = $(this);
         const action = $(this).data('action');
         const $form = $(this).closest('form');
         let route = $(this).data('route');

         function showResponse(json) {
            setTimeout(function () {
               $($button).removeClass('loading').prop('disabled', false);
            }, 500);
            $.wNotice({
               autoclose: 12000,
               type: json.type,
               title: json.title,
               text: json.message
            });
            if (json.type === 'success' && json.redirect) {
               $('main').transition('scaleOut', {
                  duration: 1000,
                  complete: function () {
                     window.location.href = json.redirect;
                  }
               });
            }
         }

         function showLoader() {
            $($button).addClass('loading').prop('disabled', true);
         }

         const options = {
            target: null,
            beforeSubmit: showLoader,
            success: showResponse,
            type: 'post',
            url: config.surl + route,
            data: {
               action: action
            },
            dataType: 'json'
         };

         $($form).ajaxForm(options).submit();
      });

      /* == Add/Edit Modal Actions == */
      $(document).on('click', '.action:not(.input)', function () {
         const dataset = $(this).data('set');
         const $parent = dataset.parent;
         const $this = $(this);
         let actions = '';
         const route = dataset.url;

         if (dataset.mode !== undefined) {
            $.ajax({
               type: 'POST',
               url: config.aurl + route,
               dataType: 'json',
               data: dataset.option[0]
            }).done(function (json) {
               if (json.type === 'success') {
                  switch (dataset.complete) {
                     case 'remove':
                        $($parent).transition('scaleOut', {
                           duration: 300,
                           complete: function () {
                              $($parent).remove();
                           }
                        });
                        break;

                     case 'replace':
                        $($parent).html(json.html).transition('fadeIn', {
                           duration: 600
                        });
                        break;

                     case 'prepend':
                        $($parent).prepend(json.html).transition('fadeIn', {
                           duration: 600
                        });
                        break;
                  }

                  if (dataset.redirect) {
                     setTimeout(function () {
                        $('main').transition('scaleOut');
                        window.location.href = dataset.redirect;
                     }, 800);
                  }
               }

               if (json.message) {
                  $.wNotice({
                     autoclose: 12000,
                     type: json.type,
                     title: json.title,
                     text: json.message
                  });
               }

            });
         } else {
            $.get(config.aurl + route, dataset.option[0], function (data) {
               if (dataset.buttons !== false) {
                  actions += '' +
                    '<div class="footer">' +
                    '<button type="button" class="wojo small simple button" data="modal:close">' + config.lang.canBtn + '</button>' +
                    '<button type="button" class="wojo small positive button" data="modal:ok">' + dataset.label + '</button>' +
                    '</div>';
               }

               const $wmodal = $('<div class="wojo ' + dataset.modalclass + ' modal"><div class="dialog" role="document"><div class="content">' +
                 '' + data + '' +
                 '' + actions + '' +
                 '</div></div></div>').on($.modal.BEFORE_OPEN, function () {
                  $('.datepick', this).wDate({
                     months: config.lang.monthsFull,
                     short_months: config.lang.monthsShort,
                     days_of_week: config.lang.weeksFull,
                     short_days: config.lang.weeksShort,
                     days_min: config.lang.weeksSmall,
                     selected_format: 'DD, mmmm d',
                     month_head_format: 'mmmm yyyy',
                     format: 'mm/dd/yyyy',
                     clearBtn: true,
                     todayBtn: true,
                     cancelBtn: true,
                     clearBtnLabel: config.lang.clear,
                     cancelBtnLabel: config.lang.canBtn,
                     okBtnLabel: config.lang.ok,
                     todayBtnLabel: config.lang.today,
                  }).on('datechanged', function (event) {
                     if ($(this).attr('data-element')) {
                        const element = $(this).data('element');
                        const parent = $(this).data('parent');

                        const date = new Date(event.date);
                        const day = date.getDate();
                        const month = config.lang.monthsFull[date.getMonth()];
                        const year = date.getFullYear();
                        const formatted = month + ' ' + day + ', ' + year;

                        $(parent).html(formatted);
                        if ($(element).is(':input')) {
                           $(element).val(event.date).trigger('change');
                        } else {
                           $(element).html(formatted);
                        }
                     }
                  });
               }).modal().on('click', '[data="modal:ok"]', function () {
                  $(this).addClass('loading').prop('disabled', true);

                  function showResponse(json) {
                     setTimeout(function () {
                        $('[data="modal:ok"]', $wmodal).removeClass('loading').prop('disabled', false);
                        if (json.message) {
                           $.wNotice({
                              autoclose: 12000,
                              type: json.type,
                              title: json.title,
                              text: json.message
                           });
                        }
                        if (json.type === 'success') {
                           if (dataset.redirect) {
                              setTimeout(function () {
                                 $('main').transition('scaleOut');
                                 window.location.href = json.redirect;
                              }, 800);
                           } else {
                              switch (dataset.complete) {
                                 case 'replace':
                                    $($parent).html(json.html).transition('fadeIn', {
                                       duration: 600
                                    });
                                    break;
                                 case 'replaceWith':
                                    $($this).replaceWith(json.html).transition('fadeIn', {
                                       duration: 600
                                    });
                                    break;
                                 case 'append':
                                    $($parent).append(json.html).transition('scaleIn', {
                                       duration: 300
                                    });
                                    break;
                                 case 'prepend':
                                    $($parent).prepend(json.html).transition('scaleIn', {
                                       duration: 300
                                    });
                                    break;
                                 case 'update':
                                    $($parent).replaceWith(json.html).transition('fadeIn', {
                                       duration: 600
                                    });
                                    break;
                                 case 'insert':
                                    if (dataset.mode === 'append') {
                                       $($parent).append(json.html);
                                    }
                                    if (dataset.mode === 'prepend') {
                                       $($parent).prepend(json.html);
                                    }
                                    break;
                                 case 'highlight':
                                    $($parent).addClass('highlight');
                                    break;

                                 default:
                                    break;
                              }
                              if (dataset.callback) {
                                 const callback = dataset.callback[0];
                                 switch (callback.type) {
                                    case 'select':
                                       break;

                                 }
                              }
                              $.modal.close();
                           }
                        }

                     }, 500);
                  }

                  const options = {
                     target: null,
                     success: showResponse,
                     type: 'post',
                     url: config.aurl + route,
                     data: dataset.option[0],
                     dataType: 'json'
                  };
                  $('#modal_form').ajaxForm(options).submit();
               });
            });
         }
      });

      /* == Modal Delete/Archive/Trash Actions == */
      $(document).on('click', 'a.data', function () {
         const dataset = $(this).data('set');
         const $parent = $(this).closest(dataset.parent);
         //const asseturl = dataset.url;
         const url = config.aurl + dataset.url;
         let subtext = dataset.subtext;
         const children = dataset.children ? dataset.children[0] : null;
         let header;
         let content;
         let btnLabel;

         switch (dataset.action) {
            case 'trash':
               btnLabel = config.lang.trsBtn;
               subtext = config.lang.delMsg8;
               header = config.lang.delMsg3 + ' <span class="text-color-secondary">' + dataset.option[0].title + '?</span>';
               content = '<img src="' + config.url + '/images/trash.svg" class="wojo center small image" alt="">';
               break;

            case 'archive':
               btnLabel = config.lang.arcBtn;
               header = config.lang.delMsg5.replace('[NAME]', '<span class="text-color-secondary">' + dataset.option[0].title + '</span>');
               content = '<img src="' + config.url + '/images/archive.svg" class="wojo center small image" alt="">';
               break;

            case 'unarchive':
               btnLabel = config.lang.uarcBtn;
               header = config.lang.delMsg6.replace('[NAME]', '<span class="text-color-secondary">' + dataset.option[0].title + '</span>');
               content = '<img src="' + config.url + '/images/unarchive.svg" class="wojo center small image" alt="">';
               break;

            case 'restore':
               btnLabel = config.lang.restBtn;
               subtext = config.lang.delMsg9;
               header = config.lang.delMsg7.replace('[NAME]', '<span class="text-color-secondary">' + dataset.option[0].title + '</span>');
               content = '<img src="' + config.url + '/images/restore.svg" class="wojo center small image" alt="">';
               break;

            case 'delete':
               btnLabel = config.lang.delBtn;
               subtext = config.lang.delMsg2;
               header = config.lang.delMsg1;
               content = '<img src="' + config.url + '/images/delete.svg" class="wojo center small image" alt="">';
               break;
         }

         $('<div class="wojo modal"><div class="dialog" role="document"><div class="content">' +
           '<div class="header"><h5>' + header + '</h5></div>' +
           '<div class="body center-align">' + content + '<div class="wojo info icon inverted dashed message margin-top compact center-align"><i class="icon information square"></i>' + subtext + '</div></div>' +
           '<div class="footer">' +
           '<button type="button" class="wojo small simple button" data="modal:close">' + config.lang.canBtn + '</button>' +
           '<button type="button" class="wojo small positive inverted button" data="modal:ok">' + btnLabel + '</button>' +
           '</div></div></div></div>').modal().on('click', '[data="modal:ok"]', function () {
            $(this).addClass('loading').prop('disabled', true);

            $.ajax({
               type: 'POST',
               url: url,
               dataType: 'json',
               data: dataset.option[0]
            }).done(function (json) {
               if (json.type === 'success') {
                  if (dataset.redirect) {
                     $.modal.close();
                     $.wNotice({
                        autoclose: 4000,
                        type: json.type,
                        title: json.title,
                        text: json.message
                     });
                     $('main').transition('scaleOut', {
                        duration: 4000,
                        complete: function () {
                           window.location.href = dataset.redirect;
                        }
                     });
                  } else {
                     if (dataset.action === 'restore') {
                        $($parent).transition('fadeOut', {
                           duration: 300,
                           complete: function () {
                              $($parent).remove();
                           }
                        });
                     } else {
                        $($parent).transition('scaleOut', {
                           duration: 300,
                           complete: function () {
                              $($parent).remove();
                           }
                        });
                     }
                     if (children) {
                        $.each(children, function (i, values) {
                           $.each(values, function (k, v) {
                              if (v === 'html') {
                                 $(i).html(json[k]);
                              } else {
                                 $(i).val(json[k]);
                              }
                           });
                        });
                     }
                     $('.wojo.modal').find('.small.image').attr('src', config.url + '/images/checkmark.svg').transition('scaleIn', {
                        duration: 500,
                        complete: function () {
                           $.modal.close();
                           $.wNotice({
                              autoclose: 6000,
                              type: json.type,
                              title: json.title,
                              text: json.message
                           });
                        }
                     });
                  }
               }
            });
         });
      });
   };
})(jQuery);