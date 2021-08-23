<?php

namespace App\Http\Controllers;

use App\Models\server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

include("Virtualizor.php");

use Virtualizor;

class ServerActionController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function createVirtualizorClient($api, $api_pass)
    {
        $host_ip  = '';
        $key = $api;
        $key_pass = $api_pass;
        return new Virtualizor\Virtualizor_Enduser_API($host_ip, $key, $key_pass);
    }
    public function getInformation($v, $server)
    {
        $serverinfo = $v->vpsinfo($server->virtualizor_server_id);
        $ipv4 = $server->ipv4;
        $hostname = $server->hostname;
        $bandwidth_used = $serverinfo['info']['bandwidth']['used'];
        $storage = $serverinfo['info']['vps']['space'];
        $cores = $serverinfo['info']['vps']['cores'];
        $active_time = $serverinfo['info']['show_server_active_time'];
        $os_name = $serverinfo['info']['vps']['os_name'];
        $current_information = array('ipv4' => $ipv4, 'hostname' => $hostname, 'bandwidth_used' => $bandwidth_used, 'storage' => $storage, 'cores' => $cores, 'active_time', $active_time, 'os_name' => $os_name);
        return $current_information;
    }
    public function index(Server $server)
    {
        $this->authorize("use_server", $server);
        $api = Auth::user()->api;
        $api_pass = Auth::user()->api_pass;
        $v = $this->createVirtualizorClient($api, $api_pass);
        $information = $this->getInformation($v, $server);
        return view('dashboard.server.current', ['information' => $information, 'server' => $server]);
    }
    public function start(Server $server)
    {
        $this->authorize("use_server", $server);

        return back();
    }

    public function stop(Server $server)
    {
        $this->authorize("use_server", $server);

        return back();
    }

    public function restart(Server $server)
    {
        $this->authorize("use_server", $server);

        return back();
    }

    public function destroy(Server $server)
    {
        $this->authorize("use_server", $server);

        return back();
    }
}
