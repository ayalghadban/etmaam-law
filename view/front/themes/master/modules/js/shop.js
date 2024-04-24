$(function () {
   'use strict';
   const $shop = $('#shop');
   //add to cart
   $shop.on('click', 'a.add-shop', function () {
      let set = $(this).data('option');
      let id = set.id;
      let type = set.type;
      let button = $(this);
      let url = $('#shop').attr('data-url');
      button.addClass('loading');

      $.post(url + 'shop/action/', {
         action: 'add',
         id: id,
         type: type,
      }, function (json) {
         if (json.status === 'success') {
            if (type === 'simple') {
               $('#simpleCart span').html(json.html);
               $('#simpleCart').transition('shake');
            } else {
               $('#scartList').html(json.html);
            }
         } else {
            $.wNotice({
               autoclose: 12000,
               type: json.status,
               title: json.title,
               text: json.message
            });
         }
         setTimeout(function () {
            button.removeClass('loading');
         }, 1200);
      }, 'json');

   });

   //add to cart variant
   $shop.on('click', 'a.add-shopv', function () {
      let set = $(this).data('option');
      let id = set.id;
      let type = set.type;
      let variant = set.variant;
      let url = $('#shop').attr('data-url');

      $.get(url + 'shop/action/', {
         action: 'variants',
         id: id,
         variant: variant
      }, function (json) {

         let $wmodal = $(
           '<div class="wojo big modal" id="cartModal"><div class="dialog" role="document"><div class="content">' +
           '<div class="body">' + json.html + '</div>' +
           '<div class="footer">' +
           '<button type="button" class="wojo small simple button" data="modal:close">Cancel</button>' +
           '<button type="button" class="wojo small positive button" data="modal:ok">OK</button>' +
           '</div></div></div>').on($.modal.OPEN, function () {
            $('#cartModal').on('click', '.add-var', function () {
               let variant = $(this);
               let parent = $(this).closest('.wojo.list');
               parent.find('.add-var').removeClass('active');
               variant.toggleClass('active');
            });
         }).modal().on('click', '[data="modal:ok"]', function () {
            $(this).addClass('loading').prop('disabled', true);
            let active = $('#cartModal').find('.add-var.active').length;
            let ids = [];

            $('#cartModal .add-var.active').each(function () {
               ids.push($(this).attr('data-id'));
            });
            $.post(url + 'shop/action/', {
               action: 'addVariant',
               ids: ids,
               id: id,
               type: type,
               active: active
            }, function (jsonr) {
               if (jsonr.status === 'success') {
                  if (type === 'simple') {
                     $('#simpleCart span').html(jsonr.html);
                  } else {
                     $('#scartList').html(jsonr.html);
                  }
                  $('#cartModal').modal('hide');
               }

               $.wNotice({
                  autoclose: 12000,
                  type: jsonr.status,
                  title: jsonr.title,
                  text: jsonr.message
               });
               setTimeout(function () {
                  $('[data="modal:ok"]', $wmodal).removeClass('loading').prop('disabled', false);
               }, 1200);
            }, 'json');

            $.modal.close();
         }).on($.modal.AFTER_CLOSE, function () {
            $('#cartModal').remove();
         });
      }, 'json');
   });

   //delete from cart
   $('#scartList').on('click', 'a.deleteItem', function () {
      let id = $(this).data('id');
      let parent = $(this).closest('.item');
      let url = $('#scartList').attr('data-url');

      $.post(url + 'shop/action/', {
         action: 'remove',
         type: 'small',
         id: id,
      }, function (json) {
         if (json.status === 'success') {
            parent.transition('swoopOutTop', {
               duration: 400,
               complete: function () {
                  $('#scartList').html(json.html);
               }
            });
         }
      }, 'json');
   });

   //delete from big cart
   $shop.on('click', 'a.deleteCartItem', function () {
      let id = $(this).data('id');
      let url = $('#shop').attr('data-url');

      $.post(url + 'shop/action/', {
         action: 'remove',
         type: 'big',
         id: id,
      }, function (json) {
         if (json.status === 'success') {
            window.location.href = json.redirect;
         }
      }, 'json');
   });

   //checkout
   $('#checkout').on('click', function () {
      if ($('input[name=\'shipping\']').is(':checked')) {
         window.location.href = $(this).data('url');
      } else {
         $('#sid .item').transition('shake');
      }
   });

   //shipping
   $shop.on('change', 'input[name="shipping"]', function () {
      let val = $(this).val();
      let id = $(this).attr('id').split('_').pop();
      let url = $('#shop').attr('data-url');
      //$("#sCheckout").html(' ');
      $('input[name=gateway]:checked').prop('checked', false);
      $.post(url + 'shop/action/', {
         action: 'shipping',
         value: val,
         id: id,
      }, function (json) {
         if (json.status === 'success') {
            $('#shipping_c').text(json.shipping);
            $('#grand_c').text(json.grand);
         }

      }, 'json');
   });

   //change qty
   $shop.on('change', 'select[name=qty]', function () {
      let id = $(this).data('id');
      let value = $(this).val();
      let parent = $(this).closest('.wojo.item');
      let url = $('#shop').attr('data-url');
      parent.addClass('loading');

      $.post(url + 'shop/action/', {
         action: 'qty',
         id: id,
         value: value,
      }, function (json) {
         if (json.status === 'success') {
            setTimeout(function () {
               $('body').transition({
                  animation: 'scaleOut'
               });
               window.location.href = json.redirect;
            }, 800);
         } else {
            $.wNotice({
               autoclose: 12000,
               type: json.status,
               title: json.title,
               text: json.message
            });
         }

      }, 'json');

   });

   //like item
   $shop.on('click', '.shopLike', function () {
      let id = $(this).attr('data-shop-like');
      let total = $(this).attr('data-shop-total');
      let score = $(this).parent().find('.likeTotal');
      let url = $('#shop').attr('action');
      let $this = $(this);
      score.html(parseInt(total) + 1);

      $(this).transition('scaleOut', {
         duration: 500,
         complete: function () {
            $this.replaceWith('<i class="icon check"></i>');
            $.post(url + 'shop/action/', {
               action: 'like',
               id: id
            });
         }
      });
   });

   //Wishlist
   $shop.on('click', '.add-shop-wish', function () {
      let $btn = $(this);
      let id = $btn.data('id');
      let url = $('#shop').attr('data-url');

      switch ($btn.data('layout')) {
         case 'list':
            $btn.children().addClass('positive spin spinner circles');
            break;

         default:
            $btn.children().addClass('spin spinner circles');
            break;
      }

      $.post(url + 'shop/action/', {
         action: 'wishlist',
         id: id,
      }, function (json) {
         if (json.type === 'error') {
            console.log('Invalid ID');
         }
         setTimeout(function () {
            $btn.removeClass('add-shop-wish');
            $btn.children().removeClass('inverted spin spinner circles heart files');

            switch ($btn.data('layout')) {
               case 'list':
                  $btn.children().addClass('positive check');
                  break;

               default:
                  $btn.children().addClass('check');
                  break;
            }
         }, 500);

      }, 'json');
   });

   //remove wishlist
   $shop.on('click', '.removeWish', function () {
      let id = $(this).data('id');
      let url = $shop.data('url');
      let parent = $shop.find('tr#wishlist_' + id);
      $.post(url + 'shop/action/', {
         action: 'removeWish',
         id: id,
      }, function (json) {
         if (json.status === 'success') {
            parent.transition('scaleOut', {
               duration: 300,
               complete: function () {
                  parent.remove();
               }
            });
         }

      }, 'json');
   });

   //load gateway
   $shop.on('change', 'input[name=gateway]', function () {
      const $sch = $('#shCheckout');
      let id = $(this).val();
      let url = $shop.attr('data-url');
      let isValid = true;

      $('#sCheckout input').each(function () {
         if ($.trim($(this).val()).length === 0) {
            isValid = false;
            $(this).closest('.fields').transition('shake');
         }
      });
      if (isValid) {
         $.get(url + 'shop/action/', {
            action: 'gateway',
            id: id,
            address: $('#wojo_form').serialize()
         }, function (json) {
            $sch.html(json.message);
            $('html,body').animate({
               scrollTop: $sch.offset().top
            }, 1000);
         }, 'json');
      } else {
         $('html,body').animate({
            scrollTop: $('#shop').offset().top
         }, 500);
         $('input[name=gateway]:checked').prop('checked', false);
      }
   });
});