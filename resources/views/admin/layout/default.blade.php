<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
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
    <meta name="keywords"
          content="gafafit, commando, siclo, fit, gafa, booking, zuda, t3mplo, gestion, yoga, spinning, gym, fitness">
    <meta name="msapplication-TileColor" content="#FFFFFF">
    <meta name="msapplication-TileImage" content="{{ url('images/buq_favicon.png') }}">
    <meta name="theme-color" content="#EE6E73">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'buq') }}</title>
    @include('admin.layout.colors')
    <!-- FONTS-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inconsolata" type="text/css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/dynamic.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/scrollbar/perfect-scrollbar.min.css') }}">
    <link href="{{ asset('css/admin/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin/fa-all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin/saas-menu.css') }}" rel="stylesheet">

    @yield('css')
</head>
<body class="signin">
<div class="version-stop">
    <div class="container">
        <h2>Lo sentimos, servicio disponible únicamente para tabletas y equipos de escritorio</h2>
    </div>
</div>
@yield('appPre')
<div id="app">
    @yield('content')
</div>
@yield('appPost')
<!-- Scripts -->
<script type="text/javascript" src="{{ asset('js/all.js') }}"></script>

@yield('jsPreApp')
{{-- APP AND INIT --}}
<script src="{{ asset('js/forge.js') }}"></script>
<script src="{{ asset('js/signin.js') }}"></script>
<script src="{{ asset('js/init.js') }}"></script>
@yield('jsPostApp')
</body>
</html>
