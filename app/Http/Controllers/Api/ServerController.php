<?php

namespace App\Http\Controllers\Api;

use App\Models\Api;
use App\Models\User;
use App\Models\Server;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServerController extends Controller
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
    private function returnUser($bearerToken)
    {
        $user = User::where('api_token', $bearerToken)->first();
        return $user;
    }


    public function index(Request $request)
    {
        $user = $this->returnUser($request->bearerToken());
        $servers = $user->server()->paginate(5);
        return response()->json($servers);
    }

    public function information(Request $request, $id)
    {
        $user = $this->returnUser($request->bearerToken());
        $server = $user->server()->where(['id' => $id])->firstOrFail();
        return response()->json($server);
    }
}
