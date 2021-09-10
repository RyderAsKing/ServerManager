<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Server Manager</title>

        <script src="{{ asset('js/jquery.min.js') }}" crossorigin="anonymous"></script>
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/ReactToastify.css') }}">
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    </head>

    <body class="bg-dark text-white">
        <div id="app"></div>

        <script src="{{ asset('js/app.js') }}"></script>
    </body>

</html>