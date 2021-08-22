<?php

namespace App\Http\Controllers;

use App\Models\Vps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

include("Virtualizor.php");

use Virtualizor;

class VpsActionController extends Controller
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
    public function getInformation($v, $vps)
    {
        $vpsinfo = $v->vpsinfo($vps->virtualizor_server_id);
        $ipv4 = $vps->ipv4;
        $hostname = $vps->hostname;
        $bandwidth_used = $vpsinfo['info']['bandwidth']['used'];
        $storage = $vpsinfo['info']['vps']['space'];
        $cores = $vpsinfo['info']['vps']['cores'];
        $active_time = $vpsinfo['info']['show_vps_active_time'];
        $os_name = $vpsinfo['info']['vps']['os_name'];
        $current_information = array('ipv4' => $ipv4, 'hostname' => $hostname, 'bandwidth_used' => $bandwidth_used, 'storage' => $storage, 'cores' => $cores, 'active_time', $active_time, 'os_name' => $os_name);
        return $current_information;
    }
    public function index(Vps $vps)
    {
        $this->authorize("use", $vps);
        $api = Auth::user()->api;
        $api_pass = Auth::user()->api_pass;
        $v = $this->createVirtualizorClient($api, $api_pass);
        $information = $this->getInformation($v, $vps);
        return view('dashboard.vps.current', ['information' => $information, 'vps' => $vps]);
    }
    public function start(Vps $vps)
    {
        $this->authorize("use", $vps);

        return back();
    }

    public function stop(Vps $vps)
    {
        $this->authorize("use", $vps);

        return back();
    }

    public function restart(Vps $vps)
    {
        $this->authorize("use", $vps);

        return back();
    }

    public function destroy(Vps $vps)
    {
        $this->authorize("use", $vps);

        return back();
    }
}
