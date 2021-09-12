<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Custom\Functions\ApiFunctions;
use App\Custom\Functions\PterodactylFunctions;
use App\Custom\Functions\VirtualizorFunctions;

class ServerController extends Controller
{
    //

    public function index(Request $request)
    {
        if (empty($request->bearerToken())) {
            return response()->json(['message' => 'Unauthorized']);
        }
        $user = ApiFunctions::returnUser($request->bearerToken());
        $servers = $user->server()->paginate(5);
        return response()->json($servers);
    }

    public function information(Request $request, $id)
    {
        if (empty($request->bearerToken())) {
            return response()->json(['message' => 'Unauthorized']);
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

    public function power(Request $request, $id)
    {
        if (empty($request->bearerToken())) {
            return response()->json(['status' => 401, 'message' => 'Unauthorized']);
        }
        $user = ApiFunctions::returnUser($request->bearerToken());
        $server = $user->server()->where(['id' => $id])->firstOrFail();
        $api_instance = ApiFunctions::returnApiInstance($server);
        $type = ApiFunctions::returnType($api_instance);
        $action = $request->action;
        $response = array();

        if ($action != 'start' && $action != 'stop' && $action != 'restart' &&  $action != 'kill') {
            return response()->json(['satus' => 404, "message" => "Invalid method"]);
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
                return response()->json(['status' => 419, 'message' => 'The host returned a empty response, check your API, API Pass and try again. If it still does not work then there is high possibility that your host has a invalid license or has blocked the API requests.']);
            }
            $response = VirtualizorFunctions::sendPowerAction($v, $server, $action);
            if (empty($response)) {
                return response()->json(['status' => 419, "message" => "Invalid response from host"]);
            }
            $response = array('status' => 200, 'message' => $response);
        }
        return response()->json($response);
    }

    public function destroy(Request $request, $id)
    {
        if (empty($request->bearerToken())) {
            return response()->json(['message' => 'Unauthorized']);
        }
        $user = ApiFunctions::returnUser($request->bearerToken());
        $server = $user->server()->where(['id' => $id])->firstOrFail();
        $api_instance = ApiFunctions::returnApiInstance($server);
        $user->server()->where('id', $id)->delete();
        return response()->json(['message' => 'Server deleted successfully']);
    }
}
