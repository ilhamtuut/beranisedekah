@extends('layouts.backend',['page'=>'add_member'])

@section('content')
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6 text-center">
            <h4 class="mb-0"><b>Daftarkan Member Baru</b></h4>
            <p class="m-b-lg">Menjadi bagian dari masa depan.</p>
            <div class="text-left">
                @include('layouts.partials.alert')
            </div>
            <form action="{{route('team.save_member')}}" method="POST" id="form">
                @csrf
                <div class="form-group">
                    <div class="input-group box-shadow">
                        <span class="input-group-addon bg-white border-0"><i class="icon-user"></i></span>
                        <input id="username" name="username" type="text" class="form-control bg-white border-0 form-box p-20" placeholder="Username" aria-describedby="username">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group box-shadow">
                        <span class="input-group-addon bg-white border-0"><i class="icon-user"></i></span>
                        <input id="name" name="nama" type="text" class="form-control border-0 form-box p-20" placeholder="Nama" aria-describedby="name">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group box-shadow">
                        <span class="input-group-addon bg-white border-0"><i class="fa fa-envelope"></i></span>
                        <input id="email" name="email" type="text" class="form-control border-0 form-box p-20" placeholder="Email" aria-describedby="email">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group box-shadow">
                        <span class="input-group-addon bg-white border-0"><i class="icon-phone"></i></span>
                        <input id="phone_number" name="telepon" type="text" class="form-control border-0 form-box p-20" placeholder="Telepon" aria-describedby="phone_number">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group box-shadow">
                        <span class="input-group-addon bg-white border-0"><i class="icon-location"></i></span>
                        <input id="address" name="alamat" type="text" class="form-control border-0 form-box p-20" placeholder="Alamat" aria-describedby="address">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group box-shadow">
                        <span class="input-group-addon bg-white border-0"><i class="fa fa-lock"></i></span>
                        <input id="password" name="kata_sandi" type="password" class="form-control border-0 form-box p-20" placeholder="Kata Sandi" aria-describedby="password">
                        <span class="input-group-addon bg-white border-0 cursor-pointer" id="showPass"><i class="fa fa-eye-slash"></i></span>
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" id="btn_submit" class="btn btn-rounded btn-block btn-success p-10"><i class="icon-user"></i> Tambah Member</button>
                    {{-- <p class="text-center">By creating an account, you agree to Wasty <span class="text-success">Term of use</span> and <span class="text-success">Privacy Police</span></p> --}}
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
                $(this).html('<i class="fa fa-eye"></i>');
            }else{
                passInput.attr('type','password');
                $(this).html('<i class="fa fa-eye-slash"></i>');
            }
        });
    </script>
@endsection
