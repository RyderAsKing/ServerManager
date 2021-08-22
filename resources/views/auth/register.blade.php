@extends("layout.app")
@section("content")
<div class="container text-white">
    <h3>Register</h3>
    <form method="post" action="{{ route("register") }}">
        @if(session('status'))

        <div class="alert alert-danger" role="alert" style="margin-top: 5px;">
            {{session('status')}}
        </div>
        @endif
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name"
                value="{{ old('name') }}">
        </div>
        @error('name')

        <div style="color: red;">{{ $message }}</div>
        @enderror
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address"
                value="{{ old('email') }}">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        @error('email')
        <div style="color: red;">{{ $message }}</div>
        @enderror
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
        </div>
        <div class="mb-3">
            <label for="confirmpassword_confirmation_password" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                placeholder="Enter your password again">
        </div>
        @error('password')
        <div style="color: red;">{{ $message }}</div>
        @enderror
        <div class="mb-3">
            <label for="api" class="form-label">Virtualizor API key</label>
            <input type="text" class="form-control" id="api" name="api" placeholder="Enter your virtualizor API key"
                value="{{ old('api') }}">
        </div>
        @error('api')
        <div style="color: red;">{{ $message }}</div>
        @enderror
        <div class="mb-3">
            <label for="api_pass" class="form-label">Virtualizor API Password</label>
            <input type="text" class="form-control" id="api_pass" name="api_pass"
                placeholder="Enter your virtualizor API Password" value="{{ old('api_pass') }}">
            <div id="apiHelp" class="form-text"><a href="https://www.virtualizor.com/docs/enduser/client-api-keys/"
                    style="color: white;" target="_blank">Here's how to get the API key for
                    your account</a></div>
        </div>
        @error('api_pass')
        <div style="color: red;">{{ $message }}</div>
        @enderror
        <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Register</button>
    </form>
</div>
@endsection