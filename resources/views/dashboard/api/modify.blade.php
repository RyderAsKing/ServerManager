@extends("layout.app")
@section("content")
<div class="container text-white">
    <h3>Add a API</h3>
    <form method="post" action="{{ route("dashboard.api.add") }}">
    </form>
</div>
@endsection