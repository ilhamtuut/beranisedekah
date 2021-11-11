@extends('layouts.backend',['page'=>'bank','active'=>'bank'])

@section('content')
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6 text-center">
            <h4 class="m-b-lg">Rekening</h4>
            <div class="text-left">
                @include('layouts.partials.alert')
            </div>
            <form action="{{route('user.bank.save')}}" method="POST" id="form">
                @csrf
                <div class="form-group">
                    <div class="input-group box-shadow">
                        <span class="input-group-addon bg-white border-0"><i class="fa fa-bank"></i></span>
                        <input id="bank" name="bank" value="{{ucfirst(Auth::user()->account_bank_name)}}" type="text" class="form-control bg-white border-0 form-box p-20" placeholder="Bank" aria-describedby="bank">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group box-shadow">
                        <span class="input-group-addon bg-white border-0"><i class="icon-account_balance_wallet"></i></span>
                        <input id="nomor_rekening" name="nomor_rekening" value="{{ucfirst(Auth::user()->account_number)}}" type="text" class="form-control border-0 form-box p-20" placeholder="Nomor Rekening" aria-describedby="nomor_rekening">
                    </div>
                </div>
                <div class="form-group m-b-lg">
                    <div class="input-group box-shadow">
                        <span class="input-group-addon bg-white border-0"><i class="icon-user"></i></span>
                        <input id="atas_nama" name="atas_nama" type="text" value="{{ucfirst(Auth::user()->account_name)}}" class="form-control border-0 form-box p-20" placeholder="Atas Nama" aria-describedby="atas_nama">
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
    </script>
@endsection
