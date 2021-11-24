@extends('layouts.backend',['page'=>'koin'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-white stats-widget box-shadow">
            <div class="panel-body">
                <div class="pull-left">
                    <p class="stats-info">Total Koin</p>
                    <span class="stats-number">{{$total_koin}}</span>
                    <p class="stats-info"><a href="{{route('balance.wallet','koin')}}" class="text-primary">Riwayat Koin</a></p>
                </div>
            </div>
        </div>
        @include('layouts.partials.alert')
    </div>
    <div class="col-md-6 text-center">
        <h4 class="m-b-lg">Beli Koin 1 Koin Rp{{number_format($price,0,'.',',')}}</h4>
        <form action="{{route('koin.buy')}}" method="POST" id="form-buy">
            @csrf
            <div class="form-group">
                <div class="input-group box-shadow">
                    <span class="input-group-addon bg-white border-0"><i class="fa fa-circle"></i></span>
                    <input id="amount" name="jumlah_koin" type="text" onkeypress="if(isNaN( String.fromCharCode(event.keyCode) )) return false;" class="form-control border-0 form-box p-20" placeholder="Jumlah Koin" aria-describedby="jumlah_koin">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group box-shadow">
                    <span class="input-group-addon bg-white border-0"><i class="fa fa-circle"></i></span>
                    <input id="total" readonly type="text" class="form-control bg-white border-0 form-box p-20" placeholder="Total Pembayaran" aria-describedby="total">
                </div>
            </div>
            <div class="form-group m-b-lg">
                <div class="input-group box-shadow">
                    <span class="input-group-addon bg-white border-0"><i class="fa fa-bank"></i></span>
                    <select name="tipe_pembayaran" id="tipe_pembayaran" style="height:40px;" class="form-control border-0 form-box">
                        <option value="">Pilih Metode Pembayaran</option>
                        @foreach ($type as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <button type="button" id="btn_buy" class="btn btn-rounded btn-block btn-success p-10 mb-2">Beli Koin</button>
                <a class="text-success" href="{{ route('koin.history.buy') }}">Riwayat Pembelian</a>
            </div>
        </form>
    </div>
    <div class="col-md-6 text-center">
        <h4 class="m-b-lg">Jual Koin ke Member Lain</h4>
        <form action="{{route('koin.sell')}}" method="POST" id="form-sell">
            @csrf
            <div class="form-group">
                <div class="input-group box-shadow">
                    <span class="input-group-addon bg-white border-0"><i class="fa fa-circle"></i></span>
                    <input id="amount" name="jumlah_koin" type="text" class="form-control border-0 form-box p-20" onkeypress="if(isNaN( String.fromCharCode(event.keyCode) )) return false;" placeholder="Jumlah Koin" aria-describedby="jumlah_koin">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group box-shadow">
                    <span class="input-group-addon bg-white border-0"><i class="icon-user"></i></span>
                    <input id="username" name="username" type="text" class="form-control border-0 form-box p-20" placeholder="Username Pembeli" aria-describedby="jumlah_koin">
                </div>
            </div>
            <div class="form-group m-b-lg">
                <div class="input-group box-shadow">
                    <span class="input-group-addon bg-white border-0"><i class="fa fa-lock"></i></span>
                    <input id="password" name="kata_sandi" type="password" class="form-control border-0 form-box p-20" placeholder="Kata Sandi Anda" aria-describedby="password">
                    <span class="input-group-addon bg-white border-0 cursor-pointer" id="showPass"><i class="fa fa-eye-slash"></i></span>
                </div>
            </div>
            <div class="form-group">
                <button type="button" id="btn_sell" class="btn btn-rounded btn-block btn-danger p-10 mb-2">Jual Koin</button>
                <a class="text-success" href="{{ route('koin.history.sell') }}">Riwayat Transaksi Koin</a>
            </div>
        </form>
    </div>
</div>
@if (Session::has('flash_buy'))
    <div class="modal fade" id="myModalInfo" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body text-center min-h-50v min-w-500 mb-5">
                    <p class="mb-5">
                        <i class="text-success fa fa-info-circle fa-5x"></i>
                    </p>
                    <h4>Pembelian Koin Sebanyak</h4>
                    <h2 class="mb-4">{{number_format(Session::get('data')->amount,0,',','.')}} Koin : Rp{{number_format(Session::get('data')->total,0,',','.')}}</h2>
                    <h4>Lanjutkan Pembayaran</h4>
                    <img height="50px" src="{{asset('images/logo/'.Session::get('data')->method->logo)}}" alt="">
                    <h4 class="mb-5">{{Session::get('data')->method->name}} - {{Session::get('data')->account_name}} - {{Session::get('data')->account_number}}</h4>
                    @foreach ($contact as $item)
                        @if ($item->name == 'Whatsapp')
                            <a href="https://api.whatsapp.com/send/?phone={{'+62'.ltrim($item->value,'0')}}&text=Saya Mau Konfirmasi Pembayaran Koin" class="btn btn-success btn-rounded btn-block mb-2">
                                <i class="fa fa-whatsapp"></i> Konfirmasi Pembayaran
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

@endsection
@section('script')
    <script type="text/javascript">
        @if (Session::has('flash_buy'))
            $('#myModalInfo').modal('show');
        @endif
        var price = {{$price}};
        $('#btn_buy').on('click',function () {
            $(this).attr('disabled','disabled');
            $(this).append('<i class="fa fa-spinner fa-spin m-l-xs"></i>');
            $('#form-buy').submit();
	    });

        $('#btn_sell').on('click',function () {
            $(this).attr('disabled','disabled');
            $(this).append('<i class="fa fa-spinner fa-spin m-l-xs"></i>');
            $('#form-sell').submit();
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

        $('#amount').on('keyup', function(){
            var value =  $(this).val();
            var total = value * price;
            $('#total').val(addCommas(total));
        });
    </script>
@endsection
