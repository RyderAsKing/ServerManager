<?php

namespace App\Http\Controllers\Api;

use App\Models\Api;
use App\Models\User;
use App\Models\Server;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PterodactylServerController extends Controller
{
    //
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
    public function information(Request $request, $server_id)
    {
        if (empty($request->bearerToken())) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $user = User::where('api_token', $request->bearerToken())->first();
        $server = $user->server()->where(['server_id' => $server_id])->firstOrFail();
        $api_instance = $this->returnApiInstance($server);
        $type = $this->returnType($api_instance);

        // 1 = Pterodactyl
        if ($type == 1) {
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
            $information = array('ipv4' => $ipv4, 'hostname' => $hostname, 'sftp_port' => $sftp_port, 'disk' => $disk, 'cpu' => $cpu, 'memory' => $memory, 'uuid' => $uuid);
            return response()->json($information);
        } else {
            return response()->json(["message" => "Invalid type"], 404);
        }
    }
    public function resources(Request $request, $server_id)
    {
        if (empty($request->bearerToken())) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $user = User::where('api_token', $request->bearerToken())->first();
        $server = $user->server()->where(['server_id' => $server_id])->firstOrFail();
        $api_instance = $this->returnApiInstance($server);
        $type = $this->returnType($api_instance);

        // 1 = Pterodactyl
        if ($type == 1) {
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

            curl_setopt($ch, CURLOPT_URL, $protocol . "://" . $host_ip . '/api/client/servers/' . $server->server_id . '/resources');
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
            $status = $result['attributes']['current_state'];
            $ram_current = round($result['attributes']['resources']['memory_bytes'] / 1024 / 1024, 0);
            $cpu_current = $result['attributes']['resources']['cpu_absolute'];
            $disk_current = round($result['attributes']['resources']['disk_bytes'] / 1024 / 1024, 0);
            $resources = array('status' => $status, 'memory_current' => $ram_current, 'cpu_current' => $cpu_current, 'disk_current' => $disk_current);
            return response()->json($resources);
        } else {
            return response()->json(["message" => "Invalid type"], 404);
        }
    }
}
