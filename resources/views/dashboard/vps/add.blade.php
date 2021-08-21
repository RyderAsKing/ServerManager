@extends("layout.app")
@section("content")
<div class="container">
    <h3>Add a server</h3>
    <form method="post" action="{{ route("dashboard.vps.add") }}">
        @if(session('status'))
        <div class="alert alert-danger" role="alert" style="margin-top: 5px;">
            {{session('status')}}
        </div>
        @endif

        @csrf
        <div class="mb-3">
            <label for="virtualizor_server_id" class="form-label">VPS ID</label>
            <input type="number" class="form-control" id="virtualizor_server_id" name="virtualizor_server_id"
                placeholder="Enter your server ID" value="{{ old('virtualizor_server_id') }}" min="1">
        </div>
        @error('virtualizor_server_id')
        <div style="color: red;">{{ $message }}</div>
        @enderror
        <div class="mb-3">
            <label for="api" class="form-label">API Account</label>
            <select class="form-select" name="api">
                <option selected>{{ auth()->user()->api }}</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="api" class="form-label">Virtualizor Host</label>
            <select class="form-select" name="host">
                <option selected>VelocityNode</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Add server</button>
    </form>
</div>
@endsection