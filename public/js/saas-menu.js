!function(a){var e={};function s(n){if(e[n])return e[n].exports;var t=e[n]={i:n,l:!1,exports:{}};return a[n].call(t.exports,t,t.exports,s),t.l=!0,t.exports}s.m=a,s.c=e,s.d=function(a,e,n){s.o(a,e)||Object.defineProperty(a,e,{configurable:!1,enumerable:!0,get:n})},s.n=function(a){var e=a&&a.__esModule?function(){return a.default}:function(){return a};return s.d(e,"a",e),e},s.o=function(a,e){return Object.prototype.hasOwnProperty.call(a,e)},s.p="",s(s.s=1)}({1:function(a,e,s){s("BGO1"),s("z/JI"),a.exports=s("N9uw")},BGO1:function(a,e){$(document).ready(function(){$(".saas-main-menu").click(function(){var a=$(this).find("a");return a&&a.attr("href")&&(window.location=a.attr("href")),!1}),$(".saas-main-menu").click(function(){if($(".saas-sidebar").removeClass("active"),$(".saas-main-menu").removeClass("toggle"),$(".transition-main-enter").removeClass("saas-content-collapse"),$("#headerApp").removeClass("saas-content-collapse"),$(".saas-sidebar[data-open="+$(this).data("open")+"]").toggleClass("active"),$(this).toggleClass("toggle"),$(".transition-main-enter").toggleClass("saas-content-collapse"),$("#headerApp").toggleClass("saas-content-collapse"),$('[data-open="location"]').hasClass("active")){var a=$('[data-open="location"]');1===a.find("> ul > li").length&&(a.find("> ul > li > ul").hasClass("active")||a.find("> ul > li > a").click())}$(".saas-nav").addClass("is-open")}),$("a.clickable").on("click",function(a){$(this).hasClass("panel-collapsed")?($(".saas-secondary-menu").removeClass("toggle"),$(".saas-secondary-menu").find(".collapsein").removeClass("active").slideUp(),$(".saas-secondary-menu").find("a").addClass("panel-collapsed"),$(this).parent().find(".collapsein").addClass("active").slideDown(),$(this).parent().addClass("toggle"),$(this).removeClass("panel-collapsed")):($(this).parent().find(".collapsein").removeClass("active").slideUp(),$(this).addClass("panel-collapsed"))})})},N9uw:function(a,e){},"z/JI":function(a,e){}});