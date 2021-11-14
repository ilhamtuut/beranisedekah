@extends('layouts.app')
@section('content')
<form class="#" method="POST" action="{{ route('login') }}" id="form-action">
    @csrf
    <p class="title-login">Masuk ke akun Anda</p>
    @include('layouts.partials.alert')
    <div class="form-group">
        <div class="input-group box-shadow">
            <span class="input-group-addon bg-white border-0"><i class="icon-user"></i></span>
            <input id="username" name="username" type="text" class="form-control border-0 form-box p-20" placeholder="Username" aria-describedby="username">
        </div>
    </div>
    <div class="form-group">
        <div class="input-group box-shadow">
            <span class="input-group-addon bg-white border-0"><i class="fa fa-lock"></i></span>
            <input id="password" name="password" type="password" class="form-control border-0 form-box p-20" placeholder="Password" aria-describedby="password">
            <span class="input-group-addon bg-white border-0 cursor-pointer" id="showPass"><i class="fa fa-eye-slash"></i></span>
        </div>
    </div>
    <p class="text-right"><a href="{{ route('password.request') }}">Lupa Password?</a></p>
    <div class="form-group">
        <button type="submit" class="btn btn-rounded btn-block btn-success p-10">Login</button>
    </div>
</form>
@endsection
@section('script')
    <script>
        $('#showPass').on('click', function(){
            var passInput = $("#password");
            if(passInput.attr('type') == 'password'){
                passInput.attr('type','text');
                $(this).html('<i class="fa fa-eye"></i>');
            }else{
                passInput.attr('type','password');
                $(this).html('<i class="fa fa-eye-slash"></i>');
            }
        });
    </script>
@endsection
