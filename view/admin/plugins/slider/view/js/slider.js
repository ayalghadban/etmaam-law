(function ($) {
   "use strict";
   $.Slider = function (settings) {
      const config = {
         url: "",
         aurl: "",
         surl: "",
         element: "",
         ytapi: "",
         adistance: 5,
         lang: {
            canBtn: "",
            updBtn: "",
         }
      };
      let $activeCard;
      const $source = $("#source");
      const $sortable = $("#sortable");
      if (settings) {
         $.extend(config, settings);
      }

      //Slide Holder actions
      $(".eMenu").on('click', 'a.button', function () {
         const $this = $(this);
         $("#sortable .card").removeClass("outline");
         $activeCard = $this.closest('.card');
         const set = $(this).data('set');

         $activeCard.addClass('loading outline');
         $("#sortable").addClass("read-only");

         switch (set.mode) {
            case "prop":
               $.post(config.url, {
                  action: "properties",
                  id: set.id,
               }, function (json) {
                  if (json.type === "success") {
                     $(':radio[value=' + json.mode + ']', "#source").prop('checked', true);
                     $("#bg_img").val(json.image);
                     $("#bg_color").val(json.color);
                     $("[data-id=cl_asset] .button", "#source").css('backgbound-color', json.color);
                     $('[data-id=' + json.mode + '_asset]').show();
                     $("#source").slideDown(200);
                     $activeCard.removeClass('loading');

                  }
               }, "json");
               break;

            case "duplicate":
               $.post(config.url, {
                  action: "duplicate",
                  id: set.id,
               }, function (json) {
                  if (json.type === "success") {
                     $(json.thumb).insertAfter($activeCard.parent());
                     $('.wedit').editableTableWidget();
                     $activeCard.removeClass('loading outline').css("pointerEvents", "auto");
                     $("#sortable").removeClass("read-only");
                  }

               }, "json");
               break;

            case "delete":
               $.post(config.url, {
                  action: "delete",
                  type: "slide",
                  id: set.id
               }, function (json) {
                  if (json.type === "success") {
                     $activeCard.parent().fadeOut();
                     $activeCard.parent().remove();
                     $("#sortable").removeClass("read-only");
                  }

               }, "json");
               break;
         }
      });

      $source.on('change', 'input[type=radio]', function () {
         const image = $('#bg_img').val();
         const value = $(this).val();
         const $cl_asset = $('[data-id=cl_asset]');
         const $bg_asset = $('[data-id=bg_asset]');
         if ($activeCard.length) {
            switch (value) {
               case "bg":
                  $cl_asset.hide();
                  $bg_asset.show();
                  $activeCard.removeClass("trans").addClass("photo").css({
                     'backgroundImage': 'url(' + config.surl + 'uploads/thumbs/' + image.replace(/.*\//, '') + ')',
                     'backgroundColor': '',
                  });
                  break;

               case "tr":
                  $('[data-id=cl_asset],[data-id=bg_asset]').hide();
                  $activeCard.removeAttr("style").removeClass("photo").addClass("trans");
                  break;

               case "cl":
                  $bg_asset.hide();
                  $cl_asset.show();
                  const color = $('#bg_color').val();
                  $activeCard.removeAttr("style").removeClass("photo trans").css('backgroundColor', color);

                  $("[data-id=cl_asset] #bgColor").wojocolors({
                     opacity: "0.5",
                     format: "rgb",
                     mode: "full",
                     defaultValue: "rgba(0, 128, 96, 0.35)",
                     theme: "wojo input color",
                     change: function (color) {
                        $activeCard.css("backgroundColor", color);
                        $('#bg_color').val(color);

                     },
                  });
                  break;
            }
         }
      });

      //change image
      $source.on('click', '.bg_image', function () {
         $.get(config.aurl + 'filepicker.php', {
            pickFile: 1,
            editor: true
         }, function (data) {
            $('<div class="wojo big modal"><div class="dialog" role="document"><div class="content">' + data + '</div></div></div>').modal();
            $("#result").on('click', '.is_file', function () {
               const dataset = $(this).data('set');
               if (dataset.image === "true") {
                  $("#bg_img").val(dataset.url);
                  $activeCard.css("backgroundImage", "url('" + config.surl + "uploads/thumbs/" + dataset.name + "')");
                  $.modal.close();
               }
            });
         });
      });

      //Close source
      $source.on('click', 'a#closeSource', function () {
         $("#source").slideUp(100);
         const id = $activeCard.parent().data("id");
         const mode = $activeCard.data("mode");
         const color = $activeCard.data("color");

         $.post(config.url, {
            action: "updateSlide",
            id: id,
            mode: mode,
            image: $("#bg_img").val(),
            color: color,
         }, function (json) {
            if (json.type === "success") {
               $activeCard.removeClass("outline");
               $("#sortable").removeClass("read-only");
            }
         }, "json");
      });

      //Add new slide
      $("#addnew").on('click', function () {
         $("#sortable .card").removeClass("outline");
         $.get(config.aurl + 'filepicker.php', {
            pickFile: 1,
            editor: true
         }, function (data) {
            $('<div class="wojo big modal"><div class="dialog" role="document"><div class="content">' + data + '</div></div></div>').modal();
            $("#result").on('click', '.is_file', function () {
               const dataset = $(this).data('set');
               if (dataset.image === "true") {
                  $.post(config.url, {
                     action: "newSlide",
                     id: $.url().segment(-1),
                     image: dataset.url
                  }, function (json) {
                     if (json.type === "success") {
                        $sortable.addClass("read-only");
                        $sortable.append(json.thumb);
                        $activeCard = $("#sortable .card:last").addClass("outline");

                        $("#bg_img").val(json.image);
                        $(':radio[value=' + json.mode + ']', "#source").prop('checked', true);
                        $('[data-id=' + json.mode + '_asset]').show();
                        $("#source").slideDown();
                        $('.wedit').editableTableWidget();
                     }
                  }, "json");

                  $.modal.close();
               }
            });
         });
      });

      $sortable.sortable({
         ghostClass: "ghost",
         handle: ".handle",
         animation: 600,
         onUpdate: function () {
            const order = this.toArray();
            $.ajax({
               type: 'post',
               url: config.url,
               dataType: 'json',
               data: {
                  action: "sort",
                  sorting: order
               }
            });
         }
      });

      //Global Configuration
      $("#layoutMode").on('click', 'a', function () {
         $("#layoutMode .segment a").removeClass('outline');
         $(this).addClass('outline');
         $("input[name=layout]").val($(this).data('type'));
      });
   };
})(jQuery);