<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>

    <script src="{{ asset('js/notification.js') }}" defer></script>
    <script src="{{ asset('js/head.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/big_buttons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/notification.css') }}" rel="stylesheet">
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
            window.location="https://www.google.com.ua/search?ie=UTF-8&hl=ru&q=%D1%81%D0%BA%D0%B0%D1%87%D0%B0%D1%82%D1%8C%20%D0%B1%D1%80%D0%B0%D1%83%D0%B7%D0%B5%D1%80";
        }
</script>
<div class="container-god">
<header>
    @include('MainSite.search')

    @include('MainSite.navbar')

    @include('MainSite.slider')

    @include('MainSite.big_buttons')
</header>
<main>
@yield('content')


</main>
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