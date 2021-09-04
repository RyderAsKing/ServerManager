<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Server Manager</title>

        <!-- Global CSS -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
        <link rel="stylesheet" href="{{ asset('css/alertify.css') }}">
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

        <!-- Global JS -->
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
        <script src="{{ asset('chart.min.js') }}" crossorigin="anonymous"></script>
        <script src="{{ asset("js/alertify.js") }}" crossorigin="anonymous"></script>
    </head>

    <body class="bg-dark">
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd; margin-bottom: 20px;">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('home') }}">Server Manager</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor03"
                    aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarColor03">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("dashboard") }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("dashboard.server.index") }}">List servers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("dashboard.api.index") }}">List API's</a>
                        </li>
                        @endauth
                    </ul>
                    @guest
                    <a href="{{ route("login") }}"><button class="btn btn-outline-primary">Login</button></a>
                    <a href="{{ route("register") }}"><button class="btn btn-outline-primary"
                            style="margin-left: 5px;">Register</button></a>
                    @endguest

                    @auth
                    <a href="{{ route("logout") }}"><button class="btn btn-outline-danger">Logout</button></a>
                    @endauth
                </div>
            </div>
        </nav>
        @if(session('message'))
        <script>
            alertify.notify("{{ session('message') }}", 'custom');
        </script>
        @endif
        @if(session('error'))
        <script>
            alertify.error("{{ session('error') }}");
        </script>
        @endif
        @if(session('popup'))
        <script>
            alertify.alert('Notification', "{{ session('popup') }}");
        </script>
        @endif
        @yield('content')

    </body>

</html>