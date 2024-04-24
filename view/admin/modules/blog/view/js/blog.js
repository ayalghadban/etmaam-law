(function ($) {
   "use strict";
   $.Blog = function (settings) {
      const config = {
         url: "",
         aurl: "",
         lang: {
            delMsg3: "",
            delMsg8: "",
            canBtn: "",
            trsBtn: "",
            err: "",
            err1: ""
         }
      };

      if (settings) {
         $.extend(config, settings);
      }

      const $micon = $("input[name=icon]");
      const active = $micon.val();
      const $sortable = $('#sortable');
      const activeIcon = active ? $('#cIcons .button').find("." + active.replace(/\s+/g, '.')) : null;
      if (activeIcon) {
         $(activeIcon).parent().removeClass("simple").addClass("primary");
      }

      // select layout item
      $("#layoutMode").on('click', 'a', function () {
         $("#layoutMode .segment a").removeClass('outline');
         $(this).addClass('outline');
         $("input[name=flayout]").val($(this).data('type'));
         $("input[name=layout]").val($(this).data('type'));
      });

      // clear file
      $("#removeFile").on('click', function () {
         $(".group-span-filestyle").find(".badge-light").remove();
         if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $('input:hidden[name=remfile]').remove();
         } else {
            $(this).addClass('active');
            $('<input>').attr({
               type: 'hidden',
               name: 'remfile',
               value: 1
            }).appendTo('#wojo_form');

         }
      });

      /* == Toggle Category icons == */
      $micon.on('click', '.button', function () {
         $('#cIcons .button.primary').not(this).removeClass('primary').toggleClass("simple");
         $(this).toggleClass("primary simple");
         $micon.val($(this).hasClass('primary') ? $(this).children().attr('class') : "");
      });

      // sort categories
      if ($.inArray("category", $.url().segment()) !== -1 || $.inArray("categories", $.url().segment()) !== -1) {
         $('#cIcons').find('i[class="' + $micon.val() + '"]').parent('.button').addClass('highlite');

         /* == Toggle Menu parents == */
         $("#sortlist > ul > li:has(> ul)").addClass('parent');
         $("#sortlist > ul > li.parent > .content .handle").after("<div class=\"arrow\"><i class=\"icon chevron down\"></i></div>");
         $('#sortlist .arrow').on('click', function () {
            $(this).find(".icon").toggleClass("down up");
            const parent = $(this).closest("li");
            $(parent).children("ul").fadeToggle(150);
            event.preventDefault()
         });
         $('.wojo.nestable').sortable({
            group: 'nested',
            animation: 150,
            fallbackOnBody: true,
            swapThreshold: 0.65,
            handle: ".handle",
            ghostClass: "ghost",
            onUpdate: function () {
               const items = this.toArray();
               $.ajax({
                  cache: false,
                  type: "post",
                  url: config.url,
                  dataType: "json",
                  data: {
                     action: "sort",
                     type: "categories",
                     sorting: items
                  }
               });
            },
         });
      }

      // sort images
      $("#sortable").sortable({
         ghostClass: "outline",
         animation: 600,
         onUpdate: function () {
            const order = this.toArray();
            $.post(config.url, {
               action: "sort",
               type: "images",
               sorting: order
            }, function () {
            }, "json");

         }
      });

      // add images
      $('#images').simpleUpload({
         url: config.url,
         dataType: "json",
         types: ['jpg', 'png', 'JPG', 'PNG'],
         error: function (error) {
            if (error.type === 'fileType') {
               $.wNotice({
                  autoclose: 4000,
                  type: "error",
                  title: config.lang.err,
                  text: config.lang.err1
               });
            }
         },
         beforeSend: function () {
            $sortable.closest('.segment').addClass('loading');
         },
         success: function (json) {
            if (json.type === "success") {
               let html = '';
               $.each(json.html, function (key, value) {
                  html += value;
               });
               $('#sortable').prepend(html).sortable();
            } else {
               $.wNotice({
                  autoclose: 6000,
                  type: "error",
                  title: json.title,
                  text: json.message
               });
            }
            $sortable.closest('.segment').removeClass('loading');
         }
      });
   };
})(jQuery);