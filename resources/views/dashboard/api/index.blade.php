@extends("layout.app")
@section("content")
<div class="container text-white">
    <h3 class="text-center">Manage {{ Str::plural("API", $apis->count()) }}</h3>
    <p class="text-center">Add API's to our database so that you can add servers and then perform actions on them</p>
    @if($apis->count() > 0)
    <div class="row equal">
        @foreach ($apis as $api)
        <div class="col-sm-6">
            <div class="card bg-dark" style="margin: 5px; border: 1px solid white;">
                <div class="card-body">
                    <h5 class="card-title">Nickname: {{ $api->nick }}</h5>
                    <h5 class="card-title">API: <code>{{ $api->api }}</code></h5>
                    <h5 class="card-title">Hostname: <code>{{ $api->hostname }}</code></h5>
                    <p class="card-text">Type @if($api->type == 0) Virtualizor @elseif($api->type == 1) Pterodactyl
                        @endif <br> Created
                        {{ $api->created_at->diffForHumans() }}</p>
                    <a href="{{ route("dashboard.api.destroy", $api) }}"><button class="btn btn-outline-danger"
                            type="button">Delete</button></a>
                </div>
            </div>
        </div>
        @endforeach
        <div class="col-sm-6">
            <div class="card bg-dark" style="margin: 5px; border: 1px solid white;">
                <div class="card-body">
                    <h5 class="card-title">Add more API's?</h5>
                    <p class="card-text">Add new API's to our database so that you can add servers and then perform
                        actions on them.</p>
                    <a href="{{ route("dashboard.api.add") }}"><button class="btn btn-outline-light" type="button">Add
                            API</button></a>
                </div>
            </div>
        </div>
    </div>
    <div style="float: right;">
        {{ $apis->links() }}
    </div>

    @else
    <div class="p-5 text-white bg-dark rounded-3" style="text-align: center; margin-top: 10%;">
        <h4>Seems like you have no API, how about adding one?</h4>
        <p>Add new API's to our database so that you can add servers and then perform actions on them.</p>
        <a href="{{ route("dashboard.api.add") }}"><button class="btn btn-outline-light" type="button">Add
                API</button></a>
    </div>
    @endif
</div>
@endsection