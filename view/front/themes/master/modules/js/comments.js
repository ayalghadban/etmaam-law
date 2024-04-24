(function ($, window, document, undefined) {
   "use strict";
   const pluginName = "Comments",
     defaults = {
        url: "",
        murl: ""
     };

   function Comments(element, options) {
      this.element = element;

      this.settings = $.extend({}, defaults, options);
      this._defaults = defaults;
      this._name = pluginName;
      this.init();
   }

   $.extend(Comments.prototype, {
      init: function () {
         this.bind();
      },
      bind: function () {
         const base = this;

         //vote
         $(this.element).on('click', 'a.down, a.up', function () {
            const type = $(this).attr('class').replace("item ", "");
            const id = $(this).data('id');
            const icon = $(this).children('.icon');
            const score = $(this).children('span');
            const down = $(this).data('down');
            const up = $(this).data('up');

            icon.removeClass("chevron up down").addClass("check").fadeIn(200);
            $(this).removeClass("up down");

            $.post(base.settings.url + 'action/', {
               action: "vote",
               type: type,
               id: id
            }, function (json) {
               if (json.status === "success") {
                  if (json.type === "down") {
                     score.text(parseInt(down) - 1);
                  } else {
                     score.text(parseInt(up) + 1);
                  }
               }
            }, "json");
         });

         //load reply form
         $(this.element).on('click', 'a.replay', function () {
            $("#replyform, #pError").remove();
            const id = $(this).data('id');
            $.get(base.settings.url + 'action/', {
               action: "load",
               id: id
            }, function (json) {
               if (json.status === "success") {
                  const comment = $("#comment_" + id, base.element).children('.content');
                  comment.append(json.html);
                  $("#replyform").fadeIn();
               }
            }, "json");
         });

         //reply
         $(this.element).on('click', 'button[name=doReply]', function () {
            const id = $(this).closest('.comment').data('id');
            $(this).addClass('loading').prop('disabled', true);

            const data = {
               id: id,
               parent_id: $("input[name=parent_id]").val(),
               section: $("input[name=section]").val(),
               message: $("textarea[name=replybody]").val(),
               username: $("input[name=replayname]").val(),
               url: $("input[name=url]").val(),
               action: "reply"
            };

            base.submitComment(data);
         });

         //new
         $(document).on('click', 'button[name=doComment]', function () {
            $(this).addClass('loading').prop('disabled', true);

            let data = {
               id: 0,
               parent_id: $("input[name=parent_id]").val(),
               section: $("input[name=section]").val(),
               message: $("textarea[name=body]").val(),
               username: $("input[name=name]").val(),
               captcha: $("input[name=captcha]").val(),
               star: $("input[name=star]:checked").val(),
               url: $("input[name=url]").val(),
               action: "comment"
            };

            base.submitComment(data);
         });

         //delete
         $(this.element).on('click', 'a.delete', function () {
            let id = $(this).closest('.comment').data('id');
            $.post(base.settings.url + 'action/', {
               action: "delete",
               id: id
            }, function () {
               let comment = $("#comment_" + id, base.element);
               $(comment).fadeOut();
            });
         });

         //char counter
         $(document).on('keyup paste', '#combody, #replybody', function () {
            let characters = $(this).attr('data-counter');
            if ($(this).val().length > characters) {
               $(this).val($(this).val().substr(0, characters));
            }
            let id = $(this).attr('id');
            let remaining = characters - $(this).val().length;
            let $cs = $("." + id + "_counter span");
            $cs.html(remaining);
            if (remaining <= 10) {
               $cs.addClass('negative').removeClass('positive');
            } else {
               $cs.removeClass('negative').addClass('positive');
            }
         });
      },

      //process comment
      submitComment: function (data) {
         const base = this;
         $.post(this.settings.url + 'action/', data, function (json) {
            if (json.type === "success") {
               $("#replyform").remove();
               if (json.html) {
                  if (data.action === "reply") {
                     $("#comment_" + data.id).children('.content').append(json.html);
                  } else {
                     $(base.element).find(".comments").prepend(json.html);
                     console.log($(base.element));
                     $('html, body').animate({
                        scrollTop: $(base.element).offset().top
                     }, 500);
                     $("#combody").val('');
                  }
               }
            }
            $.wNotice({
               autoclose: 12000,
               type: json.type,
               title: json.title,
               text: json.message
            });
            $("button[name=doReply], button[name=doComment]").removeClass('loading').prop('disabled', false);
         }, "json");
      }
   });

   $.fn[pluginName] = function (options) {
      return this.each(function () {
         if (!$.data(this, pluginName)) {
            $.data(this, pluginName, new Comments(this, options));
         }
      });
   };
})(jQuery, window, document);