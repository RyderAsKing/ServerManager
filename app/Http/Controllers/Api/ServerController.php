<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Custom\Functions\ApiFunctions;
use Illuminate\Support\Facades\Validator;
use App\Custom\Functions\PterodactylFunctions;
use App\Custom\Functions\VirtualizorFunctions;

class ServerController extends Controller
{
    //

    public function index(Request $request)
    {
        if (empty($request->bearerToken())) {
            return response()->json(['status' => 401, 'error' => true, 'error_message' => 'Unauthorized']);
        }
        $user = ApiFunctions::returnUser($request->bearerToken());
        $servers = $user->server()->paginate(5);
        return response()->json($servers);
    }

    public function information(Request $request, $id)
    {
        if (empty($request->bearerToken())) {
            return response()->json(['status' => 401, 'error' => true, 'error_message' => 'Unauthorized']);
        }
        $user = ApiFunctions::returnUser($request->bearerToken());
        $server = $user->server()->where(['id' => $id])->firstOrFail();
        $api_instance = ApiFunctions::returnApiInstance($server);
        $type = ApiFunctions::returnType($api_instance);

        $response = array();
        if ($type == 0) {
            $v = VirtualizorFunctions::createVirtualizorClient($api_instance);
            $information = VirtualizorFunctions::getVirtualizorInformation($v, $server);
            $response = json_decode($server, true);
            array_push($response, $information);
        }
        if ($type == 1) {
            $information = PterodactylFunctions::getPterodactylInformation($server, $api_instance);
            $response = json_decode($server, true);
            array_push($response, $information);
        }
        return response()->json($response);
    }

    public function store(Request $request)
    {
        if (empty($request->bearerToken())) {
            return response()->json(['status' => 401, 'error' => true, 'error_message' => 'Unauthorized']);
        }

        $validator = Validator::make($request->all(), [
            'server_id' => 'required',
            'api_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => true, 'validation_errors' => $validator->messages()]);
        }


        $user = ApiFunctions::returnUser($request->bearerToken());

        if ($user->server()->where(['server_id' => $request->server_id, 'api_id' => $request->api_id])->count() > 0) {
            return response()->json(['satus' => 419, 'error' => true,  "error_message" => "This server already exists in our database"]);
        }

        $api_instance = $user->api()->where(['id' => $request->api_id])->firstOrFail();

        $type = $api_instance->type;
        $host_ip  = $api_instance->hostname;

        $key = $api_instance->api;
        $key_pass = $api_instance->api_pass;
        $protocol = "";
        if ($api_instance->protocol == 0) {
            $protocol = 'http';
        } else {
            $protocol = 'https';
        }

        if ($type == 0) {
            $v = VirtualizorFunctions::createVirtualizorClient($api_instance);

            $current_information = array();
            $serverinfo = $v->vpsinfo($request->server_id);
            if (empty($serverinfo)) {
                $current_information = null;
            } else {
                $current_information = $serverinfo;
            }

            if (empty($current_information) || $current_information == null) {
                return response()->json(['satus' => 419, 'error' => true,  "error_message" => "The requested server was not found'"]);
            }
            if ($current_information['uid'] == -1) {
                return response()->json(['satus' => 419, 'error' => true,  "error_message" => "The API key and password are incorrect'"]);
            }
            $hostname = $current_information['info']['hostname'];
            $ipv4 = $current_information['info']['ip'][0];

            if (!$user()->server()->create(['server_type' => 0, 'server_id' => $request->server_id, 'hostname' => $hostname, 'ipv4' => $ipv4, 'api_id' => $request->api_id])) {
                return response()->json(['satus' => 419, 'error' => true,  "error_message" => "There was an error adding the server'"]);
            }
            return response()->json(['status' => 200, 'message' => 'Server added successfully']);
        }

        // 1 = Pterodactyl
        if ($type == 1) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $protocol . "://" . $host_ip . '/api/client/servers/' . $request->server_id);
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
                    return response()->json(['status' => 'The API key and password are incorrect']);
                } elseif ($result['errors'][0]['status'] == 404) {
                    return response()->json(['status' => 'The requested server was not found']);
                }
                return response()->json(['status' => 'An unkown error occurred']);
            }
            $hostname = $result['attributes']['name'];
            $ipv4 = $result['attributes']['sftp_details']['ip'];

            if (!$user->server()->create(['server_type' => 1, 'server_id' => $request->server_id, 'hostname' => $hostname, 'ipv4' => $ipv4, 'api_id' => $request->api_id])) {
                return response()->json(['satus' => 419, 'error' => true,  "error_message" => "There was an error adding the server'"]);
            }
            return response()->json(['status' => 200, 'message' => 'Server added successfully']);
        }
    }

    public function power(Request $request, $id)
    {
        if (empty($request->bearerToken())) {
            return response()->json(['status' => 401, 'error' => true, 'error_message' => 'Unauthorized']);
        }
        $user = ApiFunctions::returnUser($request->bearerToken());
        $server = $user->server()->where(['id' => $id])->firstOrFail();
        $api_instance = ApiFunctions::returnApiInstance($server);
        $type = ApiFunctions::returnType($api_instance);
        $action = $request->action;
        $response = array();

        if ($action != 'start' && $action != 'stop' && $action != 'restart' &&  $action != 'kill') {
            return response()->json(['satus' => 404, 'error' => true,  "error_message" => "Invalid method"]);
        }

        if ($type == 1) {
            $action = ['signal' => $action];
            $action = json_encode($action);
            $response = PterodactylFunctions::sendPowerAction($server, $api_instance, $action);
            if (empty($response)) {
                $response = array('status' => 200, 'message' => 'Power action executed successfully');
            }
        }
        if ($type == 0) {
            $v = VirtualizorFunctions::createVirtualizorClient($api_instance);
            $information = VirtualizorFunctions::getVirtualizorInformation($v, $server);
            if (empty($information)) {
                return response()->json(['status' => 419, 'error' => true,  'error_message' => 'The host returned a empty response, check your API, API Pass and try again. If it still does not work then there is high possibility that your host has a invalid license or has blocked the API requests.']);
            }
            $response = VirtualizorFunctions::sendPowerAction($v, $server, $action);
            if (empty($response)) {
                return response()->json(['status' => 419, 'error' => true,  "error_message" => "Invalid response from host"]);
            }
            $response = array('status' => 200, 'message' => $response);
        }
        return response()->json($response);
    }

    public function destroy(Request $request, $id)
    {
        if (empty($request->bearerToken())) {
            return response()->json(['status' => 401, 'error' => true, 'error_message' => 'Unauthorized']);
        }
        $user = ApiFunctions::returnUser($request->bearerToken());
        $user->server()->where('id', $id)->delete();
        return response()->json(['status' => 200, 'message' => 'Server deleted successfully']);
    }
}
