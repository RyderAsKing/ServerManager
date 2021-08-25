<?php

namespace App\Http\Controllers\Api;

use App\Models\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ApiManagementController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $apis = Auth::user()->api()->latest()->with('user')->paginate(5);
        return view('dashboard.api.index', ['apis' => $apis]);
    }
    public function add()
    {
        return view('dashboard.api.add');
    }
    public function store(Request $request)
    {
        $this->validate($request, ['type' => 'required|integer', 'api' => 'required|min:16', 'name' => 'required|max:64', 'hostname' => 'required|max:32', 'protocol' => 'required|max:32']);

        if ($request->type == "0") { // Virtualizor
            if (empty($request->api_pass)) {
                return back()->with('status', 'The API pass is required for Virtualizor API type');
            }
            $this->validate($request, ['api_pass' => 'min:16']);
        }

        Auth::user()->api()->create(['type' => $request->type, 'api' => $request->api, 'api_pass' => $request->api_pass, 'nick' => $request->name, 'hostname' => $request->hostname, 'protocol' => $request->protocol]);
        return redirect()->route("dashboard.api.index");
    }
    public function destroy(Api $api)
    {
        $this->authorize("use_api", $api);
        Auth::user()->server()->where('api_id', $api->id)->delete();
        Auth::user()->api()->where("id", $api->id)->delete();
        return back()->with('message', 'Successfully deleted the specified API');
    }
}
