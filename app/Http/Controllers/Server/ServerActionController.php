<?php

namespace App\Http\Controllers\Server;


use App\Models\Server;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Custom\Functions\ApiFunctions;
use App\Custom\Functions\PterodactylFunctions;
use App\Custom\Functions\VirtualizorFunctions;

class ServerActionController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Server $server)
    {
        $this->authorize("use_server", $server);
        $api_instance = ApiFunctions::returnApiInstance($server); // returns the api instance model
        $type = ApiFunctions::returnType($api_instance); // returns type of the api instance
        // 0 = Virtualizor
        if ($type == 0) {
            $v = VirtualizorFunctions::createVirtualizorClient($api_instance);
            $information = VirtualizorFunctions::getVirtualizorInformation($v, $server);
            if (empty($information)) {
                return back()->with('popup', 'The host returned a empty response, check your API, API Pass and try again. If it still does not work then there is high possibility that your host has a invalid license or has blocked the API requests.');
            }
            return view('dashboard.server.current', ['information' => $information, 'server' => $server]);
        }
        // 1 = Pterodactyl
        if ($type == 1) {
            $api_instance = ApiFunctions::returnApiInstance($server);
            $information = PterodactylFunctions::getPterodactylInformation($server, $api_instance);
            return view('dashboard.server.current', ['information' => $information, 'server' => $server]);
        }
    }
    public function start(Server $server)
    {
        $this->authorize("use_server", $server);
        $api_instance = ApiFunctions::returnApiInstance($server);
        $type = ApiFunctions::returnType($api_instance);
        // 0 = Virtualizor
        if ($type == 0) {
            $v = VirtualizorFunctions::createVirtualizorClient($api_instance);
            $information = VirtualizorFunctions::getVirtualizorInformation($v, $server);
            if (empty($information)) {
                return back()->with('popup', 'The host returned a empty response, check your API, API Pass and try again. If it still does not work then there is high possibility that your host has a invalid license or has blocked the API requests.');
            }
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
        $api_instance = ApiFunctions::returnApiInstance($server);
        $type = ApiFunctions::returnType($api_instance);
        // 0 = Virtualizor
        if ($type == 0) {
            $v = VirtualizorFunctions::createVirtualizorClient($api_instance);
            $information = VirtualizorFunctions::getVirtualizorInformation($v, $server);
            if (empty($information)) {
                return back()->with('popup', 'The host returned a empty response, check your API, API Pass and try again. If it still does not work then there is high possibility that your host has a invalid license or has blocked the API requests.');
            }
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
        $api_instance = ApiFunctions::returnApiInstance($server);
        $type = ApiFunctions::returnType($api_instance);
        // 0 = Virtualizor
        if ($type == 0) {
            $v = VirtualizorFunctions::createVirtualizorClient($api_instance);
            $information = VirtualizorFunctions::getVirtualizorInformation($v, $server);
            if (empty($information)) {
                return back()->with('popup', 'The host returned a empty response, check your API, API Pass and try again. If it still does not work then there is high possibility that your host has a invalid license or has blocked the API requests.');
            }
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
        $api_instance = ApiFunctions::returnApiInstance($server);
        $type = ApiFunctions::returnType($api_instance);
        // 0 = Virtualizor
        if ($type == 0) {
            $v = VirtualizorFunctions::createVirtualizorClient($api_instance);
            $information = VirtualizorFunctions::getVirtualizorInformation($v, $server);
            if (empty($information)) {
                return back()->with('popup', 'The host returned a empty response, check your API, API Pass and try again. If it still does not work then there is high possibility that your host has a invalid license or has blocked the API requests.');
            }
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
        $api_instance = ApiFunctions::returnApiInstance($server);
        $type = ApiFunctions::returnType($api_instance);
        // 0 = Virtualizor
        if ($type == 0) {
            $v = VirtualizorFunctions::createVirtualizorClient($api_instance);
            $information = VirtualizorFunctions::getVirtualizorInformation($v, $server);
            if (empty($information)) {
                return back()->with('popup', 'The host returned a empty response, check your API, API Pass and try again. If it still does not work then there is high possibility that your host has a invalid license or has blocked the API requests.');
            }
            $hostname = $request->hostname;
            $output = $v->hostname($server->server_id, $hostname);
            return back()->with('popup', $output);
        }
    }
    public function changePassword(Request $request, Server $server)
    {
        $this->authorize("use_server", $server);
        $this->validate($request, ['password' => 'required|min:8|max:32']);
        $api_instance = ApiFunctions::returnApiInstance($server);
        $type = ApiFunctions::returnType($api_instance);
        // 0 = Virtualizor
        if ($type == 0) {
            $v = VirtualizorFunctions::createVirtualizorClient($api_instance);
            $information = VirtualizorFunctions::getVirtualizorInformation($v, $server);
            if (empty($information)) {
                return back()->with('popup', 'The host returned a empty response, check your API, API Pass and try again. If it still does not work then there is high possibility that your host has a invalid license or has blocked the API requests.');
            }
            $password = $request->password;
            $output = $v->changepassword($server->server_id, $password);
            return back()->with('popup', $output);
        }
    }
}
