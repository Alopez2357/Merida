<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-lt-installed="true">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name', 'Merida') }}">
    <meta name="application-name" content="{{ config('app.name', 'Merida') }}">
    <script type="application/ld+json">{"@context":"https:\/\/schema.org","@type":"WebPage","headline":"{{ config('app.name', 'Merida') }}","url":"{{ url()->current() }}"}</script>

    <meta property="og:url" content="{!!  url()->current() !!}" />
    <meta property="og:site_name" content="{{ config('app.name') }}" />
    <meta property="og:image" content="{{ asset('favicons/cinco/apple-icon-180x180.png') }}" />
    <meta property="og:image:width" content="1128" />
    <meta property="og:image:height" content="581" />
    <meta property="og:image:type" content="image/jpeg" />
    <meta name="twitter:card" content="summary_large_image" />


    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicons/cinco/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicons/cinco/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicons/cinco/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicons/cinco/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicons/cinco/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/cinco/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicons/cinco/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicons/cinco/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/cinco/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('favicons/cinco/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/cinco/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/cinco/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/cinco/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicons/cinco/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('favicons/cinco/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">


    <!--<link rel="icon" type="image/x-icon" href="{{ asset('img/shield.png') }}">-->

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicons/cinco/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicons/cinco/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicons/cinco/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicons/cinco/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicons/cinco/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/cinco/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicons/cinco/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicons/cinco/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/cinco/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('favicons/cinco/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/cinco/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/cinco/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/cinco/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicons/cinco/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('favicons/cinco/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">

    <title>{{ config('app.name', 'Merida') }} | @yield('title','Home') </title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css" />

    <link rel="stylesheet" href="{{ asset('2023/basictable/css/basictable.css') }}?{{ date('Ymd') }}">
    <link rel="stylesheet" href="{{ asset('css/mobile-style.css') }}?{{ date('Ymd') }}">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>


        <script src="{{ asset('js/misc.js?').date('Ymd') }}"></script>



    @vite(['resources/css/app.css','resources/js/app.js'])



    @livewireStyles
</head>
<body class="overflow-hidden">
    <div class="flex h-screen bg-gray-100"  :class="{ 'overflow-hidden': isSideMenuOpen }">
        @include('partials.sidebar')
        <div class="flex flex-col flex-1 w-full overflow-y-auto overflow-x-auto">
            @include('partials.header')
            @yield('content')
        </div>
    </div>


    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>
    @livewireScripts
   <!--<script src="{{ asset('js/charts-lines.js') }}"></script>
    <script src="{{ asset('js/charts-pie.js') }}"></script>
    <script src="{{ asset('js/charts-bars.js') }}"></script>-->

    <script src="{{ asset('js/init-alpine.js') }}"></script>
    <script src="{{ asset('2023/basictable/js/basictable.js') }}?{{ date('Ymd') }}"></script>
    <script src="{{ asset('2023/template/js/componente_extra.js') }}?{{ date('Ymd') }}"></script>

</body>
</html>