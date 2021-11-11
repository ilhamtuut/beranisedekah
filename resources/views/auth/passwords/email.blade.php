@extends('layouts.app')

@section('content')
<form class="m-t" action="{{ route('password.email') }}" method="POST">
    @csrf
    <p class="title-login">Lupa Password</p>
    @include('layouts.partials.alert')
    <div class="form-group mb-4">
        <div class="input-group box-shadow">
            <span class="input-group-addon bg-white border-0" id="email"><i class="fa fa-envelope"></i></span>
            <input id="email" name="email" type="text" class="form-control border-0 form-box p-20" placeholder="Email" aria-describedby="email">
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-rounded btn-block btn-success p-10">Lupa Password</button>
    </div>
</form>
<div class="text-center m-t-2">Kembali ke <a href="{{route('login')}}" class="text-center text-muted">Login</a></div>
@endsection
