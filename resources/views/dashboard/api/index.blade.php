@extends("layout.app")
@section("content")
<div class="container text-white">
    <h3 style="text-align: center">Manage {{ Str::plural("API", $apis->count()) }}</h3>
    @if($apis->count() > 0)
    @foreach ($apis as $api)
    {{ $api->api }}
    @endforeach
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