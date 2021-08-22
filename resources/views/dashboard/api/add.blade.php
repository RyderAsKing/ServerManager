@extends("layout.app")
@section("content")
<div class="container text-white">
    <h3>Add a API</h3>
    <form method="post" action="{{ route("dashboard.api.add") }}">
        @csrf
        <div class="mb-3">
            <label for="type" class="form-label">API Type</label>
            <select class="form-select" name="type">
                <option selected value="0">Virtualizor</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="api" class="form-label">API Key</label>
            <input type="text" class="form-control" id="api" name="api" placeholder="Enter your API Key"
                value="{{ old('api') }}" min="1">
        </div>
        <div class="mb-3">
            <label for="api_pass" class="form-label">API Pass</label>
            <input type="text" class="form-control" id="api_pass" name="api_pass"
                placeholder="Enter your API Pass (if required)" value="{{ old('api_pass') }}" min="1">
        </div>
        <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Add API</button>
    </form>

</div>
@endsection