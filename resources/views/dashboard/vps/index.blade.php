@extends("layout.app")
@section("content")
<div class="container text-white">
    <h3 style="text-align: center">Manage {{ Str::plural("Server", $vpss->count()) }}</h3>
    <p style="text-align: center">Perform powerful one click actions on servers with ease</p>
    @if($vpss->count() > 0)

    <div class="row">
        @foreach($vpss as $vps)
        <div class="col-sm-6">
            <div class="card bg-dark" style="margin: 5px; border: 1px solid white">
                <div class="card-body">
                    <h5 class="card-title">{{ $vps->virtualizor_server_id }} - {{ $vps->hostname }}</h5>
                    <p class="card-text">{{ $vps->ipv4 }}</p>
                    <a href="{{ route("dashboard.vps.current.start", $vps) }}" class="btn btn-success"><i
                            class="fas fa-play"></i></a>
                    <a href="{{ route("dashboard.vps.current.stop", $vps)}}" class="btn btn-danger"><i
                            class="fas fa-stop"></i></a>
                    <a href="{{ route("dashboard.vps.current.index", $vps)}}" class="btn btn-primary"><i
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
                    <a href="{{ route("dashboard.vps.add") }}"><button class="btn btn-outline-light" type="button">Add
                            servers</button></a>
                </div>
            </div>
        </div>
    </div>

    <div style="float: right; margin-top: 100px;">
        {{ $vpss->links() }}
    </div>
    @else
    <div class="p-5 text-white bg-dark rounded-3" style="text-align: center; margin-top: 10%;">
        <h4>Seems like you have no servers, how about adding one?</h4>
        <p>Add new servers to our database so that you can perform actions on them.</p>
        <a href="{{ route("dashboard.vps.add") }}"><button class="btn btn-outline-light" type="button">Add
                servers</button></a>
    </div>
    @endif
</div>

@endsection