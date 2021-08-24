@extends("layout.app")
@section("content")
<div class="container text-white">
    <h3 class="text-center">Add a server</h3>
    @if($apis->count() < 1) <div class="p-5 text-white bg-dark rounded-3" style="text-align: center; margin-top: 10%;">
        <h4>Seems like you have no API, how about adding one?</h4>
        <p>Add new API's to our database so that you can add servers and then perform actions on them.</p>
        <a href="{{ route("dashboard.api.add") }}"><button class="btn btn-outline-light" type="button">Add
                API</button></a>
</div>
@else <form method="post" action="{{ route("dashboard.server.add") }}">
    @if(session('status'))
    <div class="alert alert-danger" role="alert" style="margin-top: 5px;">
        {{session('status')}}
    </div>
    @endif

    @csrf
    <div class="mb-3">
        <label for="server_id" class="form-label">Server ID</label>
        <input class="form-control" id="server_id" name="server_id" placeholder="Enter your server ID"
            value="{{ old('server_id') }}">
    </div>
    @error('server_id')
    <div style="color: red;">{{ $message }}</div>
    @enderror
    <div class="mb-3">
        <label for="api_id" class="form-label">API Account</label>
        <select class="form-select" name="api_id">
            @foreach ($apis as $api)
            <option value="{{ $api->id }}">{{ $api->nick }} | {{ $api->api }} | @if($api->type == 0) Virtualizor
                @elseif($api->type == 1) Pterodactyl @endif
            </option>
            @endforeach
        </select>
    </div>
    @error('api_id')
    <div style="color: red;">{{ $message }}</div>
    @enderror
    <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Add server</button>
</form>
@endif

</div>
@endsection