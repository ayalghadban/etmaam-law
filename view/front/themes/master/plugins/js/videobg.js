(function ($) {
   $.fn.wVback = function (options) {
      let settings = $.extend({'video-opacity': 1, 'masked': false, 'mask-opacity': 1, 'youtube-mute-video': true, 'youtube-loop': true}, options);
      return this.each(function () {
         let targetobj = $(this), targettag = targetobj.prop('tagName');
         if (targettag === 'BODY') {
            let obj = $('.wVideo');
            let objtag = obj.prop('tagName');
            if (objtag === 'IFRAME') {
               let youtube_source = obj.attr('src') + '?autoplay=1&rel=0&controls=0&showinfo=0', video_url_parts = obj.attr('src').split('/'), video_id = video_url_parts[video_url_parts.length - 1];
               if (settings['youtube-mute-video'] === true) {
                  youtube_source += '&mute=1'
               }
               if (settings['youtube-loop'] === true) {
                  youtube_source += '&loop=1&playlist=' + video_id
               }
               obj.attr('src', youtube_source);
               obj.addClass('wVideo-active-body-back-youtube');
               let winh = $(window).height(), winw = $(window).width(), wre = winw / winh, hre = winh / winw;
               if (winw > winh) {
                  if (wre < 1.77) {
                     let expected_width = winh * (16 / 9);
                     obj.css('width', expected_width + 'px');
                     obj.css('height', '100%')
                  } else {
                     let expected_height = winw / (16 / 9);
                     obj.css('width', '100%');
                     obj.css('height', expected_height + 'px')
                  }
               } else {
                  if (hre < 0.56) {
                     let expected_height = winw / (16 / 9);
                     obj.css('width', '100%');
                     obj.css('height', expected_height + 'px')
                  } else {
                     let expected_width = winh * (16 / 9);
                     obj.css('width', expected_width + 'px');
                     obj.css('height', '100%')
                  }
               }
            } else {
               let poster = obj.attr('poster');
               obj.css('background-size', '100% 100% !important');
               obj.css('background-image', 'url(' + poster + ')');
               obj.addClass('wVideo-active-body-back');
               obj.css('opacity', settings['video-opacity'])
            }
            obj.removeClass('wVideo');
            if (settings.masked === true) {
               obj.after('<div class="vidmask-body-back">&nbsp;</div>');
               $('.vidmask-body-back').css('opacity', settings['mask-opacity'])
            }
            $(window).resize(function () {
               let winh = $(window).height(), winw = $(window).width(), vidh = obj.height(), vidw = obj.width(), wre = winw / winh, hre = winh / winw;
               if (objtag === 'IFRAME') {
                  if (winw > winh) {
                     if (wre < 1.77) {
                        let expected_width = winh * (16 / 9);
                        obj.css('width', expected_width + 'px');
                        obj.css('height', '100%')
                     } else {
                        let expected_height = winw / (16 / 9);
                        obj.css('width', '100%');
                        obj.css('height', expected_height + 'px')
                     }
                  } else {
                     if (hre < 0.56) {
                        let expected_height = winw / (16 / 9);
                        obj.css('width', '100%');
                        obj.css('height', expected_height + 'px')
                     } else {
                        let expected_width = winh * (16 / 9);
                        obj.css('width', expected_width + 'px');
                        obj.css('height', '100%')
                     }
                  }
               } else {
                  if (vidh < winh) {
                     obj.css('height', winh)
                  }
                  if (vidw < winw) {
                     obj.css('width', winw)
                  }
               }
            })
         } else {
            let obj = $(this).find('.wVideo');
            let objtag = obj.prop('tagName');
            if (objtag === 'IFRAME') {
               let youtube_source = obj.attr('src') + '?autoplay=1&rel=0&controls=0&showinfo=0', video_url_parts = obj.attr('src').split('/'), video_id = video_url_parts[video_url_parts.length - 1];
               if (settings['youtube-mute-video'] === true) {
                  youtube_source += '&mute=1'
               }
               if (settings['youtube-loop'] === true) {
                  youtube_source += '&loop=1&playlist=' + video_id
               }
               obj.attr('src', youtube_source);
               obj.addClass('wVideo-active-block-back-youtube');
               targetobj.css('position', 'relative');
               targetobj.css('overflow', 'hidden');
               let winh = targetobj.outerHeight(), winw = targetobj.width(), wre = winw / winh, hre = winh / winw;
               if (winw > winh) {
                  if (wre < 1.77) {
                     let expected_width = winh * (16 / 9);
                     obj.css('width', expected_width + 'px');
                     obj.css('height', '100%')
                  } else {
                     let expected_height = winw / (16 / 9);
                     obj.css('width', '100%');
                     obj.css('height', expected_height + 'px')
                  }
               } else {
                  if (hre < 0.56) {
                     let expected_height = winw / (16 / 9);
                     obj.css('width', '100%');
                     obj.css('height', expected_height + 'px')
                  } else {
                     let expected_width = winh * (16 / 9);
                     obj.css('width', expected_width + 'px');
                     obj.css('height', '100%')
                  }
               }
            } else {
               let poster = obj.attr('poster');
               targetobj.css('position', 'relative');
               targetobj.css('overflow', 'hidden');
               obj.css('background-image', 'url(' + poster + ')');
               obj.addClass('wVideo-active-block-back');
               obj.css('opacity', settings['video-opacity'])
            }
            obj.removeClass('wVideo');
            if (settings.masked === true) {
               targetobj.append('<div class="vidmask-block-back">&nbsp;</div>');
               targetobj.find('.vidmask-block-back').css('opacity', settings['mask-opacity'])
            }
            $(window).resize(function () {
               let winh = targetobj.outerHeight(), winw = targetobj.width(), vidh = obj.height(), vidw = obj.width(), wre = winw / winh, hre = winh / winw;
               if (objtag === 'IFRAME') {
                  if (winw > winh) {
                     if (wre < 1.77) {
                        let expected_width = winh * (16 / 9);
                        obj.css('width', expected_width + 'px');
                        obj.css('height', '100%')
                     } else {
                        let expected_height = winw / (16 / 9);
                        obj.css('width', '100%');
                        obj.css('height', expected_height + 'px')
                     }
                  } else {
                     if (hre < 0.56) {
                        let expected_height = winw / (16 / 9);
                        obj.css('width', '100%');
                        obj.css('height', expected_height + 'px')
                     } else {
                        let expected_width = winh * (16 / 9);
                        obj.css('width', expected_width + 'px');
                        obj.css('height', '100%')
                     }
                  }
               } else {
                  if (vidh < winh) {
                     obj.css('height', winh)
                  }
                  if (vidw < winw) {
                     obj.css('width', winw)
                  }
               }
            });
            $(window).scroll(function () {
               let top = $(window).scrollTop(), winheight = $(window).height(), target_element_position = targetobj.offset(), target_element_bottom = targetobj.outerHeight() + target_element_position.top, window_bottom = winheight + top;
               if ((target_element_bottom < top || target_element_position.top > window_bottom) && objtag === 'VIDEO') {
                  if (!obj[0].paused) {
                     try {
                        obj[0].pause()
                     } catch (e) {
                        console.error(e)
                     }
                  }
               } else {
                  if (obj[0].paused) {
                     try {
                        obj[0].play()
                     } catch (e) {
                        console.error(e)
                     }
                  }
               }
            })
         }
      })
   }
})(jQuery);