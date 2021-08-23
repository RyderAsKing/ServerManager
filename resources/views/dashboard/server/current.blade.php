@extends("layout.app")
@section("content")
<div class="container text-white">
    @if(sizeof($information) > 0)
    <div class="col-sm-12">
        <div class="card bg-dark" style="margin: 5px; border: 1px solid white; margin-top: 10%;">
            <div class="card-body">
                <h5 class="card-title">{{ $vps->virtualizor_server_id }} - {{ $vps->hostname }}
                    ({{ $information['os_name'] }})</h5>
                <p class="card-text">{{ $vps->ipv4 }}</p>
                <a href="{{ route("dashboard.vps.current.start", $vps) }}" class="btn btn-success"><i
                        class="fas fa-play"></i> Start</a>
                <a href="{{ route("dashboard.vps.current.stop", $vps)}}" class="btn btn-danger"><i
                        class="fas fa-stop"></i> Stop</a>
                <a href="{{ route("dashboard.vps.current.restart", $vps)}}" class="btn btn-warning"><i
                        class="fas fa-redo"></i> Restart</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="card bg-dark" style="margin: 5px; border: 1px solid white">
                <div class="card-body">
                    <h5 class="card-title">Bandwidth Usage</h5>
                    <p class="card-text">{{ $information['bandwidth_used'] }}
                        {{ Str::plural("GB", $information['bandwidth_used']) }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card bg-dark" style="margin: 5px; border: 1px solid white">
                <div class="card-body">
                    <h5 class="card-title">Total Cores</h5>
                    <p class="card-text">{{ $information['cores'] }} {{ Str::plural("Core", $information['cores']) }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card bg-dark" style="margin: 5px; border: 1px solid white">
                <div class="card-body">
                    <h5 class="card-title">Total Storage</h5>
                    <p class="card-text">{{ $information['storage'] }} {{ Str::plural("GB", $information['storage']) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12" style="text-align: center">
        <div class="card bg-dark" style="margin: 5px; border: 1px solid red">
            <div class="card-body">
                <h5 class="card-title">Remove this server?</h5>
                <a href="{{ route("dashboard.vps.current.destroy", $vps)}}" class="btn btn-danger"><i
                        class="fa fa-trash"></i> Remove it</a>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection