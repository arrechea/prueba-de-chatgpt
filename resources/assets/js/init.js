$(function () {

    $('.carousel.carousel-slider').carousel({full_width: true});
    $('.carousel').carousel();
    $('.slider').slider({full_width: true});
    $('.parallax').parallax();
    $('.forge-modal').modal();
    $('.scrollspy').scrollSpy();
    $('.button-collapse').sideNav({'edge': 'left'});
    $('.datepicker').pickadate({selectYears: 100});
    $('select.forge-select').not('.disabled').material_select();
    $('#menuAccountClick').on('click', function () {
        $('.navbar__menu-sub').toggleClass('show');
    });
    if ($('.sidebar__section').length == 4) {
        $('.sidebar__section').first().addClass('is-here');
        $('.sidebar__section').each(function () {
            var section = $(this);
            if (section.hasClass('is-here') == false) {
                section.find('.sidebar__nav .nav__submenu-trigger').attr('data-open', false);
                section.find('.sidebar__nav .nav__submenu-trigger a').removeClass('is-here');
            }
        });
    } else {
        $('.sidebar__section').first().addClass('is-here');
    }
    $('#close-sidebar').on('click', function () {
        $('.sidebar ,header ,.transition-main-enter').addClass('hide-side');
        $('#open-sidebar').addClass('show');
    });
    $('#open-sidebar').on('click', function () {
        $('.sidebar ,header ,.transition-main-enter').removeClass('hide-side');
        $(this).removeClass('show');
    });
    $(window).on('load', function () {
        if ($(this).width() <= 992) {
            $('.sidebar ,header ,.transition-main-enter').removeClass('hide-side').addClass('hide-side');
            $('#open-sidebar').removeClass('show').addClass('show');
        }
    });
    // $(window).on('resize load', function(){
    //     var windowWidth = $(this).width();
    //     // TODO Remover antes de lanzar a la rama bug / Front End
    //     if(windowWidth < 1024){
    //         $('body').css({'overflow-y' : 'hidden'});
    //         $('.version-stop').css({'display' : 'flex'});
    //         $('#app').hide();
    //     } else {
    //         $('body').css({'overflow-y' : 'initial'});
    //         $('.version-stop').hide();
    //         $('#app').show();
    //     }
    // });
    $('.sidebar__section').each(function () {
        var thisSection = $(this);
        thisSection.find('.sidebar__title:not(".no-interaction")').on('click', function () {
            if (thisSection.attr('data-open') == 'false') {
                $('.sidebar__section').attr('data-open', false);
                thisSection.attr('data-open', true);
                $('[data-open="false"]').each(function () {
                    $(this).find('.sidebar__nav:not(.first)').removeClass('show');
                });
                thisSection.find('.sidebar__nav:not(.first)').addClass('show');
            } else {
                thisSection.attr('data-open', false);
                thisSection.find('.sidebar__nav:not(.first)').removeClass('show');
            }
        });
    });
    $('.sidebar__section.is-here .nav__item[data-open="true"]').each(function () {
        var loadMenu = $(this);
        loadMenu.addClass('show active');
        loadMenu.find('.nav__submenu-body').addClass('show');
    });


    $('.nav__submenu-trigger').each(function () {
        var submenuTrigger = $(this);

        if (submenuTrigger.attr('data-open') === 'false') {
            submenuTrigger.find('.nav__submenu-body').css({'height': 0,});
        }

        submenuTrigger.on('click', function () {
            if (submenuTrigger.attr('data-open') == 'false') {
                $('.nav__submenu-trigger').attr('data-open', false);
                submenuTrigger.attr('data-open', true);
                $('[data-open="false"]').each(function () {
                    $(this).removeClass('show active');
                    $(this).find('.nav__submenu-body').removeClass('show').animate({
                        'height': 0,
                    });
                });
                submenuTrigger.find('.nav__submenu-body').animate({
                    'height': submenuTrigger.find('.nav__submenu-body ul').height(),
                });
                submenuTrigger.addClass('show active');
                submenuTrigger.find('.nav__submenu-body').addClass('show');
            } else {
                submenuTrigger.attr('data-open', false);
                submenuTrigger.removeClass('show active');
                submenuTrigger.find('.nav__submenu-body').removeClass('show').animate({
                    'height': 0,
                });

            }
        });
    });

    $('input.autocomplete').autocomplete({
        data: {"Apple": null, "Microsoft": null, "Google": 'http://placehold.it/250x250'}
    });

    $('.chips').material_chip();

    $('.chips-initial').material_chip({
        readOnly: true,
        data: [{
            tag: 'Apple',
        }, {
            tag: 'Microsoft',
        }, {
            tag: 'Google',
        }]
    });

    $('.chips-placeholder').material_chip({
        placeholder: 'Enter a tag',
        secondaryPlaceholder: '+Tag',
    });

    // Swipeable Tabs Demo Init
    if ($('#tabs-swipe-demo').length) {
        $('#tabs-swipe-demo').tabs({'swipeable': true});
    }

    document.addEventListener("DOMSubtreeModified", function (e) {
        if ($('#sidenav-overlay').length === 0) {
            $('.mob-menu').removeClass('leftArrow').addClass('menu');
        } else {
            $('.mob-menu').removeClass('menu').addClass('leftArrow');
        }
    }, false);

    $("#rsb-tasklist div.tab-notelist").sortable({
        placeholder: "ui-state-highlight"
    });

    $("#rsb-tasklist div.tab-notelist").disableSelection();

    //  MATERIALIZED INIT
    $('select.mat_select').material_select();
    $('.theme-tooltipped').tooltip({delay: 50});

    // PuSH Pin
    var tocWrapper = $('.toc-wrapper');
    var mainContainer = $('.main-container');
    if (tocWrapper.length && mainContainer.length) {
        tocWrapper.pushpin({
            top: $('.main-container').offset().top
        });
    }
    // DASH CARD HANDLERS
    $('.card-dash .card-reveal .card-title').on("click", () => $(this).closest('.card').removeAttr("style"));

    $('.card-dash .activator').on("click", () => $(this).closest('.card').removeAttr("style"));

    // WINDOW ON RESIZE
    $(window).on('resize', () => {
        $('ul.tabs').tabs();
    });
});
$(window).on('load', () => {
    // Load Tab Elements
    setTimeout(() => {
        $('ul.tabs').tabs();
    }, 300);

    // Application PreLoader & Loader
    // ############# HIDE PRELOADER AND ITS OVERLAY #################
    setTimeout(() => {
        $('.preloader-center').addClass('loaded');
        $("html").trigger("loaded");
    }, 1000);
    setTimeout(() => {
        $("#preloader").addClass('loaded');
        $("#loader-wrapper").addClass('loaded');
        $("html").trigger("loaded");
    }, 1010);

    // ############# HIDE PRELOADER AND ITS OVERLAY ENDs #################

    /**
     * Custom scrollbars
     */
    var navdefault = document.getElementById('nav-default');
    if (typeof (navdefault) != 'undefined' && navdefault != null) {
        Ps.initialize(navdefault);
    }

    var psNotificationList = document.getElementById('psNotificationList');
    if (typeof (psNotificationList) != 'undefined' && psNotificationList != null) {
        Ps.initialize(psNotificationList);
    }

    var psTabShortcut = document.getElementById('psTabShortcut');
    if (typeof (psTabShortcut) != 'undefined' && psTabShortcut != null) {
        Ps.initialize(psTabShortcut);
    }
    var psTabNotelist = document.getElementById('psTabNotelist');
    if (typeof (psTabNotelist) != 'undefined' && psTabNotelist != null) {
        Ps.initialize(psTabNotelist);
    }
    var psTopNavMmsgs = document.getElementById('psTopNavMmsgs');
    var psTopNavMmsgsWeb = document.getElementById('psTopNavMmsgsWeb');
    if (typeof (psTopNavMmsgs) != 'undefined' && psTopNavMmsgs != null) {
        Ps.initialize(psTopNavMmsgs);
    }
    if (typeof (psTopNavMmsgsWeb) != 'undefined' && psTopNavMmsgsWeb != null) {
        Ps.initialize(psTopNavMmsgsWeb);
    }
    $(document).on("click", "a.close-flash", () => $('div.alert-flash').fadeOut(300));
});
