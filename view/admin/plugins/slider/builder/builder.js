(function ($, window, document, undefined) {
   "use strict";
   const pluginName = 'Builder';
   let bMode = "design";
   let $activeElement;
   let $activeSection;
   let $activeRow;

   const wraps = {
      sectionWrap: '<a data-redonly="true" class="grid-insert"><i class="icon plus circle"></i></a>',
   };

   const cssProp = [
      "paddingTop",
      "paddingBottom",
      "paddingLeft",
      "paddingRight",
      "marginTop",
      "marginBottom",
      "marginLeft",
      "marginRight",
      "borderTopLeftRadius",
      "borderTopRightRadius",
      "borderBottomLeftRadius",
      "borderBottomRightRadius",
      "boxShadow",
      "background",
      "backgroundColor",
      "backgroundAttachment",
      "backgroundImage",
      "backgroundPosition",
      "backgroundRepeat",
      "backgroundSize",
      "filter",
      "borderTopWidth",
      "borderBottomWidth",
      "borderLeftWidth",
      "borderRightWidth",
      "borderTopStyle",
      "borderBottomStyle",
      "borderLeftStyle",
      "borderRightStyle",
      "borderTopColor",
      "borderBottomColor",
      "borderLeftColor",
      "borderRightColor"
   ];

   const flexAlign = [
      "justify-center",
      "justify-end",
      "justify-start",
      "align-top",
      "align-middle",
      "align-bottom"
   ];

   const flexSelfAlign = [
      "align-self-top",
      "align-self-middle",
      "align-self-bottom"
   ];

   let htmlEditor = ace.edit("tempHtml", {
      mode: "ace/mode/html",
      theme: "ace/theme/tomorrow",
      highlightActiveLine: false,
      displayIndentGuides: true,
      wrap: true,
      showPrintMargin: false
   });

   /**
    * Description
    * @method Plugin
    * @param element
    * @param options
    * @constructor
    */
   function Plugin(element, options) {

      this.element = element;
      this._name = pluginName;
      this._defaults = $.fn.Builder.defaults;
      this.options = $.extend({}, this._defaults, options);

      this.init();

   }

   $.extend(Plugin.prototype, {
      /**
       * Description
       * @method init
       * @return
       */
      init: function () {
         this._initBuilder();
         this.bindEvents();
         this._editSection();
         this._toolbar();
         this._animations();

      },

      /**
       * Description
       * @method _initBuilder
       * @return
       */
      _initBuilder: function () {
         const plugin = this;
         const $builder = $("#builder");

         $(this.element).find('.row').each(function () {
            const id = plugin.makeid();
            $(this).prepend(wraps.sectionWrap).attr("data-id", id);
         });

         $(".is_draggable").on('mousedown', function (e) {
            const dr = $(this).addClass("drag");
            const height = dr.outerHeight();
            const width = dr.outerWidth();
            const ypos = dr.offset().top + height - e.pageY;
            const xpos = dr.offset().left + width - e.pageX;
            $(document).on('mousemove', function (e) {
               const itop = e.pageY + ypos - height;
               const ileft = e.pageX + xpos - width;
               if (dr.hasClass("drag")) {
                  dr.offset({
                     top: itop,
                     left: ileft
                  });
               }
            }).on('mouseup', function () {
               dr.removeClass("drag");
            });
         });

         $("#element-helper").on('click', "[data-tab]", function (event) {
            const tab_id = $(this).attr('data-tab');

            $('#element-helper [data-tab]').removeClass('active');
            $('#element-helper .wojo.tab').removeClass('active');

            $(this).addClass('active');
            $("#" + tab_id).addClass('active');
            event.preventDefault();
         });

         $(this.element).on('click', 'a', function () {
            return false;
         });

         $('.reswitch').on('click', 'a.action', function () {
            $builder.removeClass('tabletview phoneview');
            const mode = $(this).data('mode');
            $('.reswitch').find('.icon.primary').removeClass('primary');

            switch (mode) {
               case "screen":
                  $builder.animate({
                     width: '100%'
                  }, 1000, function () {
                     $(this).removeClass('tabletview phoneview');
                     $(this).removeAttr("style");
                  });
                  break;

               case "tablet":
                  $builder.addClass('tabletview');
                  $builder.animate({
                     width: '1024px'
                  }, 1000);
                  break;

               case "phone":
                  $builder.addClass('phoneview');
                  $builder.animate({
                     width: '480px'
                  }, 1000);
                  break;
            }
            $(".icon", this).addClass('primary');
         });

         //Save page
         $('#saveAll').on('click', function () {
            const $button = $(this);
            $button.addClass("loading");
            $(plugin.element).children().find(".grid-insert").remove();

            $(plugin.element).find('.ws-layer').each(function () {
               $(this).removeClass("active");
               $(this).removeAttr("role aria-labelledby");
            });

            $(plugin.element).find('.ws-layer').find("*").each(function () {
               $(this).removeAttr("data-redactor-span data-redactor-style-cache");
            });

            const id = $('.uitem', plugin.element).attr('id');
            const mode = $('.uitem', plugin.element).attr('data-type');
            const raw = $(plugin.element).html();
            let $tempData = $("#tempData");

            $tempData.html($(".ucontent", plugin.element).html());
            $tempData.children().removeAttr('data-id');

            const content = $tempData.html();
            let image = $(".uimage", plugin.element).css("backgroundImage");
            image = image.replace(/(url\(|\)|'|")/gi, '');
            const color = $(".uimage", plugin.element).css("backgroundColor");
            let attr = $(".ucontent", plugin.element).attr("class");
            attr = attr.replace('ucontent', '');
            attr = $.trim(attr);

            $.post(plugin.options.purl + 'action/', {
               action: "saveSlideData",
               id: id.replace('item_', ''),
               html: content,
               html_raw: raw,
               image: image,
               color: color,
               mode: mode,
               attr: attr,
               slidename: plugin.options.slidename
            }, function (json) {
               $.wNotice({
                  autoclose: 12000,
                  type: json.type,
                  title: json.title,
                  text: json.message
               });
               $button.removeClass('loading');
               $(".row", plugin.element).prepend(wraps.sectionWrap);
            }, "json");
         });
      },

      /**
       * Description
       * @method bindEvents
       * @return
       */
      bindEvents: function () {
         this._onEvents();
         this._editElements();
         this._editCanvas();
      },

      /**
       * Description
       * @method _toolbar
       * @return
       */
      _toolbar: function () {
         const plugin = this;
         const $builderHeader = $("#builderHeader");
         const $builderAside = $("#builderAside");
         const $tempData = $("#tempData");
         const $tempHtml = $("#tempHtml");
         const $editSource = $("#editSource");
         const $elHelper = $("#element-helper");

         $builderHeader.on('click', '.is_position', function () {
            if ($activeSection !== undefined && $activeSection.hasClass("active")) {
               const $row = $activeSection.closest(".row");
               const salign = $(this).data("align-self");
               $row.removeClass(flexSelfAlign).addClass(salign);
            } else {
               const align = $(this).data("mode");
               if ($(this).children().is(".primary")) {
                  $(this).children().removeClass("primary");
                  $(plugin.element).find(".ucontent").removeClass(flexAlign);
               } else {
                  $builderHeader.find(".is_position .icon").removeClass("primary");
                  $(this).children().addClass("primary");
                  $(plugin.element).find(".ucontent").removeClass(flexAlign).addClass(align);
               }
            }
         });

         //enter edit mode
         $builderAside.on('click', '.editor', function () {
            bMode = "edit";
            $(plugin.element).find("a.grid-insert").addClass("hide");
            $builderHeader.find(".button").addClass("disabled");
            $builderAside.find(".button").addClass("disabled");
            $builderAside.find(".button.save").removeClass("disabled");
            if ($activeSection.length) {
               $(plugin.element).find(".ws-layer:not(.active)").addClass("editing");
               $(plugin.element).find(".ws-layer").closest(".row").attr("data-mode", "readonly");
               $activeSection.closest(".row").removeAttr("data-mode");
            }
         });

         //exit edit mode
         $builderAside.on('click', '.save', function () {
            bMode = "design";
            $(plugin.element).find("a.grid-insert").removeClass("hide");
            $(plugin.element).find(".row").removeAttr("data-mode");
            $(plugin.element).find(".ws-layer").removeClass("editing");
            $builderAside.find(".button").removeClass("disabled");
            $builderHeader.find(".button").removeClass("disabled");
            $(this).addClass("disabled");
         });

         //edit html
         $builderAside.on('click', '.html', function () {
            bMode = "design";
            const html = $activeSection.html();

            htmlEditor.setValue(html);
            /*
                        $tempData.html(html);
                        $tempHtml.val($tempData.html());

                        plugin._formatSource($tempHtml);
            */

            $("#editSource").modal({
               destroy: false
            }).on('click', '[data="modal:ok"]', function () {
               $activeSection.html(htmlEditor.getValue());
               $.modal.close();
            });

         });

         //edit canvas html
         $builderAside.on('click', '.editHtml', function () {
            bMode = "design";
            const element = $(plugin.element).find(".ucontent");
            $tempHtml.val("");
            $tempData.html("");

            $tempData.html(element.html());
            $tempData.find(".grid-insert").remove();
            /*
                        $tempHtml.val($tempData.html());
                        plugin._formatSource($tempHtml);
            */
            htmlEditor.setValue($tempData.html());

            $editSource.modal({
               destroy: false
            }).on('click', '[data="modal:ok"]', function () {
               element.html(htmlEditor.getValue());
               $(element).find('.row').each(function () {
                  $(this).prepend(wraps.sectionWrap);
               });
               $.modal.close();
            });
         });

         //insert grid
         $(plugin.element).on('click', '.grid-insert', function () {
            $activeRow = $(this).parent(".row");
            plugin.makeRows(20);
         });

         //insert element
         $(this.element).on('click', '.is_empty', function () {
            bMode = "design";
            $activeElement = $(this);
            $(plugin.element).find(".row").attr("data-mode", "readonly");

            if ($elHelper.is(".hide-all")) {
               $elHelper.fadeIn();
            }
            plugin._prepareButton();
            plugin._prepareText();
            plugin._prepareImage();
            plugin._prepareIcon();
         });

         $elHelper.on('click', '.insert', function (event) {
            const el = $elHelper.find(".tbutton.active");
            const type = el.data("type");

            switch (type) {
               case "button":
                  plugin._insertButton();
                  break;

               case "icon":
                  plugin._insertIcon();
                  break;

               case "image":
                  plugin._insertImage();
                  break;

               default:
                  plugin._insertText();
                  break;
            }

            $activeElement.removeClass("is_empty");

            event.preventDefault();
         });

         $elHelper.on('click', 'a.close-styler', function (event) {
            $elHelper.fadeOut();
            $(plugin.element).find(".row").removeAttr("data-mode");
            event.preventDefault();
         });

         //delete section
         $builderAside.on('click', '.is_trash', function () {
            if ($activeSection.length) {
               const parent = $activeSection.parent();

               $activeSection.remove();
               $activeSection = '';
               $builderAside.find(".is_edit").addClass("disabled");
               $builderHeader.find(".is_edit").addClass("disabled");
               $builderHeader.find(".is_size").addClass("disabled");
               $builderHeader.find(".is_align").addClass("disabled");
               if (parent.children().length === 0) {
                  parent.addClass("is_empty");
               }
            }
         });
      },

      /**
       * Description
       * @method _animations
       * @return
       */
      _animations: function () {
         const plugin = this;
         const $anipack = $("#anipack");
         //Animation
         $anipack.on('click', '.item', function () {
            const selected = $(this).data("value");
            $anipack.find(".selected.item").removeClass("selected");
            $(this).addClass("selected");

            if ($activeSection.length) {
               $activeSection.attr("data-animation", selected);
               let type = $activeSection.attr('data-animation');
               let time = $activeSection.attr('data-duration');
               let delay = $activeSection.attr('data-delay');

               if (!type) {
                  type = selected;
               }
               if (!time) {
                  time = 1500;
               }

               if (!delay) {
                  delay = 500;
               }

               if (selected === "none") {
                  $activeSection.attr("data-animation", '');
               } else {
                  const values = "animate " + type;
                  $activeSection.addClass(values).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                     setTimeout(function () {
                        $activeSection.removeClass(values);
                     }, 500);
                  });
               }
            }
         });

         // Play Animation
         $("#play").on('click', function () {
            $.each($(plugin.element).find('.ws-layer'), function () {
               const $this = $(this);
               $(this).removeClass("active");
               const type = $(this).attr('data-animation');
               if (type) {
                  const values = "animate " + type;
                  $this.addClass(values).on('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                     setTimeout(function () {
                        $this.removeClass(values);
                     }, 500);
                  });
               }
            });
         });

         //Animation time
         $("#duration").on('change', function () {
            const time = $(this).val().replace(/[^0-9.]/g, '');
            const type = $activeSection.attr('data-animation');
            if ($activeSection.length) {
               $activeSection.attr("data-duration", time);
               if (type) {
                  const values = "animate " + type;
                  $activeSection.addClass(values).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                     setTimeout(function () {
                        $activeSection.removeClass(values);
                     }, 500);
                  });
               }
            }
         });

         //Animation delay
         $("#delay").on('change', function () {
            const time = $(this).val().replace(/[^0-9.]/g, '');
            const type = $activeSection.attr('data-animation');
            if ($activeSection.length) {
               $activeSection.attr("data-delay", time);
               if (type) {
                  const values = "animate " + type;
                  $activeSection.addClass(values).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                     setTimeout(function () {
                        $activeSection.removeClass(values);
                     }, 500);
                  });
               }
            }
         });
      },

      /**
       * Description
       * @method _editElements
       * @return
       */
      _editElements: function () {
         const plugin = this;
         $("#builderAside").on('click', '.element', function () {
            const css = $activeSection.css(cssProp);

            $("#section-helper input[name=marginTop]").val(parseInt(css.marginTop)).wRange({
               onSlide: function (position, value) {
                  $activeSection.css("marginTop", parseInt(value));
               }
            });
            $("#section-helper input[name=marginBottom]").val(parseInt(css.marginBottom)).wRange({
               onSlide: function (position, value) {
                  $activeSection.css("marginBottom", parseInt(value));
               }
            });
            $("#section-helper input[name=marginLeft]").val(parseInt(css.marginLeft)).wRange({
               onSlide: function (position, value) {
                  $activeSection.css("marginLeft", parseInt(value));
               }
            });
            $("#section-helper input[name=marginRight]").val(parseInt(css.marginRight)).wRange({
               onSlide: function (position, value) {
                  $activeSection.css("marginRight", parseInt(value));
               }
            });

            $(plugin.element).find(".row").attr("data-mode", "readonly");

            if ($("#section-helper").is(".hide-all")) {
               $('#section-helper').fadeIn();
            }
         });


         $("#section-helper").on('click', 'a.close-styler', function (event) {
            $('#section-helper').fadeOut();
            $(plugin.element).find(".row").removeAttr("data-mode");
            event.preventDefault();
         });
      },

      /**
       * Description
       * @method _editCanvas
       * @return
       */
      _editCanvas: function () {
         const plugin = this;
         $(".editCanvas").on('click', function () {
            $(plugin.element).find(".ws-layer.active").removeClass("active");
            const css = $(plugin.element).find(".ucontent").css(cssProp);

            $("#canvas-helper input[name=paddingTop]").asRange('set', parseInt(css.paddingTop));
            $("#canvas-helper input[name=paddingBottom]").asRange('set', parseInt(css.paddingBottom));

            $('.rangers').on('asRange::change', function (event, el) {
               switch (el.$element.prop('name')) {
                  case "paddingTop":
                  case "paddingBottom":
                     $(plugin.element).find(".ucontent").css(el.$element.prop('name'), el.value);
                     break;
               }
            });

            if ($("#canvas-helper").is(".hidden")) {
               $('#canvas-helper').transition('scale');
            }
         });

         $("#canvas-helper").on('click', 'a.close-styler', function (event) {
            $('#canvas-helper').transition('fade out');
            event.preventDefault();
         });
      },

      /**
       * Description
       * @method _editSection
       * @return
       */
      _editSection: function () {
         const plugin = this;
         const $bh = $("#builderHeader");
         const $anipack = $("#anipack");

         $(this.element).on("click", ".ws-layer", function () {
            $activeSection = $(this);
            $(plugin.element).find(".ws-layer.active").removeClass("active");
            $activeSection.addClass("active");

            const time = $(this).attr('data-duration');
            const delay = $(this).attr('data-delay');
            const animation = $(this).attr('data-animation');

            $anipack.find(".selected").removeClass("selected");
            $("[data-wdropdown='#anipack']").find("span").text(animation);
            $anipack.find(".item[data-value='" + animation + "']").addClass("selected");

            $("#duration").val(time);
            $("#delay").val(delay);

            if (bMode === "design") {
               $("#builderAside").find(".is_edit").removeClass("disabled");
               $bh.find(".is_edit").removeClass("disabled");
               $bh.find(".is_size").removeClass("disabled");
               $bh.find(".is_size .icon").removeClass("primary");
               $bh.find("[data-self=false]").addClass("disabled");
            }
         });
      },

      /**
       * Description
       * @method _prepareButton
       * @return
       */
      _prepareButton: function () {
         const plugin = this;
         const $elButton = $("#el_button");
         const $modalIcons = $("#modalIcons");
         const $iconHelper = $("#icon-helper");
         const $bb = $("#buttons .button");

         $("#el_button .docolors").wojocolors({
            opacity: "0.5",
            format: "rgb",
            mode: "full",
            defaultValue: "rgba(0, 128, 96, 0.35)",
            theme: "wojo input color",
            change: function (color) {
               switch ($(this).data('color')) {
                  case "bg":
                     $bb.css("background-color", color);
                     break;
                  case "text":
                     $bb.css("color", color);
                     break;
                  case "icon":
                     $("icon", $bb).css("color", color);
                     break;

               }
               $(this).prev('input').val(color);
            }
         });

         $elButton.on('click', '#buttons .button', function (e) {
            if ($(e.target).is('i')) {

               if ($modalIcons.is(':empty')) {
                  let list = '';

                  $.getJSON(plugin.options.url + 'snippets/iconset.json')
                    .done(function (json) {
                       $.each(json.iconset, function (i, item) {
                          list += '<div class="columns"><a class="wojo basic small icon fluid button" title="' + item.name + '"><i class="icon ' + item.code + '"></i></a></div>';
                       });

                       $modalIcons.html(list);

                    });
               }
               if ($iconHelper.is(".hide-all")) {
                  $iconHelper.fadeIn();
               }
               $modalIcons.on('click', '.columns a.button', function () {
                  const icon = $(this).html();
                  $("#buttons i.icon").replaceWith(icon);
               });

               $iconHelper.on('click', 'a.close-styler', function (event) {
                  $iconHelper.fadeOut();
                  event.preventDefault();
               });
            }
            $("#buttons .button").removeClass('elactive').parent().removeAttr("data-active");
            $(this).addClass('elactive').parent().attr("data-active", "true");
         });

         $elButton.on('change', 'input[name=btext]', function () {
            $("#buttons .button span").text($(this).val());
         });

         $elButton.on('change', 'input[name=burl]', function () {
            $("#buttons .button").attr("href", $(this).val());
         });
      },

      /**
       * Description
       * @method _insertButton
       * @return
       */
      _insertButton: function () {
         const $active = $("#el_button").find('[data-active=true]');
         //let $tempData = $("#tempData");
         if ($active.length) {
            let $tempData = $("#tempData");
            $active.children().removeClass("elactive");
            $tempData.html($active.html());
            $("#tempData span").replaceWith(function () {
               return $(this).contents();
            });
            const button = '<div class="ws-layer" data-delay="0" data-duration="0" data-animation="" data-type="button">' + $tempData.html() + '</div>';
            $activeElement.append(button);
            $active.removeAttr("data-active");
         } else {
            return false;
         }
      },

      /**
       * Description
       * @method _prepareImage
       * @return
       */
      _prepareImage: function () {
         $("#el_image").on('click', '.item', function () {
            $("#el_image .item").removeClass('elactive').removeAttr("data-active");
            $(this).addClass('elactive').attr("data-active", "true");
         });
      },

      /**
       * Description
       * @method _insertImage
       * @return
       */
      _insertImage: function () {
         const plugin = this;
         const $active = $("#el_image").find('[data-active=true]');
         if ($active.length) {
            const str = $active.data("src");
            const image = '<div class="ws-layer" data-delay="0" data-duration="0" data-animation="" data-type="image"><img src="' + plugin.options.surl + str + '" alt=""></div>';
            $activeElement.append(image);
         } else {
            return false;
         }
      },

      /**
       * Description
       * @method _prepareIcon
       * @return
       */
      _prepareIcon: function () {
         $("#el_icon").on('click', '.button', function () {
            //$("#el_icon .button").removeClass('primary').removeAttr("data-active");
            $("#el_icon .button.primary").not(this).removeClass('primary').addClass("simple").removeAttr("data-active");
            $(this).toggleClass("primary simple").attr("data-active", "true");
            //$(this).addClass('primary').attr("data-active", "true");
         });
      },

      /**
       * Description
       * @method _insertIcon
       * @return
       */
      _insertIcon: function () {
         const $active = $("#el_icon").find('[data-active=true]');
         if ($active.length) {
            const html = $active.html();
            const image = '<div class="ws-layer" data-delay="0" data-duration="0" data-animation="" data-type="icon">' + html + '</div>';
            $activeElement.append(image);
         } else {
            return false;
         }
      },

      /**
       * Description
       * @method _prepareText
       * @return
       */
      _prepareText: function () {
         $("#el_text").on('click', '.item', function () {
            $("#el_text .item").removeClass('elactive').removeAttr("data-active");
            $(this).addClass('elactive').attr("data-active", "true");
         });
      },

      /**
       * Description
       * @method _insertText
       * @return
       */
      _insertText: function () {
         const $active = $("#el_text").find('[data-active=true]');
         if ($active.length) {
            const html = $active.html();
            const text = '<div class="ws-layer" data-delay="0" data-duration="0" data-animation="" data-type="text">' + html + '</div>';
            $activeElement.append(text);
         } else {
            return false;
         }
      },

      /**
       * Description
       * @method makeRows
       * @param row
       */
      makeRows: function (row) {
         let html = '';
         switch (row) {

            case 2:
               html += '' +
                 '<div class="columns mobile-50 phone-100"></div>' +
                 '<div class="columns mobile-50 phone-100"></div>';
               break;

            case 3:
               html += '' +
                 '<div class="columns phone-100"></div>' +
                 '<div class="columns phone-100"></div>' +
                 '<div class="columns phone-100"></div>';
               break;

            case 4:
               html += '' +
                 '<div class="columns tablet-50 mobile-100 phone-100"></div>' +
                 '<div class="columns tablet-50 mobile-100 phone-100"></div>' +
                 '<div class="columns tablet-50 mobile-100 phone-100"></div>' +
                 '<div class="columns tablet-50 mobile-100 phone-100"></div>';
               break;

            case 5:
               html += '' +
                 '<div class="columns screen-60 tablet-60 mobile-50 phone-100"></div>' +
                 '<div class="columns screen-40 tablet-40 mobile-50 phone-100"></div>';
               break;

            case 6:
               html += '' +
                 '<div class="columns screen-40 tablet-40 mobile-50 phone-100"></div>' +

                 '<div class="columns screen-60 tablet-60 mobile-50 phone-100"></div>';
               break;

            case 7:
               html += '' +
                 '<div class="columns screen-70 tablet-70 mobile-50 phone-100"></div>' +
                 '<div class="columns screen-30 tablet-30 mobile-50 phone-100"></div>';
               break;

            case 8:
               html += '' +
                 '<div class="columns screen-30 tablet-30 mobile-50 phone-100"></div>' +
                 '<div class="columns screen-70 tablet-70 mobile-50 phone-100"></div>';
               break;

            case 9:
               html += '' +
                 '<div class="columns screen-20 tablet-20 mobile-100 phone-100"></div>' +
                 '<div class="columns screen-60 tablet-60 mobile-100 phone-100"></div>' +
                 '<div class="columns screen-20 tablet-20 mobile-100 phone-100"></div>';
               break;

            case 10:
               html += '' +
                 '<div class="columns screen-20 tablet-20 mobile-100 phone-100"></div>' +
                 '<div class="columns screen-20 tablet-20 mobile-100 phone-100"></div>' +
                 '<div class="columns screen-60 tablet-60 mobile-100 phone-100"></div>';
               break;

            case 11:
               html += '' +
                 '<div class="columns screen-60 tablet-60 mobile-100 phone-100"></div>' +
                 '<div class="columns screen-20 tablet-20 mobile-100 phone-100"></div>' +
                 '<div class="columns screen-20 tablet-20 mobile-100 phone-100"></div>';
               break;

            default:
               html += '<div class="columns"></div>';
               break;
         }

         const el = $("<div class=\"row\">" + html + "</div>").prepend(wraps.sectionWrap);
         el.insertAfter($activeRow);
         el.find(".columns").addClass("is_empty");
      },

      /**
       * Description
       * @method makeid
       * @return string
       */
      makeid: function () {
         let text = "";
         const possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
         for (let i = 0; i < 2; i++) {
            text += possible.charAt(Math.floor(Math.random() * possible.length));
         }
         let text2 = "";
         const possible2 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
         for (let k = 0; k < 5; k++) {
            text2 += possible2.charAt(Math.floor(Math.random() * possible2.length));
         }
         return text + text2;
      },

      /**
       * Description
       * @method _offEvents
       * @return
       */
      _offEvents: function () {
         $(this.element).off('mouseenter mouseleave mouseover mouseout click');
      },

      /**
       * Description
       * @method _onEvents
       * @return
       */
      _onEvents: function () {
         const plugin = this;
         const $bh = $("#builderHeader");
         $(this.element).on("click", function (event) {
            if ($(event.target).is(".uimage")) {
               $(plugin.element).find(".ws-layer.active").removeClass("active");
               $("#builderAside").find(".is_edit").addClass("disabled");
               $bh.find(".is_edit").addClass("disabled");
               $bh.find(".is_position").removeClass("disabled");
            }
         });
      }
   });

   /**
    * Description
    * @method Builder
    * @param options
    * @returns {jQuery.Builder}
    * @constructor
    */
   $.fn.Builder = function (options) {
      this.each(function () {
         if (!$.data(this, pluginName)) {
            $.data(this, pluginName, new Plugin(this, options));
         }
      });
      return this;
   };

   $.fn.Builder.defaults = {
      editables: ["div", "p", "h1", "h2", "h3", "h4", "h5", "h6", "i", "span"],
      url: "",
      surl: "",
      purl: "",
      slidename: "",
      lang: {
         btnOk: "ok",
         btnCancel: "cancel",
         msgUndone: "Are you sure you want to restore it, this action can not be undone!",
         msgUrlError: "Invalid url detected!!!",
      }
   };

})(jQuery, window, document);