@extends("layout.app")
@section("content")
<div class="container">
    <h3 style="text-align: center">Manage Servers</h3>
    <p style="text-align: center">Perform powerful one click actions on servers with ease</p>
    @if($vpss->count() > 0)

    <table class="table table-dark" style="margin-top: 5%">
        <thead>
            <tr>
                <th scope="col" style="text-align: center">Server ID</th>
                <th scope="col" style="text-align: center">IP</th>
                <th scope="col" style="text-align: center">HOSTNAME</th>
                <th scope="col" style="text-align: center">ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vpss as $vps)
            <tr>
                <th scope="row" style="text-align: center">{{ $vps->virtualizor_server_id }}</th>
                <td style="text-align: center">{{ $vps->ipv4 }}</td>
                <td style="text-align: center">{{ $vps->hostname }}</td>
                <td style="text-align: center">
                    <a class="btn btn-success"><i class="fas fa-play"></i></a>
                    <a class="btn btn-danger"><i class="fas fa-stop"></i></a>
                    <a class="btn btn-primary"><i class="fas fa-external-link-alt"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
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