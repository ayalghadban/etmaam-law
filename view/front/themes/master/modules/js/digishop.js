$(function () {
   'use strict';
   const $ds = $('#digishop');
   const $cl = $('#cartList');
   const $dco = $('#dCheckout');

   //add to cart
   $ds.on('click', 'a.add-digishop', function () {
      let id = $(this).data('id');
      let button = $(this);
      let url = $ds.data('url');
      button.addClass('loading');

      $.post(url + 'digishop/action/', {
         action: 'add',
         id: id,
      }, function (json) {
         if (json.status === 'success') {
            $cl.html(json.html);
         } else {
            $.wNotice({
               autoclose: 12000,
               type: json.type,
               title: json.title,
               text: 'Ooops, there was an error selecting this item.'
            });
         }
         setTimeout(function () {
            button.removeClass('loading');
         }, 1200);
      }, 'json');
   });

   //delete from cart
   $cl.on('click', 'a.deleteItem', function () {
      let id = $(this).data('id');
      let item = $(this).closest('.item');
      let url = $cl.data('url');

      $.post(url + 'digishop/action/', {
         action: 'delete',
         id: id,
      }, function (json) {
         if (json.status === 'success') {
            item.transition('fadeOut', {
               duration: 300,
               complete: function () {
                  $cl.html(json.html);
               }
            });
         }
      }, 'json');
   });

   //like item
   $ds.on('click', '.digishopLike', function () {
      let id = $(this).attr('data-digishop-like');
      let total = $(this).attr('data-digishop-total');
      let score = $(this).parent().find('.likeTotal');
      let url = $ds.data('url');
      let $this = $(this);
      score.html(parseInt(total) + 1);

      $(this).transition('scaleOut', {
         duration: 800,
         complete: function () {
            $this.replaceWith('<i class="icon check"></i>');
            $.post(url + 'digishop/action/', {
               action: 'like',
               id: id
            });
         }
      });
   });

   //load gateway
   $ds.on('change', 'input[name=gateway]', function () {
      let id = $(this).val();
      let url = $ds.data('url');
      $dco.addClass('loading');

      $.get(url + 'digishop/action/', {
         action: 'gateway',
         id: id
      }, function (json) {
         $dco.html(json.message);
         $('html,body').animate({
            scrollTop: $dco.offset().top
         }, 1000);
         $dco.removeClass('loading');
      }, 'json');
   });
});