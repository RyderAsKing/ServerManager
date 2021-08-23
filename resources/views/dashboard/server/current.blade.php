@extends("layout.app")
@section("content")
<div class="container text-white">
    @if(sizeof($information) > 0)
    <div class="col-sm-12">
        <div class="card bg-dark" style="margin: 5px; border: 1px solid white; margin-top: 5%;">
            <div class="card-body">
                <h5 class="card-title">

                    @if($server->type == 0)

                    <!-- Virtualizor Online and offline -->
                    (@if($information['status'] == 0)
                    <span class="offline"></span>
                    @elseif($information['status'] == 1)

                    <span class="online"></span>
                    @endif
                    @endif)
                    <!-- Virtualizor Online and offline -->

                    {{ $server->server_id }} - {{ $server->hostname }}
                    @if($server->type == 0)
                    ({{ $information['os_name'] }})
                    @endif
                </h5>
                <p class="card-text">{{ $server->ipv4 }}</p>
                <a href="{{ route("dashboard.server.current.start", $server) }}" class="btn btn-success"><i
                        class="fas fa-play"></i> Start</a>
                <a href="{{ route("dashboard.server.current.stop", $server)}}" class="btn btn-danger"><i
                        class="fas fa-stop"></i> Stop</a>
                <a href="{{ route("dashboard.server.current.restart", $server)}}" class="btn btn-warning"><i
                        class="fas fa-redo"></i> Restart</a>
            </div>
        </div>
    </div>
    @if($server->type == 0)
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

    <div class="row">
        <div class="col-sm-8">
            <div class="card bg-dark" style="margin: 5px; border: 1px solid white">
                <div class="card-body">
                    <h5 class="card-title">More actions (Virtualizor specific)</h5>
                    <a href="#" class="btn btn-primary"><i class="fas fa-file-signature"></i>
                        Change
                        Hostname</a>
                    <a href="#" class="btn btn-primary"><i class="fas fa-key"></i>
                        Reset
                        Password</a>
                    <a href="#" class="btn btn-primary"><i class="fas fa-first-aid"></i> Enable
                        rescue mode</a>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card bg-dark" style="margin: 5px; border: 1px solid white">
                <div class="card-body">
                    <h5 class="card-title">VNC Information</h5>
                    <p class="card-text">
                        IP: <code>{{ $information['vnc_ip'] }}</code><br>
                        Port: <code>{{ $information['vnc_port'] }}</code><br>
                        Password: <code>{{ $information['vnc_password'] }}</code>
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="col-sm-12" style="text-align: center">
        <div class="card bg-dark" style="margin: 5px; border: 1px solid red">
            <div class="card-body">
                <h5 class="card-title">Remove this server?</h5>
                <a href="{{ route("dashboard.server.current.destroy", $server)}}" class="btn btn-danger"><i
                        class="fa fa-trash"></i> Remove it</a>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection