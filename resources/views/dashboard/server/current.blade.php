@extends("layout.app")
@section("content")
<div class="container text-white">
    @if(sizeof($information) > 0)
    <div class="col-sm-12">
        <div class="card bg-dark" style="margin: 5px; border: 1px solid white; margin-top: 5%;">
            <div class="card-body">
                <h5 class="card-title">
                    <!-- if virtualizor -->
                    @if($server->server_type == 0)

                    <!-- Virtualizor Online and offline -->
                    (@if($information['status'] == 0)
                    <span class="offline"></span>
                    @elseif($information['status'] == 1)

                    <span class="online"></span>
                    @endif)

                    <!-- if pterodactyl -->
                    @elseif($server->server_type == 1)
                    ( <span class="offline hidden" id="offline"></span>
                    <span class="online hidden" id="online"></span> )
                    @endif


                    <!-- Virtualizor Online and offline -->

                    {{ $server->server_id }} - {{ $server->hostname }}

                    <!-- if virtualizor -->
                    @if($server->server_type == 0)
                    ({{ $information['os_name'] }})

                    <!-- if pterodactyl -->
                    @elseif($server->server_type == 1)
                    ({{ $information['uuid'] }})

                    @endif
                </h5>
                <p class="card-text">{{ $server->ipv4 }}</p>
                <a href="{{ route("dashboard.server.current.start", $server) }}" class="btn btn-success"><i
                        class="fas fa-play"></i> Start</a>
                <a href="{{ route("dashboard.server.current.stop", $server)}}" class="btn btn-danger"><i
                        class="fas fa-stop"></i> Stop</a>
                <a href="{{ route("dashboard.server.current.restart", $server)}}" class="btn btn-warning"><i
                        class="fas fa-redo"></i> Restart</a>
                <a href="{{ route("dashboard.server.current.kill", $server)}}" class="btn btn-danger"><i
                        class="fas fa-power-off"></i>
                    Kill</a>
            </div>
        </div>
    </div>
    <!-- if virtualizor -->
    @if($server->server_type == 0)
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
        <div class="col-sm-12">
            <div class="card bg-dark" style="margin: 5px; border: 1px solid white">
                <div class="card-body">
                    <h5 class="card-title">More actions (Virtualizor specific)</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#changeHostname"><i class="fas fa-file-signature"></i>
                        Change
                        Hostname
                    </button>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changePassword"><i
                            class="fas fa-key"></i>
                        Change Password
                    </button>
                    @if($information['is_vnc_available'] == 1)
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#vncInformation"><i
                            class="fas fa-desktop"></i>
                        VNC information
                    </button>
                    @endif
                </div>
            </div>
        </div>

    </div>
    @elseif($server->server_type == 1)
    <div class="row">
        <div class="col-sm-4">
            <div class="card bg-dark" style="margin: 5px; border: 1px solid white">
                <div class="card-body">
                    <h5 class="card-title">Memory</h5>
                    <p class="card-text"><span id="memory"></span>@if($information['memory'] == 0) Unlimited @else
                        {{ $information['memory'] }} MB @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card bg-dark" style="margin: 5px; border: 1px solid white">
                <div class="card-body">
                    <h5 class="card-title">CPU</h5>
                    <p class="card-text"><span id="cpu"></span>@if($information['cpu'] == 0) Unlimited @else
                        {{ $information['cpu'] }}% @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card bg-dark" style="margin: 5px; border: 1px solid white">
                <div class="card-body">
                    <h5 class="card-title">Disk</h5>
                    <p class="card-text"><span id="disk"></span>@if($information['disk'] == 0) Unlimited @else
                        {{ $information['disk'] }} MB @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 5px;">
        <div class="col-sm-12">
            <canvas id="resource_chart" height="70"></canvas>
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
</div>
<!-- Modals -->
<div class="modal fade" id="changePassword" role="modal" tabindex="-1" aria-labelledby="changePassword"
    aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="{{ route("dashboard.server.current.changepassword", $server) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Change password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="string" class="form-control" id="password" aria-describedby="emailHelp"
                            placeholder="Enter new password" name="password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Change password</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="changeHostname" role="modal" tabindex="-1" aria-labelledby="changeHostname"
    aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="{{ route("dashboard.server.current.changehostname", $server) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Change Hostname</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="hostname">New Hostname</label>
                        <input type="string" class="form-control" id="hostname" aria-describedby="emailHelp"
                            placeholder="Enter new hostname" name="hostname">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Change hostname</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($server->server_type == 0)
@if($information['is_vnc_available'] == 1)
<div class="modal fade" id="vncInformation" role="modal" tabindex="-1" aria-labelledby="vncInformation"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">VNC Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    IP: <code>{{ $information['vnc_ip'] }}</code><br>
                    Port: <code>{{ $information['vnc_port'] }}</code><br>
                    Password: <code>{{ $information['vnc_password'] }}</code>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
@endif
@endif
<!-- Additional Javascript if required -->
@if($server->server_type == 1)
<script>
    const socket = io("{{ $information['socket'] }}", {
        "query": "token={{ $information['token'] }}"
    });
    
    socket.on('error', function (err) {
        console.log(err);
    });
</script>

<script>
    var noPercentage = false;
    var memoryLabel = "(Percentage)";
    var maxMem = parseInt('{{ $information['memory'] }}');
    if(maxMem == 0){
        noPercentage = true;
        memoryLabel = "(Megabyte)";
    }
    var memory = document.getElementById("memory");
    var cpu = document.getElementById("cpu");
    var disk = document.getElementById("disk");
    var ctc = $('#resource_chart');
    var TimeLabels = [timeformat(new Date()), timeformat(new Date())];
    var CPUData = Array(23).fill('');
    CPUData.push(0.01);
    var MemoryData = Array(23).fill('');
    MemoryData.push(0.01);
    
    function timeformat(date) {
        var h = date.getHours();
        var m = date.getMinutes();
        var x = h >= 12 ? 'pm' : 'am';
        h = h % 12;
        h = h ? h : 12;
        m = m < 10 ? '0'+m: m;
        var mytime= h + ':' + m + ' ' + x;
        return mytime;
    }
    first();

    var CPUChart = new Chart(ctc, {
        type: 'line',
        data: {
            labels: Array(24).fill(''),
            datasets: [
                {
                    cubicInterpolationMode: 'monotone',
                    label: "CPU Usage (Percentage)",
                    fill: false,
                    lineTension: 0.4,
                    backgroundColor: "#5EFFA7",
                    borderColor: '#5EFFA7',
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "#5EFFA7",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "#5EFFA7",
                    pointHoverBorderColor: "rgba(220,220,220,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: CPUData,
                    spanGaps: false,
                },
                {
                    cubicInterpolationMode: 'monotone',
                    label: `Memory Usage ${memoryLabel}`,
                    fill: false,
                    lineTension: 0.4,
                    backgroundColor: "#FFC107",
                    borderColor: "#FFC107",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "#FFC107",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "#FFC107",
                    pointHoverBorderColor: "rgba(220,220,220,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: MemoryData,
                    spanGaps: false,
                }
            ]
        },
        options: {
            title: {
                display: true,
                text: 'Resource Usage'
            },
            legend: {
                display: false,
            },
            animation: {
                duration: 1,
            },
            scales: {
                yAxes: [{
                    ticks: {
                        suggestedMin: 0,
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    function first() {
        var online = document.getElementById("online");
        var offline = document.getElementById("offline");
        $.ajax({
            url: '/api/server/pterodactyl/{{ $server->server_id }}/resources',
            type: 'GET',
            contentType: 'application/json',
            headers: {
                'Authorization': 'Bearer {{ $information['api_token'] }}'
            },
            success: function(result) {
                // CallBack(result);
                if (result['status'] == 'offline') {
                    offline.classList.remove("hidden");
                    online.classList.add("hidden");
                } else {
                    online.classList.remove("hidden");
                    offline.classList.add("hidden");
                }
            }
        });
        setInterval(update, 1000);
    }

    function update() {
        $.ajax({
            async: true,
            url: '/api/server/pterodactyl/{{ $server->server_id }}/resources',
            type: 'GET',
            contentType: 'application/json',
            headers: {
                'Authorization': 'Bearer {{ $information['api_token'] }}'
            },
            success: function(result) {
                if (CPUData.length > 25) {
                    CPUData.shift();
                    MemoryData.shift();
                    TimeLabels.shift();
                }
                var cpuUse = result['cpu_current'];
                var memoryUse;

                if(noPercentage == true){
                    memoryUse = (result['memory_current']);
                }
                else{
                    memoryUse = (result['memory_current'] / maxMem) * 100;
                }

                CPUData.push(cpuUse);
                MemoryData.push(memoryUse);
                var dateWithouthSecond = new Date();

                memory.innerHTML = `${result['memory_current']} /`;
                cpu.innerHTML = `${result['cpu_current']} /`;
                disk.innerHTML = `${result['disk_current']} /`;
                TimeLabels.push(timeformat(new Date()));
                CPUChart.update();
            }
        });
    }
</script>
@endif

@endsection