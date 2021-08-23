@extends("layout.app")
@section("content")
<div class="container">
    <div class="row align-items-md-stretch" style="margin-top: 5%;">
        <h3 style="text-align: center">What would you like to do today?</h3>
        <div class="col-md-6" style="margin-top: 15px;">
            <div class="p-5 text-white bg-dark rounded-3" style="border: 1px solid #E3F2FD">
                <h2>Manage existing servers</h2>
                <p>Perform powerful one click actions on servers with ease</p>
                <a href="{{ route("dashboard.server.show") }}"><button class="btn btn-outline-light" type="button">List
                        servers</button></a>
            </div>
        </div>
        <div class="col-md-6" style="margin-top: 15px;">
            <div class="p-5 text-white bg-dark rounded-3" style="border: 1px solid #E3F2FD">
                <h2>Add new servers</h2>
                <p>Add new servers to our database so that you can perform actions on them.</p>
                <a href="{{ route("dashboard.server.add") }}"><button class="btn btn-outline-light" type="button">Add
                        servers</button></a>
            </div>
        </div>
        <div class="col-sm-3"></div>
        <div class="col-md-6" style="margin-top: 15px;">
            <div class="p-5 text-white bg-dark rounded-3" style="border: 1px solid #E3F2FD">
                <h2>Manage API</h2>
                <p>Add, remove or modify existing API keys.</p>
                <a href="{{ route("dashboard.api.index") }}"><button class="btn btn-outline-light" type="button">Modify
                        existing API</button></a>
                <a href="{{ route("dashboard.api.add") }}"><button class="btn btn-outline-light" type="button">Add
                        new API</button></a>
            </div>
        </div>
        <div class="col-sm-3"></div>
    </div>
</div>
@endsection