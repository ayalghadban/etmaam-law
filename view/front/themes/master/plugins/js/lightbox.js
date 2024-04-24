/*
 * VenoBox - jQuery Plugin
 * version: 1.9.2
 * @requires jQuery >= 1.7.0
 *
 * Examples at http://veno.es/venobox/
 * License: MIT License
 * License URI: https://github.com/nicolafranchini/VenoBox/blob/master/LICENSE
 * Copyright 2013-2021 Nicola Franchini - @nicolafranchini
 *
 */

/* global jQuery */
(function($) {
    "use strict";
    var autoplay, blocknum, blocktitle, core, container, content, dest,
        framewidth, frameheight, gallItems, loop, items, keyNavigationDisabled, margin, counter,
        overlay, title, thisgall, thenext, theprev, nextok, prevok, preloader, $preloader, navigation,
        obj, gallIndex, startouch, wlheader, images, startY, startX, endY, endX, diff, diffX, diffY, threshold;

    $.fn.extend({
        //plugin name - venobox
        wLightbox: function(options) {
            var plugin = this;
            // default options
            var defaults = {
                autoplay: false, // same as data-autoplay - thanks @codibit
                framewidth: '',
                frameheight: '',
                gallItems: false,
                loop: false,
                htmlClose: '<i class="icon x"></i>',
                htmlNext: '<i class="icon chevron right alt"></i>',
                htmlPrev: '<i class="icon chevron left alt"></i>',
                counter: true,
                overlayClose: true, // disable overlay click-close - thanx @martybalandis
                caption: 'data-title', // specific attribute to get a title (e.g. [data-title]) - thanx @mendezcode
                onBeforeOpen: function() {
                    return true;
                },
                onAfterOpen: function() {},
                onBeforeClose: function() {
                    return true;
                },
                onAfterClose: function() {},
                onAfterResize: function() {},
                onAfterNavigation: function() {},
                onContentLoaded: function() {},// is called after new content loaded
                onInit: function() {}
            };

            var option = $.extend(defaults, options);

            // callback plugin initialization
            option.onInit(plugin);

            return this.each(function() {

                obj = $(this);

                // Prevent double initialization - thanx @matthistuff
                if (obj.data('wlightbox')) {
                    return true;
                }

                // method to be used outside the plugin
                plugin.close = function() {
                    closeLightbox();
                };
				
                obj.addClass('item');
                obj.data('framewidth', option.framewidth);
                obj.data('frameheight', option.frameheight);
                obj.data('counter', option.counter);
                obj.data('gallItems', option.gallItems);
                obj.data('loop', option.loop);
                obj.data('caption', option.caption);
                obj.data('wlightbox', true);

                obj.on('click', function(e) {
                    e.preventDefault();
                    obj = $(this);

                    // callback plugin initialization
                    var onBeforeOpen = option.onBeforeOpen(obj);

                    if (onBeforeOpen === false) {
                        return false;
                    }

                    // Remove focus from link to avoid multiple calls with enter key
                    obj.blur();

                    // methods to be used outside the plugin
                    plugin.VBnext = function() {
                        navigateGall(thenext);
                    };
                    plugin.VBprev = function() {
                        navigateGall(theprev);
                    };

                    framewidth = obj.data('framewidth');
                    frameheight = obj.data('frameheight');
                    // set data-autoplay="true" for vimeo and youtube videos - thanx @zehfernandes
                    autoplay = obj.data('autoplay') || option.autoplay;
                    nextok = false;
                    prevok = false;
                    keyNavigationDisabled = false;

                    // set a different url to be loaded using data-href="" - thanx @pixeline
                    dest = obj.data('href') || obj.attr('href');
                    title = obj.attr(obj.data('caption')) || '';

                    preloader = '<div class="preloader"></div>';

                    navigation = '<a class="next">' + option.htmlNext + '</a><a class="prev">' + option.htmlPrev + '</a>';
                    wlheader = '<div class="header"><div class="counter">0/0</div><div class="title"></div><div class="close">' + option.htmlClose + '</div></div>';

                    core = '<div class="wojo lightbox overlay">' +
                        preloader + '<div class="container"><div class="content"></div></div>' + wlheader + navigation + '</div>';

                    $('body').append(core).addClass('wojo lightbox open');

                    overlay = $('.wojo.lightbox.overlay');
                    container = $('.wojo.lightbox .container');
                    content = $('.wojo.lightbox .container .content');
                    blocknum = $('.wojo.lightbox .header .counter');
                    blocktitle = $('.wojo.lightbox .header .title');
                    $preloader = $('.wojo.lightbox .preloader');

                    $preloader.show();
					
                    content.html('');
                    content.css('opacity', '0');
                    overlay.css('opacity', '0');

                    checknav();

                    // fade in overlay
                    overlay.animate({
                        opacity: 1
                    }, 250, function() {

                        if (obj.data('type') == 'iframe') {
                            loadIframe();
                        } else if (obj.data('type') == 'inline') {
                            loadInline();
                        } else if (obj.data('type') == 'ajax') {
                            loadAjax();
                        } else if (obj.data('type') == 'video') {
                            loadVid(autoplay);
                        } else {
                            content.html('<img src="' + dest + '">');
                            preloadFirst();
                        }
                        option.onAfterOpen(obj, gallIndex, thenext, theprev);
                    });

                    /* -------- KEYBOARD ACTIONS -------- */
                    $('body').keydown(keyboardHandler);

                    /* -------- PREVGALL -------- */
                    $('.wojo.lightbox .prev').on('click', function() {
                        navigateGall(theprev);
                    });
                    /* -------- NEXTGALL -------- */
                    $('.wojo.lightbox .next').on('click', function() {
                        navigateGall(thenext);
                    });

                    return false;

                }); // click

                /* -------- CHECK NEXT / PREV -------- */
                function checknav() {

                    thisgall = obj.data('gallery');
                    counter = obj.data('counter');
                    gallItems = obj.data('gallItems');
                    loop = obj.data('loop');

                    if (gallItems) {
                        items = gallItems;
                    } else {
                        items = $('.item[data-gallery="' + thisgall + '"]');
                    }

                    if (items.length < 2) {
                        loop = false;
                        counter = false;
                    }

                    thenext = items.eq(items.index(obj) + 1);
                    theprev = items.eq(items.index(obj) - 1);

                    if (!thenext.length && loop === true) {
                        thenext = items.eq(0);
                    }

                    // update gall counter
                    if (items.length >= 1) {
                        gallIndex = items.index(obj) + 1;
                        blocknum.html(gallIndex + ' / ' + items.length);
                    } else {
                        gallIndex = 1;
                    }
                    if (counter === true) {
                        blocknum.show();
                    } else {
                        blocknum.hide();
                    }

                    // update title
                    if (title !== '') {
                        blocktitle.show();
                    } else {
                        blocktitle.hide();
                    }

                    // update navigation arrows
                    if (!thenext.length && loop !== true) {
                        $('.wojo.lightbox .next').css('display', 'none');
                        nextok = false;
                    } else {
                        $('.wojo.lightbox .next').css('display', 'block');
                        nextok = true;
                    }

                    if (items.index(obj) > 0 || loop === true) {
                        $('.wojo.lightbox .prev').css('display', 'block');
                        prevok = true;
                    } else {
                        $('.wojo.lightbox .prev').css('display', 'none');
                        prevok = false;
                    }
                    // activate swipe
                    if (prevok === true || nextok === true) {
                        content.on(TouchMouseEvent.DOWN, onDownEvent);
                        content.on(TouchMouseEvent.MOVE, onMoveEvent);
                        content.on(TouchMouseEvent.UP, onUpEvent);
                    }
                }

                /* -------- gallery navigation -------- */
                function navigateGall(destination) {

                    if (destination.length < 1) {
                        return false;
                    }
                    if (keyNavigationDisabled) {
                        return false;
                    }
                    keyNavigationDisabled = true;

                    framewidth = destination.data('framewidth');
                    frameheight = destination.data('frameheight');
                    dest = destination.data('href') || destination.attr('href');

                    autoplay = destination.data('autoplay');

                    title = (destination.data('caption') && destination.attr(destination.data('caption'))) || '';

                    // swipe out item
                    if (destination === theprev) {
                        content.addClass('done').addClass('swipe-right');
                    }
                    if (destination === thenext) {
                        content.addClass('done').addClass('swipe-left');
                    }

                    $preloader.show();

                    content.animate({
                        opacity: 0,
                    }, 500, function() {
                        content.removeClass('done').removeClass('swipe-left').removeClass('swipe-right');

                        if (destination.data('type') == 'iframe') {
                            loadIframe();
                        } else if (destination.data('type') == 'inline') {
                            loadInline();
                        } else if (destination.data('type') == 'ajax') {
                            loadAjax();
                        } else if (destination.data('type') == 'video') {
                            loadVid(autoplay);
                        } else {
                            content.html('<img src="' + dest + '">');
                            preloadFirst();
                        }
                        obj = destination;
                        checknav();
                        keyNavigationDisabled = false;
                        option.onAfterNavigation(obj, gallIndex, thenext, theprev);
                    });
                }

                /* -------- KEYBOARD HANDLER -------- */
                function keyboardHandler(e) {
                    if (e.keyCode === 27) { // esc
                        closeLightbox();
                    }

                    if (e.keyCode == 37 && prevok === true) { // left
                        navigateGall(theprev);
                    }

                    if (e.keyCode == 39 && nextok === true) { // right
                        navigateGall(thenext);
                    }
                }

                /* -------- CLOSE VBOX -------- */
                function closeLightbox() {

                    var onBeforeClose = option.onBeforeClose(obj, gallIndex, thenext, theprev);

                    if (onBeforeClose === false) {
                        return false;
                    }

                    $('body').off('keydown', keyboardHandler).removeClass('wojo lightbox open');

                    obj.focus();

                    overlay.animate({
                        opacity: 0
                    }, 500, function() {
                        overlay.remove();
                        keyNavigationDisabled = false;
                        option.onAfterClose();
                    });
                }

                /* -------- CLOSE CLICK -------- */
                var closeclickclass = '.wojo.lightbox.overlay';
                if (!option.overlayClose) {
                    closeclickclass = '.wojo.lightbox .close'; // close only on X
                }

                $('body').on('click touchstart', closeclickclass, function(e) {
                    if ($(e.target).is('.wojo.lightbox.overlay') ||
                        $(e.target).is('.wojo.lightbox .content') ||
                        $(e.target).is('.wojo.lightbox .close') ||
						$(e.target).is('.wojo.lightbox .close .icon') ||
                        $(e.target).is('.wojo.lightbox .preloader') ||
                        $(e.target).is('.wojo.lightbox .container')
                    ) {
                        closeLightbox();
                    }
                });

                startX = 0;
                endX = 0;

                diff = 0;
                threshold = 50;
                startouch = false;

                function onDownEvent(event) {
                    content.addClass('done');
                    startY = endY = event.pageY;
                    startX = endX = event.pageX;
                    startouch = true;
                }

                function onMoveEvent(event) {
                    if (startouch === true) {
                        endX = event.pageX;
                        endY = event.pageY;

                        diffX = endX - startX;
                        diffY = endY - startY;

                        var absdiffX = Math.abs(diffX);
                        var absdiffY = Math.abs(diffY);

                        if ((absdiffX > absdiffY) && (absdiffX <= 100)) {
                            event.preventDefault();
                        }
                    }
                }

                function onUpEvent() {
                    if (startouch === true) {
                        startouch = false;
                        var subject = obj;
                        var change = false;
                        diff = endX - startX;

                        if (diff < 0 && nextok === true) {
                            subject = thenext;
                            change = true;
                        }
                        if (diff > 0 && prevok === true) {
                            subject = theprev;
                            change = true;
                        }

                        if (Math.abs(diff) >= threshold && change === true) {
                            navigateGall(subject);							
                        }
                    }
                }

                /* == GLOBAL DECLERATIONS == */
                var TouchMouseEvent = {
                    DOWN: "touchmousedown",
                    UP: "touchmouseup",
                    MOVE: "touchmousemove"
                };

                /* == EVENT LISTENERS == */
                var onMouseEvent = function(event) {
                    var type;
                    switch (event.type) {
                        case "mousedown":
                            type = TouchMouseEvent.DOWN;
                            break;
                        case "mouseup":
                            type = TouchMouseEvent.UP;
                            break;
                        case "mouseout":
                            type = TouchMouseEvent.UP;
                            break;
                        case "mousemove":
                            type = TouchMouseEvent.MOVE;
                            break;
                        default:
                            return;
                    }
                    var touchMouseEvent = normalizeEvent(type, event, event.pageX, event.pageY);
                    $(event.target).trigger(touchMouseEvent);
                };

                var onTouchEvent = function(event) {
                    var type;
                    switch (event.type) {
                        case "touchstart":
                            type = TouchMouseEvent.DOWN;
                            break;
                        case "touchend":
                            type = TouchMouseEvent.UP;
                            break;
                        case "touchmove":
                            type = TouchMouseEvent.MOVE;
                            break;
                        default:
                            return;
                    }

                    var touch = event.originalEvent.touches[0];
                    var touchMouseEvent;

                    if (type == TouchMouseEvent.UP) {
                        touchMouseEvent = normalizeEvent(type, event, null, null);
                    } else {
                        touchMouseEvent = normalizeEvent(type, event, touch.pageX, touch.pageY);
                    }
                    $(event.target).trigger(touchMouseEvent);
                };

                /* == NORMALIZE == */
                var normalizeEvent = function(type, original, x, y) {
                    return $.Event(type, {
                        pageX: x,
                        pageY: y,
                        originalEvent: original
                    });
                };

                /* == LISTEN TO ORIGINAL EVENT == */
                if ("ontouchstart" in window) {
                    $(document).on("touchstart", onTouchEvent);
                    $(document).on("touchmove", onTouchEvent);
                    $(document).on("touchend", onTouchEvent);
                } else {
                    $(document).on("mousedown", onMouseEvent);
                    $(document).on("mouseup", onMouseEvent);
                    $(document).on("mouseout", onMouseEvent);
                    $(document).on("mousemove", onMouseEvent);
                }

                /* -------- LOAD AJAX -------- */
                function loadAjax() {
                    $.ajax({
                        url: dest,
                        cache: false
                    }).done(function(msg) {
                        content.html('<div class="inline">' + msg + '</div>');
                        preloadFirst();
                    }).fail(function() {
                        content.html('<div class="inline"><p>Error retrieving contents, please retry</div>');
                        updateoverlay();
                    });
                }

                /* -------- LOAD IFRAME -------- */
                function loadIframe() {
                    content.html('<iframe src="' + dest + '"></iframe>');
                    updateoverlay();
                }

                /* -------- LOAD VIDEOs -------- */
                function loadVid(autoplay) {

                    var player;
                    var videoObj = parseVideo(dest);

                    // set rel=0 to hide related videos at the end of YT + optional autoplay
                    var stringAutoplay = autoplay ? "?rel=0&autoplay=1" : "?rel=0";
                    var queryvars = stringAutoplay + getUrlParameter(dest);

                    if (videoObj.type == 'vimeo') {
                        player = 'https://player.vimeo.com/video/';
                    } else if (videoObj.type == 'youtube') {
                        player = 'https://www.youtube.com/embed/';
                    }
                    content.html('<iframe class="is_video" webkitallowfullscreen mozallowfullscreen allowfullscreen allow="autoplay" frameborder="0" src="' + player + videoObj.id + queryvars + '"></iframe>');
                    updateoverlay();
                }

                /**
                 * Parse Youtube or Vimeo videos and get host & ID
                 */
                function parseVideo(url) {
                    url.match(/(http:|https:|)\/\/(player.|www.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com))\/(video\/|embed\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/);
                    var type;
                    if (RegExp.$3.indexOf('youtu') > -1) {
                        type = 'youtube';
                    } else if (RegExp.$3.indexOf('vimeo') > -1) {
                        type = 'vimeo';
                    }
                    return {
                        type: type,
                        id: RegExp.$6
                    };
                }

                /**
                 * get additional video url parameters
                 */
                function getUrlParameter(name) {
                    var result = '';
                    var sPageURL = decodeURIComponent(name);
                    var firstsplit = sPageURL.split('?');

                    if (firstsplit[1] !== undefined) {
                        var sURLVariables = firstsplit[1].split('&');
                        var sParameterName;
                        var i;
                        for (i = 0; i < sURLVariables.length; i++) {
                            sParameterName = sURLVariables[i].split('=');
                            result = result + '&' + sParameterName[0] + '=' + sParameterName[1];
                        }
                    }
                    return encodeURI(result);
                }

                /* -------- LOAD INLINE -------- */
                function loadInline() {
                    content.html('<div class="inline">' + $(dest).html() + '</div>');
                    updateoverlay();
                }

                /* -------- PRELOAD IMAGE -------- */
                function preloadFirst() {
                    images = content.find('img');

                    if (images.length) {
                        images.each(function() {
                            $(this).one('load', function() {
                                updateoverlay();
                            });
                        });
                    } else {
                        updateoverlay();
                    }
                }

                /* -------- FADE-IN THE NEW CONTENT -------- */
                function updateoverlay() {

                    blocktitle.html(title);
                    content.find(">:first-child").addClass('target').css({
                        'width': framewidth,
                        'height': frameheight                    });

                    $('.wojo.lightbox img.target').on('dragstart', function(event) {
                        event.preventDefault();
                    });

                    // reset content scroll
                    container.scrollTop(0);

                    updateOL();

                    content.animate({
                        'opacity': '1'
                    }, 'slow', function() {
                        $preloader.hide();
                    });

                    option.onContentLoaded(obj, gallIndex, thenext, theprev);
                }

                /* -------- CENTER FRAME -------- */
                function updateOL() {
/*
                    var sonH = content.outerHeight();
                    var finH = $(window).height();

                    if (sonH + 60 < finH) {
                        margin = (finH - sonH) / 2;
                    } else {
                        margin = '2rem';
                    }
                    //content.css('margin-top', margin);
                    //content.css('margin-bottom', margin);*/
                    option.onAfterResize();
                }

                $(window).resize(function() {
                    if ($('.wojo.lightbox.content').length) {
                        setTimeout(updateOL(), 800);
                    }
                });
            }); 
        } 
    }); 
})(jQuery);