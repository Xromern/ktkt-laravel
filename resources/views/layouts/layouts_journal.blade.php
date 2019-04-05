<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Scripts -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>

    <script src="{{ asset('js/notification.js') }}" defer></script>
    <script src="{{ asset('js/head.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">    <!-- Styles -->

    <link href="{{ asset('css/main.css') }}" rel="stylesheet">

    <link href="{{ asset('css/notification.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main_journal.css') }}" rel="stylesheet">
    <link href="{{ asset('css/mediaQuery.css') }}" rel="stylesheet">

</head>
<body>
<div id="notes">

</div>
<script type="text/javascript">
    var IE='\v'=='v';
    if(IE) {
        alert('Сайт не підтримує даный берузер');
        document.getElementsByName('html').innerHTML = '';
    }
</script>
<div class="container-god">
    <header>


        @include('MainSite.navbar')
    </header>
    <div class="container-journal">
        @yield('content')


    </div>
    @include('MainSite.footer')
</div>


</body>
</html>
<script type="text/javascript">
    var IE='\v'=='v';
    if(IE) {

        document.getElementsByName('html').innerHTML = '';
    }
</script>