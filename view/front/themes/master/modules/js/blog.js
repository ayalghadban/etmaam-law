$(function () {
   'use strict';
   //like item
   $(document).on('click', '.blogLike', function () {
      let like = $(this).data('vote');
      let url = $(this).data('url');
      let id = $(this).data('id');
      let $this = $(this);

      $(this).transition('scaleOut', {
         duration: 800,
         complete: function () {
            $this.replaceWith('<i class="icon check"></i>');
            $.post(url + 'blog/action/', {
               action: 'like',
               id: id,
               type: like
            }, function (json) {
               if (json.status === 'success') {
                  Cookies.set('BLOG_voted', id, {
                     expires: 120,
                     path: '/'
                  });
               }
            }, 'json');
         }
      });
   });
});