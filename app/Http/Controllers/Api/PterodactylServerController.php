<?php

namespace App\Http\Controllers\Api;

use App\Models\Server;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PterodactylServerController extends Controller
{
    //
    public function information(Request $request, $server_id)
    {
        $server = Server::findOrFail($server_id);
        return response()->json($server);
    }
}
