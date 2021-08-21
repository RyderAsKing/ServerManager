@extends("layout.app")
@section("content")
<div class="container">
    <h3>Login</h3>
    <form method="post" action="{{ route("login") }}">
        @if(session('status'))

        <div class="alert alert-danger" role="alert" style="margin-top: 5px;">
            {{session('status')}}
        </div>
        @endif
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        @error('email')
        {{ $message }}
        @enderror
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
        </div>
        @error('password')
        {{ $message }}
        @enderror
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
            <label class="form-check-label" for="remember_me">Remember me?</label>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection