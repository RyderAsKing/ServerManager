<?php

namespace App\Http\Controllers\Api;

use App\Models\Api;
use App\Models\User;
use App\Models\Server;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Custom\Functions\ApiFunctions;
use App\Custom\Handlers\Virtualizor_Enduser_API;

class VirtualizorServerController extends Controller
{
    //
    // Virtualizor handling
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
        return new Virtualizor_Enduser_API($protocol, $host_ip, $key, $key_pass);
    }
    public function getVirtualizorInformation($v, Server $server)
    {
        $serverinfo = $v->vpsinfo($server->server_id);
        $is_vnc_available = $serverinfo['info']['vps']['vnc'];
        $vncinfo = "";
        $vnc_ip = "";
        $vnc_port = "";
        $vnc_password = "";
        if ($is_vnc_available == 1) {
            $vncinfo = $v->vnc($server->server_id);
            $vnc_ip = $vncinfo['ip'];
            $vnc_port = $vncinfo['port'];
            $vnc_password = $vncinfo['password'];
        }
        $ipv4 = $server->ipv4;
        $hostname = $server->hostname;
        $bandwidth_used = $serverinfo['info']['bandwidth']['used'];
        $storage = $serverinfo['info']['vps']['space'];
        $cores = $serverinfo['info']['vps']['cores'];
        $os_name = $serverinfo['info']['vps']['os_name'];
        $status = $serverinfo['info']['status'];
        $current_information = array('ipv4' => $ipv4, 'hostname' => $hostname, 'bandwidth_used' => $bandwidth_used, 'storage' => $storage, 'cores' => $cores, 'os_name' => $os_name, 'type' => 0, 'is_vnc_available' => $is_vnc_available, 'vnc_ip' => $vnc_ip, 'vnc_port' => $vnc_port, 'vnc_password' => $vnc_password, 'status' => $status);
        return $current_information;
    }
    // Virtualizor handling end

    public function information(Request $request, $server_id)
    {
        if (empty($request->bearerToken())) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $user = User::where('api_token', $request->bearerToken())->first();
        $server = $user->server()->where(['server_id' => $server_id])->firstOrFail();
        $api_instance = ApiFunctions::returnApiInstance($server);
        $type = ApiFunctions::returnType($api_instance);

        // 0 = Virtualizor
        if ($type == 0) {
            $v = $this->createVirtualizorClient($api_instance);
            $information = $this->getVirtualizorInformation($v, $server);
            return response()->json($information);
        } else {
            return response()->json(["message" => "Invalid type"], 404);
        }
    }

    public function power(Request $request, $server_id)
    {
        if (empty($request->bearerToken())) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $user = User::where('api_token', $request->bearerToken())->first();
        $server = $user->server()->where(['server_id' => $server_id])->firstOrFail();
        $api_instance = ApiFunctions::returnApiInstance($server);
        $type = ApiFunctions::returnType($api_instance);
        $action = $request->action;
        if ($action != 'start' && $action != 'stop' && $action != 'restart' &&  $action != 'kill') {
            return response()->json(["message" => "Invalid method"], 404);
        }
        // 0 = Virtualizor
        if ($type == 0) {
            $v = $this->createVirtualizorClient($api_instance);

            if ($action == 'start') {
                $output = $v->start($server->server_id);
            }
            if ($action == 'stop') {
                $output = $v->stop($server->server_id);
            }
            if ($action == 'restart') {
                $output = $v->restart($server->server_id);
            }
            if ($action == 'kill') {
                $output = $v->poweroff($server->server_id);
            }
            return response()->json(['msg' => $output]);
        } else {
            return response()->json(["message" => "Invalid type"], 404);
        }
    }
}
