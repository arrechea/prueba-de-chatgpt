<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="notLoaded">
<head>
    <link rel="icon" href="{{ url('images/buq_favicon.png') }}" sizes="32x32">
    <link rel="apple-touch-icon-precomposed" href="{{ url('images/buq_favicon.png') }}">
    <!-- META DATA-->
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="title" content="GAFAfit, solución integral para la gestión de booking.">
    <meta name="description"
          content="Gestiona todos tus servicios, staff, calendario, membresias y paquetes. Aplicación enfocada a la experiencia de usuario (UX). Recibe pagos con Visa, Mastercard, Amex y PayPal.">
    <meta name="keywords" content="admin, template, fitness, booking">
    <meta name="msapplication-TileColor" content="#FFFFFF">
        <meta name="msapplication-TileImage" content="{{ url('images/buq_favicon.png') }}">
    <meta name="theme-color" content="#2a56c6">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($company)?$company->name:config('app.name', 'buq') }}</title>

@include('admin.layout.colors')
<!-- FONTS-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inconsolata" type="text/css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/dynamic.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/scrollbar/perfect-scrollbar.min.css') }}">
    <link href="{{ mixGafaFit('css/admin/app.css') }}" rel="stylesheet">
    {{--  <link href="{{ asset('css/admin/fa-all.min.css') }}" rel="stylesheet">  --}}
    <link href="{{ mixGafaFit('css/admin/custom.css') }}" rel="stylesheet">

    @if(Auth::user()->isA('gafa-saas'))
        <link href="{{ mixGafaFit('css/admin/saas.css') }}" rel="stylesheet">
    @endif

    {{--Datatables--}}
    {{--  <link href="{{ asset('js/vendor/datatable/jquery.dataTables.min.css') }}" rel="stylesheet">  --}}
    {{--End Datatables--}}
    @yield('css')
    @yield('jsHeader')
    <link rel='stylesheet' href='{{asset('js/vendor/fullcalendar/fullcalendar.css')}}'/>
    <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/cupertino/jquery-ui.css">
    <!-- /pageload-overlay -->
    <script src="{{asset('js/loader/snap.svg-min.js')}}"></script>
    <script src="{{asset('js/loader/classie.js')}}"></script>
    <script src="{{asset('js/loader/svgLoader.js')}}"></script>

    @if(env('APP_ENV') === 'production')
    <!-- Hotjar Tracking Code for https://gafa.fit -->
        <script>
            (function (h, o, t, j, a, r) {
                h.hj = h.hj || function () {
                    (h.hj.q = h.hj.q || []).push(arguments)
                };
                h._hjSettings = {hjid: 1481805, hjsv: 6};
                a = o.getElementsByTagName('head')[0];
                r = o.createElement('script');
                r.async = 1;
                r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
                a.appendChild(r);
            })(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=');
        </script>
    @endif
    @if(env('APP_ENV') !== 'production')
        <script> (function () {
                var qs, js, q, s, d = document, gi = d.getElementById, ce = d.createElement,
                    gt = d.getElementsByTagName,
                    id = "typef_orm_share", b = "https://embed.typeform.com/";
                if (!gi.call(d, id)) {
                    js = ce.call(d, "script");
                    js.id = id;
                    js.src = b + "embed.js";
                    q = gt.call(d, "script")[0];
                    q.parentNode.insertBefore(js, q)
                }
            })() </script>
    @endif
</head>
<body onbeforeunload="return gafaFitUnload()" onload="return gafaFitLoad()">
{{--Loader--}}
<div id="loader" class="loaded" style="background-color: white;">
    <img src="{{asset('images/BUQ_.gif')}}" width="200" alt="buq">
</div>

<div class="version-stop">
    <div class="container">
        <h2>Lo sentimos, servicio disponible únicamente para tabletas y equipos de escritorio</h2>
    </div>
</div>

@include('admin.layout.master.master-location')

@yield('appPre')
<div id="app">
    <header id="headerApp">
        @include('admin.includes.header')
        @include('admin.includes.verticalnav')
        @include('admin.includes.horizontal')
        @include('admin.includes.notification')
    </header>
    <main>
        @include('admin.common.system-messages')
        @yield('content')
        @include('admin.includes.footer')
    </main>

    @if(env('APP_ENV') !== 'production')
        <a class="typeform-share button" href="https://gafamx.typeform.com/to/OWxPUZ" data-mode="popup"
           style="cursor: pointer;
    font-size: 14px;
    line-height: 50px;
    -moz-osx-font-smoothing: grayscale;
    position: fixed;
    bottom: 20px;
    left: 65px;
    z-index: 10000;
    scroll: unset;
    background: #000000;
    border-radius: 50%;
    width: 50px;"
           target="_blank">
            <svg style="position: relative;top: 5px;left: 14px;" width="22px" height="22px" viewBox="0 0 15 14" version="1.1"
                 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="exclamation-triangle" fill="#ffffff" fill-rule="nonzero">
                        <path d="M6.47778646,5.33085938 L6.64934896,8.24752604 C6.6590625,8.41270833 6.79585938,8.54166667 6.96130208,8.54166667 L8.03864583,8.54166667 C8.20410106,8.54166667 8.34087846,8.41269548 8.35059896,8.24752604 L8.52216146,5.33085938 C8.53270833,5.15135417 8.39,5 8.21020833,5 L6.78971354,5 C6.60994792,5 6.46723958,5.15135417 6.47778646,5.33085938 L6.47778646,5.33085938 Z M8.59375,10 C8.59375,10.6040625 8.1040625,11.09375 7.5,11.09375 C6.8959375,11.09375 6.40625,10.6040625 6.40625,10 C6.40625,9.3959375 6.8959375,8.90625 7.5,8.90625 C8.1040625,8.90625 8.59375,9.3959375 8.59375,10 Z M8.58273438,0.624609375 C8.10270833,-0.207447917 6.89817708,-0.208958333 6.41726562,0.624609375 L0.168671875,11.4586719 C-0.311067708,12.2902604 0.28953125,13.3333333 1.25140625,13.3333333 L13.7484375,13.3333333 C14.7084635,13.3333333 15.311849,12.291849 14.8311719,11.4586719 L8.58273438,0.624609375 Z M1.38518229,11.8490104 L7.36466146,1.48458333 C7.42479167,1.38036458 7.57520833,1.38036458 7.63533854,1.48458333 L13.6148177,11.8489844 C13.6749219,11.953151 13.5997396,12.0833073 13.4794792,12.0833073 L1.52052083,12.0833073 C1.40028646,12.0833073 1.32510417,11.9531771 1.38518229,11.8490104 L1.38518229,11.8490104 Z"
                              id="Shape"></path>
                    </g>
                </g>
            </svg>
        </a>
    @endif
</div>
@include('admin.layout.scriptsfooter')
@if(isset($location))
    <script src="{{asset('js/location/user.js')}}"></script>
    <script src="{{asset('js/location/assignGiftCard.js')}}"></script>
@endif

@yield('jsPostApp')
</body>
</html>
