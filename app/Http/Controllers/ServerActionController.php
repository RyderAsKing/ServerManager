<?php

namespace App\Http\Controllers;

use Virtualizor;
use App\Models\Api;
use App\Models\server;

include("Virtualizor.php");

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServerActionController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }
    private function returnApiInstance(Server $server)
    {
        $api_instance = Api::find(['id' => $server->api_id])->first();
        return $api_instance;
    }
    private function returnType(Api $api_instance)
    {
        $type = $api_instance->type;
        return $type;
    }
    private function createVirtualizorClient(Api $api_instance)
    {
        $protocol = $api_instance->protocol;
        if ($protocol == 0) {
            $protocol = 'http';
        } else {
            $protocol = 'https';
        }
        $host_ip  = $api_instance->hostname;
        $key = $api_instance->api;
        $key_pass = $api_instance->api_pass;
        return new Virtualizor\Virtualizor_Enduser_API($protocol, $host_ip, $key, $key_pass);
    }
    public function getVirtualizorInformation($v, Server $server)
    {
        $serverinfo = $v->vpsinfo($server->server_id);
        $vncinfo = $v->vnc($server->server_id);
        $vnc_ip = $vncinfo['ip'];
        $vnc_port = $vncinfo['port'];
        $vnc_password = $vncinfo['password'];
        $ipv4 = $server->ipv4;
        $hostname = $server->hostname;
        $bandwidth_used = $serverinfo['info']['bandwidth']['used'];
        $storage = $serverinfo['info']['vps']['space'];
        $cores = $serverinfo['info']['vps']['cores'];
        $active_time = $serverinfo['info']['show_vps_active_time'];
        $os_name = $serverinfo['info']['vps']['os_name'];
        $current_information = array('ipv4' => $ipv4, 'hostname' => $hostname, 'bandwidth_used' => $bandwidth_used, 'storage' => $storage, 'cores' => $cores, 'active_time', $active_time, 'os_name' => $os_name, 'type' => 0, 'vnc_ip' => $vnc_ip, 'vnc_port' => $vnc_port, 'vnc_password' => $vnc_password);
        return $current_information;
    }
    public function index(Server $server)
    {
        $this->authorize("use_server", $server);
        $api_instance = $this->returnApiInstance($server); // returns the api instance model

        $type = $this->returnType($api_instance); // returns type of the api instance
        // 0 = Virtualizor
        if ($type == 0) {
            $v = $this->createVirtualizorClient($api_instance);
            $information = $this->getVirtualizorInformation($v, $server);
            return view('dashboard.server.current', ['information' => $information, 'server' => $server]);
        }
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
