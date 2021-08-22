<?php

namespace App\Http\Controllers;

use App\Models\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $this->validate($request, ['type' => 'required|integer', 'api' => 'required|min:16', 'name' => 'required|max:64']);

        if ($request->type == "0") { // Virtualizor
            if (empty($request->api_pass)) {
                return back()->with('status', 'The API pass is required for Virtualizor API type');
            }
            $this->validate($request, ['api_pass' => 'min:16']);
        }

        Auth::user()->api()->create(['type' => $request->type, 'api' => $request->api, 'api_pass' => $request->api_pass, 'nick' => $request->name]);
        return redirect()->route("dashboard.api.index");
    }
    public function destroy(Api $api)
    {
        $this->authorize("use", $api);
        Auth::user()->api()->where("id", $api->id)->delete();
        return back()->with('message', 'Successfully deleted the specified API');
    }
}
