@extends('layouts.app')

@section('content')
<form action="{{ route('password.update') }}" method="POST">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ request()->email }}">
    <p class="title-login">Reset Password</p>
    @include('layouts.partials.alert')
    <div class="form-group">
        <div class="input-group box-shadow">
            <span class="input-group-addon bg-white border-0" id="username"><i class="icon-user"></i></span>
            <input id="username" name="username" type="text" class="form-control border-0 form-box p-20" placeholder="Username" aria-describedby="username">
        </div>
    </div>
    <div class="form-group">
        <div class="input-group box-shadow">
            <span class="input-group-addon bg-white border-0" id="password"><i class="fa fa-lock"></i></span>
            <input id="password" name="password" type="password" class="form-control border-0 form-box p-20" placeholder="Password" aria-describedby="password">
        </div>
    </div>
    <div class="form-group mb-4">
        <div class="input-group box-shadow">
            <span class="input-group-addon bg-white border-0" id="email"><i class="fa fa-lock"></i></span>
            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control border-0 form-box p-20" placeholder="Konfirm Password" aria-describedby="email">
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-rounded btn-block btn-success p-10">Reset Password</button>
    </div>
</form>
@endsection
