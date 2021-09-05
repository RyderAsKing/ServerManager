<?php

namespace App\Http\Controllers\Api;

use App\Models\Api;
use App\Models\User;
use App\Models\Server;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Custom\Functions\ApiFunctions;

class ServerController extends Controller
{
    //

    public function index(Request $request)
    {
        $user = ApiFunctions::returnUser($request->bearerToken());
        $servers = $user->server()->paginate(5);
        return response()->json($servers);
    }

    public function information(Request $request, $id)
    {
        $user = ApiFunctions::returnUser($request->bearerToken());
        $server = $user->server()->where(['id' => $id])->firstOrFail();
        return response()->json($server);
    }
}
