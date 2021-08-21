@extends('layout.app')
@section('content')
<div class="container text-center">
    <div class="px-3" style="margin-top: 20%">
        <h1>Server manager.</h1>
        <p class="lead">Control your Virtual Private Server with ease, perform one click actions on your virtual private
            server.</p>
        <p class="lead">
            <a href="{{ route("dashboard") }}" class="btn btn-lg btn-secondary fw-bold border-white bg-white"
                style="color: black">Dashboard</a>
        </p>
    </div>
</div>
@endsection