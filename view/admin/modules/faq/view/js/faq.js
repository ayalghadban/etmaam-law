(function ($) {
   "use strict";
   $.Faq = function (settings) {
      const config = {
         url: "",
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

      $(".wojo.sortable").sortable({
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
                  type: "items",
                  sorting: order
               }
            });
         }
      });

      // sort categories
      if ($.inArray("category", $.url().segment()) !== -1 || $.inArray("categories", $.url().segment()) !== -1) {
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
                     type: "category",
                     sorting: items
                  }
               });
            },
         });
      }
   };
})(jQuery);