!function(e){var t={};function a(s){if(t[s])return t[s].exports;var i=t[s]={i:s,l:!1,exports:{}};return e[s].call(i.exports,i,i.exports,a),i.l=!0,i.exports}a.m=e,a.c=t,a.d=function(e,t,s){a.o(e,t)||Object.defineProperty(e,t,{configurable:!1,enumerable:!0,get:s})},a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,"a",t),t},a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},a.p="",a(a.s=353)}({353:function(e,t,a){e.exports=a(354)},354:function(e,t){$(function(){var e=this;$(".carousel.carousel-slider").carousel({full_width:!0}),$(".carousel").carousel(),$(".slider").slider({full_width:!0}),$(".parallax").parallax(),$(".forge-modal").modal(),$(".scrollspy").scrollSpy(),$(".button-collapse").sideNav({edge:"left"}),$(".datepicker").pickadate({selectYears:100}),$("select.forge-select").not(".disabled").material_select(),$("#menuAccountClick").on("click",function(){$(".navbar__menu-sub").toggleClass("show")}),4==$(".sidebar__section").length?($(".sidebar__section").first().addClass("is-here"),$(".sidebar__section").each(function(){var e=$(this);0==e.hasClass("is-here")&&(e.find(".sidebar__nav .nav__submenu-trigger").attr("data-open",!1),e.find(".sidebar__nav .nav__submenu-trigger a").removeClass("is-here"))})):$(".sidebar__section").first().addClass("is-here"),$("#close-sidebar").on("click",function(){$(".sidebar ,header ,.transition-main-enter").addClass("hide-side"),$("#open-sidebar").addClass("show")}),$("#open-sidebar").on("click",function(){$(".sidebar ,header ,.transition-main-enter").removeClass("hide-side"),$(this).removeClass("show")}),$(window).on("resize load",function(){$(this).width()<1024?($("body").css({"overflow-y":"hidden"}),$(".version-stop").css({display:"flex"}),$("#app").hide()):($("body").css({"overflow-y":"initial"}),$(".version-stop").hide(),$("#app").show())}),$(".sidebar__section").each(function(){var e=$(this);e.find('.sidebar__title:not(".no-interaction")').on("click",function(){"false"==e.attr("data-open")?($(".sidebar__section").attr("data-open",!1),e.attr("data-open",!0),$('[data-open="false"]').each(function(){$(this).find(".sidebar__nav:not(.first)").removeClass("show")}),e.find(".sidebar__nav:not(.first)").addClass("show")):(e.attr("data-open",!1),e.find(".sidebar__nav:not(.first)").removeClass("show"))})}),$('.sidebar__section.is-here .nav__item[data-open="true"]').each(function(){var e=$(this);e.addClass("show active"),e.find(".nav__submenu-body").addClass("show")}),$(".nav__submenu-trigger").each(function(){var e=$(this);"false"===e.attr("data-open")&&e.find(".nav__submenu-body").css({height:0}),e.on("click",function(){"false"==e.attr("data-open")?($(".nav__submenu-trigger").attr("data-open",!1),e.attr("data-open",!0),$('[data-open="false"]').each(function(){$(this).removeClass("show active"),$(this).find(".nav__submenu-body").removeClass("show").animate({height:0})}),e.find(".nav__submenu-body").animate({height:e.find(".nav__submenu-body ul").height()}),e.addClass("show active"),e.find(".nav__submenu-body").addClass("show")):(e.attr("data-open",!1),e.removeClass("show active"),e.find(".nav__submenu-body").removeClass("show").animate({height:0}))})}),$("input.autocomplete").autocomplete({data:{Apple:null,Microsoft:null,Google:"http://placehold.it/250x250"}}),$(".chips").material_chip(),$(".chips-initial").material_chip({readOnly:!0,data:[{tag:"Apple"},{tag:"Microsoft"},{tag:"Google"}]}),$(".chips-placeholder").material_chip({placeholder:"Enter a tag",secondaryPlaceholder:"+Tag"}),$("#tabs-swipe-demo").length&&$("#tabs-swipe-demo").tabs({swipeable:!0}),document.addEventListener("DOMSubtreeModified",function(e){0===$("#sidenav-overlay").length?$(".mob-menu").removeClass("leftArrow").addClass("menu"):$(".mob-menu").removeClass("menu").addClass("leftArrow")},!1),$("#rsb-tasklist div.tab-notelist").sortable({placeholder:"ui-state-highlight"}),$("#rsb-tasklist div.tab-notelist").disableSelection(),$("select.mat_select").material_select(),$(".theme-tooltipped").tooltip({delay:50});var t=$(".toc-wrapper"),a=$(".main-container");t.length&&a.length&&t.pushpin({top:$(".main-container").offset().top}),$(".card-dash .card-reveal .card-title").on("click",function(){return $(e).closest(".card").removeAttr("style")}),$(".card-dash .activator").on("click",function(){return $(e).closest(".card").removeAttr("style")}),$(window).on("resize",function(){$("ul.tabs").tabs()})}),$(window).on("load",function(){setTimeout(function(){$("ul.tabs").tabs()},300),setTimeout(function(){$(".preloader-center").addClass("loaded")},1e3),setTimeout(function(){$("#preloader").addClass("loaded"),$("#loader-wrapper").addClass("loaded")},1010);var e=document.getElementById("nav-default");void 0!==e&&null!=e&&Ps.initialize(e);var t=document.getElementById("psNotificationList");void 0!==t&&null!=t&&Ps.initialize(t);var a=document.getElementById("psTabShortcut");void 0!==a&&null!=a&&Ps.initialize(a);var s=document.getElementById("psTabNotelist");void 0!==s&&null!=s&&Ps.initialize(s);var i=document.getElementById("psTopNavMmsgs"),n=document.getElementById("psTopNavMmsgsWeb");void 0!==i&&null!=i&&Ps.initialize(i),void 0!==n&&null!=n&&Ps.initialize(n),$(document).on("click","a.close-flash",function(){return $("div.alert-flash").fadeOut(300)})})}});