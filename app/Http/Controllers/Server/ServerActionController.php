<?php

namespace App\Http\Controllers\Server;


use App\Models\Api;
use App\Models\Server;

use Illuminate\Http\Request;
use App\Custom\Handlers\Virtualizor_Enduser_API;
use App\Http\Controllers\Controller;
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


    // Pterodactyl handling
    private function getPterodactylInformation(Server $server, Api $api_instance)
    {
        $server_id = $server->server_id;
        $host_ip = $api_instance->hostname;
        $key = $api_instance->api;

        $protocol = "";
        if ($api_instance->protocol == 0) {
            $protocol = 'http';
        } else {
            $protocol = 'https';
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $protocol . "://" . $host_ip . '/api/client/servers/' . $server->server_id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $key;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        $result = json_decode($result, true);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        if (isset($result['errors'])) {
            if ($result['errors'][0]['status'] == 403) {
                return back()->with('status', 'The API key and password are incorrect');
            } elseif ($result['errors'][0]['status'] == 404) {
                return back()->with('status', 'The requested server was not found');
            }
            return back()->with('status', 'There was an error adding the server');
        }
        $hostname = $result['attributes']['name'];
        $ipv4 = $result['attributes']['sftp_details']['ip'];
        $sftp_port = $result['attributes']['sftp_details']['port'];
        $disk = $result['attributes']['limits']['disk'];
        $cpu = $result['attributes']['limits']['cpu'];
        $memory = $result['attributes']['limits']['memory'];
        $uuid = $result['attributes']['uuid'];
        $current_information = array('ipv4' => $ipv4, 'hostname' => $hostname, 'sftp_port' => $sftp_port, 'disk' => $disk, 'cpu' => $cpu, 'memory' => $memory, 'uuid' => $uuid, 'api_token' => $server->user->api_token);
        return $current_information;
    }
    // Pterodactyl handling end

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
        // 1 = Pterodactyl
        if ($type == 1) {
            $api_instance = $this->returnApiInstance($server);
            $information = $this->getPterodactylInformation($server, $api_instance);
            return view('dashboard.server.current', ['information' => $information, 'server' => $server]);
        }
    }
    public function start(Server $server)
    {
        $this->authorize("use_server", $server);
        $api_instance = $this->returnApiInstance($server);
        $type = $this->returnType($api_instance);
        // 0 = Virtualizor
        if ($type == 0) {
            $v = $this->createVirtualizorClient($api_instance);
            $output = $v->start($server->server_id);
            return back()->with('popup', $output);
        }

        if ($type == 1) {
            $host_ip = $api_instance->hostname;
            $key = $api_instance->api;
            $action = array('signal' => 'start');

            $action = json_encode($action);
            $protocol = "";
            if ($api_instance->protocol == 0) {
                $protocol = 'http';
            } else {
                $protocol = 'https';
            }

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $protocol . "://" . $host_ip . '/api/client/servers/' . $server->server_id . '/power');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $action);

            $headers = array();
            $headers[] = 'Accept: application/json';
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: Bearer ' . $key;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            $result = json_decode($result, true);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
            return back()->with('popup', 'Successfully started the server');
        }
    }

    public function stop(Server $server)
    {
        $this->authorize("use_server", $server);
        $api_instance = $this->returnApiInstance($server);
        $type = $this->returnType($api_instance);
        // 0 = Virtualizor
        if ($type == 0) {
            $v = $this->createVirtualizorClient($api_instance);
            $output = $v->stop($server->server_id);
            return back()->with('popup', $output);
        }
        if ($type == 1) {
            $host_ip = $api_instance->hostname;
            $key = $api_instance->api;
            $action = array('signal' => 'stop');

            $action = json_encode($action);
            $protocol = "";
            if ($api_instance->protocol == 0) {
                $protocol = 'http';
            } else {
                $protocol = 'https';
            }

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $protocol . "://" . $host_ip . '/api/client/servers/' . $server->server_id . '/power');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $action);

            $headers = array();
            $headers[] = 'Accept: application/json';
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: Bearer ' . $key;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            $result = json_decode($result, true);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
            return back()->with('popup', 'Successfully stopped the server');
        }
    }

    public function restart(Server $server)
    {
        $this->authorize("use_server", $server);
        $api_instance = $this->returnApiInstance($server);
        $type = $this->returnType($api_instance);
        // 0 = Virtualizor
        if ($type == 0) {
            $v = $this->createVirtualizorClient($api_instance);
            $output = $v->restart($server->server_id);
            return back()->with('popup', $output);
        }
        if ($type == 1) {
            $host_ip = $api_instance->hostname;
            $key = $api_instance->api;
            $action = array('signal' => 'restart');

            $action = json_encode($action);
            $protocol = "";
            if ($api_instance->protocol == 0) {
                $protocol = 'http';
            } else {
                $protocol = 'https';
            }

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $protocol . "://" . $host_ip . '/api/client/servers/' . $server->server_id . '/power');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $action);

            $headers = array();
            $headers[] = 'Accept: application/json';
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: Bearer ' . $key;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            $result = json_decode($result, true);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
            return back()->with('popup', 'Successfully restarted the server');
        }
    }

    public function kill(Server $server)
    {
        $this->authorize("use_server", $server);
        $api_instance = $this->returnApiInstance($server);
        $type = $this->returnType($api_instance);
        // 0 = Virtualizor
        if ($type == 0) {
            $v = $this->createVirtualizorClient($api_instance);
            $output = $v->poweroff($server->server_id);
            return back()->with('popup', $output);
        }
        if ($type == 1) {
            $host_ip = $api_instance->hostname;
            $key = $api_instance->api;
            $action = array('signal' => 'kill');

            $action = json_encode($action);
            $protocol = "";
            if ($api_instance->protocol == 0) {
                $protocol = 'http';
            } else {
                $protocol = 'https';
            }

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $protocol . "://" . $host_ip . '/api/client/servers/' . $server->server_id . '/power');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $action);

            $headers = array();
            $headers[] = 'Accept: application/json';
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: Bearer ' . $key;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            $result = json_decode($result, true);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);

            return back()->with('popup', 'Successfully killed the server');
        }
    }

    public function destroy(Server $server)
    {
        $this->authorize("use_server", $server);
        Auth::user()->server()->where('id', $server->id)->delete();
        return redirect()->route("dashboard.server.index")->with('message', "Successfully removed the specified server");
    }

    public function changeHostname(Request $request, Server $server)
    {
        $this->authorize("use_server", $server);
        $this->validate($request, ['hostname' => 'required|max:32']);
        $api_instance = $this->returnApiInstance($server);
        $type = $this->returnType($api_instance);
        // 0 = Virtualizor
        if ($type == 0) {
            $v = $this->createVirtualizorClient($api_instance);
            $hostname = $request->hostname;
            $output = $v->hostname($server->server_id, $hostname);
            return back()->with('popup', $output);
        }
    }
    public function changePassword(Request $request, Server $server)
    {
        $this->authorize("use_server", $server);
        $this->validate($request, ['password' => 'required|min:8|max:32']);
        $api_instance = $this->returnApiInstance($server);
        $type = $this->returnType($api_instance);
        // 0 = Virtualizor
        if ($type == 0) {
            $v = $this->createVirtualizorClient($api_instance);
            $password = $request->password;
            $output = $v->changepassword($server->server_id, $password);
            return back()->with('popup', $output);
        }
    }
}
