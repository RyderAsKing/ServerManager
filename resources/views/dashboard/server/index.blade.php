@extends("layout.app")
@section("content")
<div class="container text-white">
    <h3 class="text-center">Manage {{ Str::plural("Server", $servers->count()) }}</h3>
    <p class="text-center">Perform powerful one click actions on servers with ease</p>
    @if($servers->count() > 0)

    <div class="row">
        @foreach($servers as $server)
        <div class="col-sm-6">
            <div class="card bg-dark" style="margin: 5px; border: 1px solid white">
                <div class="card-body">
                    <h5 class="card-title">{{ $server->server_id }} - {{ $server->hostname }}</h5>
                    <p class="card-text">{{ $server->ipv4 }}</p>
                    <a href="{{ route("dashboard.server.current.start", $server) }}" class="btn btn-success"><i
                            class="fas fa-play"></i></a>
                    <a href="{{ route("dashboard.server.current.stop", $server)}}" class="btn btn-danger"><i
                            class="fas fa-stop"></i></a>
                    <a href="{{ route("dashboard.server.current.restart", $server)}}" class="btn btn-warning"><i
                            class="fas fa-redo"></i><a>
                            <a href="{{ route("dashboard.server.current.index", $server)}}" class="btn btn-primary"><i
                                    class="fas fa-external-link-alt"></i></a>
                </div>
            </div>
        </div>

        @endforeach
        <div class="col-sm-6">
            <div class="card bg-dark" style="margin: 5px; border: 1px solid white">
                <div class="card-body">
                    <h5 class="card-title">Add more servers?</h5>
                    <p class="card-text">Add new servers to our database so that you can perform actions on them.</p>
                    <a href="{{ route("dashboard.server.add") }}"><button class="btn btn-outline-light"
                            type="button">Add
                            servers</button></a>
                </div>
            </div>
        </div>
    </div>

    <div style="float: right; margin-top: 100px;">
        {{ $servers->links() }}
    </div>
    @else
    <div class="p-5 text-white bg-dark rounded-3" style="text-align: center; margin-top: 10%;">
        <h4>Seems like you have no servers, how about adding one?</h4>
        <p>Add new servers to our database so that you can perform actions on them.</p>
        <a href="{{ route("dashboard.server.add") }}"><button class="btn btn-outline-light" type="button">Add
                servers</button></a>
    </div>
    @endif
</div>

@endsection