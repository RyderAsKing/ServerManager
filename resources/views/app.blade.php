<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Server Manager</title>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

        @vite(['resources/js/app.js'])
    </head>

    <body class="bg-dark text-white">
        <div id="app"></div>
        <script>
            var websocket_type = @json(env('WEBSOCKET_TYPE'));
            var websocket_domain = @json(env('WEBSOCKET_DOMAIN'));
            var websocket_port = @json(env('WEBSOCKET_PORT'));
            var websocket_url = websocket_type + websocket_domain + ":" + websocket_port;
        </script>
    </body>

</html>
