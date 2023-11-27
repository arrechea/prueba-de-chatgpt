$(document).ready(function () {
    $(".saas-main-menu").click(function () {
        var elem = $(this).find("a");
        if (elem && elem.attr("href")) {
            window.location = elem.attr("href");
        }
        return false;
    });

    $('.saas-main-menu').click(function () {
        $(".saas-sidebar").removeClass('active');
        $(".saas-main-menu").removeClass('toggle');
        $('.transition-main-enter').removeClass('saas-content-collapse');
        $('#headerApp').removeClass('saas-content-collapse');
        $(".saas-sidebar[data-open=" + $(this).data('open') + "]").toggleClass('active');
        $(this).toggleClass('toggle');
        $('.transition-main-enter').toggleClass('saas-content-collapse');
        $('#headerApp').toggleClass('saas-content-collapse');

        if($('[data-open="location"]').hasClass('active')){
            var element = $('[data-open="location"]');
            if(element.find('> ul > li').length === 1 ){
                if(!element.find('> ul > li > ul').hasClass('active')){
                    element.find('> ul > li > a').click();
                }
            } else {

            };
        }

        $('.saas-nav').addClass('is-open');
    });

    $('a.clickable').on("click", function (e) {
        if ($(this).hasClass('panel-collapsed')) {
            $('.saas-secondary-menu').removeClass('toggle');
            $('.saas-secondary-menu').find('.collapsein').removeClass('active').slideUp();
            $('.saas-secondary-menu').find('a').addClass('panel-collapsed');
            $(this).parent().find('.collapsein').addClass('active').slideDown();
            $(this).parent().addClass('toggle');
            $(this).removeClass('panel-collapsed');
        } else {
            // collapse the panel
            $(this).parent().find('.collapsein').removeClass('active').slideUp();
            $(this).addClass('panel-collapsed');
        }
    });
});
