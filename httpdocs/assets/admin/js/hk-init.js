/*******************************************************************************
 * *************Init JS
 *
 * TABLE OF CONTENTS --------------------------- 1.Ready function 2.Load
 * function 3.Full height function 4.Mintos function 5.Chat App function
 * 6.Resize function *
 ******************************************************************************/

"use strict";
/** ***Ready function start**** */
$(document).ready(function() {
    mintos();
    /* Disabled */
    $(document).on("click", "a.disabled,a:disabled", function(e) {
        return false;
    });
});
/** ***Ready function end**** */

/** ***Load function start**** */
$(window).on("load", function() {
    $(".preloader-it").delay(200).fadeOut("fast");
});
/** ***Load function* end**** */

/* Variables */
var height, width, $wrapper = $(".hk-wrapper"), $nav = $(".hk-nav"), $vertnaltNav = $(".hk-wrapper.hk-vertical-nav,.hk-wrapper.hk-alt-nav"), $horizontalNav = $(".hk-wrapper.hk-horizontal-nav"), $navbar = $(".hk-navbar");

/** *** Mintos function start **** */
var mintos = function() {

    /* Feather Icon */
    var featherIcon = $('.feather-icon');
    if (featherIcon.length > 0) {
        feather.replace();
    }

    /* Tooltip */
    if ($('[data-toggle="tooltip"]').length > 0)
        $('[data-toggle="tooltip"]').tooltip();

    /* Popover */
    if ($('[data-toggle="popover"]').length > 0)
        $('[data-toggle="popover"]').popover()

        /* Navbar Collapse Animation */
    var navbarNavAnchor = '.hk-nav .navbar-nav  li a';
    $(document).on("click", navbarNavAnchor, function(e) {
        if ($(this).attr('aria-expanded') === "false")
            $(this).blur();
        $(this).parent().siblings().find('.collapse').collapse('hide');
        $(this).parent().find('.collapse').collapse('hide');
    });

    /* Navbar Toggle */
    $(document).on('click', '#navbar_toggle_btn', function(e) {
        $wrapper.toggleClass('hk-nav-toggle');
        $(window).trigger("resize");
        return false;
    });
    $(document).on('click', '#hk_nav_backdrop,#hk_nav_close', function(e) {
        $wrapper.removeClass('hk-nav-toggle');
        return false;
    });
    /* Slimscroll */
    $('.nicescroll-bar').slimscroll({
        height : '100%',
        color : '#d6d9da',
        disableFadeOut : true,
        borderRadius : 0,
        size : '6px',
        enableKeyNavigation : true,
        opacity : .8
    });

    /* Slimscroll Key Control */
    $(".slimScrollDiv").hover(function() {
        $(this).find('[class*="nicescroll-bar"]').focus();
    }, function() {
        $(this).find('[class*="nicescroll-bar"]').blur();
    });
};
/** *** Mintos function end **** */

/** *** Full height function start **** */
var setHeightWidth = function() {
    height = window.innerHeight;
    width = window.innerWidth;
    $('.full-height').css('height', (height));
    $('.hk-pg-wrapper').css('min-height', (height));
};
/** *** Full height function end **** */

/** *** Resize function start **** */
$(window).on("resize", function() {
    setHeightWidth();
});
$(window).trigger("resize");
/** *** Resize function end **** */

