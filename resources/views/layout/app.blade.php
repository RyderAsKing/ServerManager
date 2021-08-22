<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Server Manager</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous">
        </script>
        <script src="https://kit.fontawesome.com/691f4cd05f.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="{{ asset("css/alertify.css") }}">
        <script src="{{ asset("js/alertify.js") }}" crossorigin="anonymous"></script>
        <style>
            .ajs-message.ajs-custom {
                color: #5aabd3;
                background-color: #196388;
                border-color: #31708f;
            }
        </style>
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
        @yield('content')

    </body>

</html>