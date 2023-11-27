<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <link rel="icon" href="{{ url('images/favicon/favicon-32x32.png') }}" sizes="32x32">
        <link rel="apple-touch-icon-precomposed" href="{{ url('images/favicon/favicon-32x32.png') }}">
        <!-- META DATA-->
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="msapplication-tap-highlight" content="no">
        <meta name="title" content="GAFAfit, solución integral para la gestión de booking.">
        <meta name="description" content="Gestiona todos tus servicios, staff, calendario, membresias y paquetes. Aplicación enfocada a la experiencia de usuario (UX). Recibe pagos con Visa, Mastercard, Amex y PayPal.">
        <meta name="keywords" content="admin, template, fitness, booking">
        <meta name="msapplication-TileColor" content="#FFFFFF">
        <meta name="msapplication-TileImage" content="{{ url('images/favicon/favicon-32x32.png') }}">
        <meta name="theme-color" content="#EE6E73">



        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'GAFAfit') }}</title>
        <!-- FONTS-->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Inconsolata" type="text/css">
        <link rel="stylesheet" href="http://fonts.googleapis.com/icon?family=Material+Icons">
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/dynamic.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/scrollbar/perfect-scrollbar.min.css') }}">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        @yield('css')
    </head>
    <body>
        @yield('appPre')
        <div id="app">
            @yield('content')
        </div>
        @yield('appPost')
        <!-- Scripts -->

        {{-- JQUERY AND MATERIAL CDNs --}}
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>

        {{-- PERFECT SCROLLBAR --}}
        <script src="{{ asset('plugins/scrollbar/perfect-scrollbar.min.js') }}"></script>

        @yield('jsPreApp')
        {{-- APP AND INIT --}}
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/init.js') }}"></script>
        @yield('jsPostApp')
    </body>
</html>
