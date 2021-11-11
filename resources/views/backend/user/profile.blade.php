@extends('layouts.backend',['page'=>'user','active'=>'profile'])

@section('content')
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6 text-center">
            <h4 class="m-b-lg">Edit Profile</h4>
            <div class="text-left">
                @include('layouts.partials.alert')
            </div>
            <form action="{{route('user.updateProfile')}}" method="POST" id="form">
                @csrf
                <div class="form-group">
                    <div class="input-group box-shadow">
                        <span class="input-group-addon bg-white border-0"><i class="icon-user"></i></span>
                        <input id="username" value="{{ucfirst(Auth::user()->username)}}" readonly type="text" class="form-control bg-white border-0 form-box p-20" placeholder="Username" aria-describedby="username">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group box-shadow">
                        <span class="input-group-addon bg-white border-0"><i class="icon-user"></i></span>
                        <input id="name" name="nama" value="{{ucfirst(Auth::user()->name)}}" type="text" class="form-control border-0 form-box p-20" placeholder="Nama" aria-describedby="name">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group box-shadow">
                        <span class="input-group-addon bg-white border-0"><i class="fa fa-envelope"></i></span>
                        <input id="email" name="email" type="text" value="{{ucfirst(Auth::user()->email)}}" class="form-control border-0 form-box p-20" placeholder="Email" aria-describedby="email">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group box-shadow">
                        <span class="input-group-addon bg-white border-0"><i class="icon-phone"></i></span>
                        <input id="phone_number" name="telepon" value="{{ucfirst(Auth::user()->phone_number)}}" type="text" class="form-control border-0 form-box p-20" placeholder="Telepon" aria-describedby="phone_number">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group box-shadow">
                        <span class="input-group-addon bg-white border-0"><i class="icon-location"></i></span>
                        <input id="address" name="alamat" value="{{ucfirst(Auth::user()->address)}}" type="text" class="form-control border-0 form-box p-20" placeholder="Alamat" aria-describedby="address">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group box-shadow">
                        <span class="input-group-addon bg-white border-0"><i class="fa fa-lock"></i></span>
                        <input id="password" name="kata_sandi" type="password" class="form-control border-0 form-box p-20" placeholder="Kata Sandi Lama" aria-describedby="password">
                        <span class="input-group-addon bg-white border-0 cursor-pointer" id="showPass"><i class="fa fa-eye"></i></span>
                    </div>
                </div>
                <div class="form-group m-b-lg">
                    <div class="input-group box-shadow">
                        <span class="input-group-addon bg-white border-0"><i class="fa fa-lock"></i></span>
                        <input id="new_password" name="kata_sandi_baru" type="password" class="form-control border-0 form-box p-20" placeholder="Kata Sandi Baru" aria-describedby="new_password">
                        <span class="input-group-addon bg-white border-0 cursor-pointer" id="showPassNew"><i class="fa fa-eye"></i></span>
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" id="btn_submit" class="btn btn-rounded btn-block btn-success p-10">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
	    $('#btn_submit').on('click',function () {
            $(this).attr('disabled','disabled');
            $(this).append('<i class="fa fa-spinner fa-spin m-l-xs"></i>');
            $('#form').submit();
	    });
        $('#showPass').on('click', function(){
            var passInput = $("#password");
            if(passInput.attr('type') == 'password'){
                passInput.attr('type','text');
            }else{
                passInput.attr('type','password');
            }
        });
        $('#showPassNew').on('click', function(){
            var passInput = $("#new_password");
            if(passInput.attr('type') == 'password'){
                passInput.attr('type','text');
            }else{
                passInput.attr('type','password');
            }
        });
    </script>
@endsection
