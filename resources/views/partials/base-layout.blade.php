<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>Des projets écolos et économes</title>
</head>

<body class="body">
    <!-- * Navbar * -->
    <x-navbar></x-navbar>

    <!-- * Header * -->

    @yield('header')

    <!-- * Menu * -->

    @auth
    <x-menu></x-menu>
    @endauth


    <!-- * Main * -->

    <main class="main">
        <x-flash-message></x-flash-message>

        <!-- * Contenu de la page * -->

        @yield('layout-content')

    </main>

    <!-- * Footer * -->

    @include('layouts.footer')

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>