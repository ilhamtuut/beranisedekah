@extends('layouts.backend',['page'=>'transaksi'])

@section('content')
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6 text-center">
        <h4 class="m-b-0">Saling Membantu Berikan</h4>
        <h4 class="m-b-lg">Donasi ke Member Lain</h4>
        <div class="text-left">
            @include('layouts.partials.alert')
        </div>
        <div class="profile-timeline">
            <ul class="list-unstyled">
                <li class="timeline-item">
                    <div class="panel panel-white box-shadow">
                        <div class="panel-body">
                            <div class="timeline-item-header">
                                <img src="{{ $donation->receiver->picture ? asset('images/picture/'.$donation->receiver->picture) : asset('images/user.png')}}" class="img-60 mb-2" style="float: initial; margin-right:0px;" alt="">
                                <p>
                                    <b class="text-success">{{ucwords($donation->receiver->name)}}</b> <br> {{ucwords($donation->receiver->username)}} <br>
                                    @php
                                        $level = 0;
                                    @endphp
                                    @if ($donation->receiver->hasRank)
                                        @php
                                            $level = $donation->receiver->hasRank->level->level;
                                        @endphp
                                    @endif
                                    @for ($i = 0; $i < 4; $i++)
                                        @if ($i < $level)
                                            <i class="text-warning fa fa-star"></i>
                                        @else
                                            <i class="text-warning fa fa-star-o"></i>
                                        @endif
                                    @endfor
                                </p>
                                <p>Transfer ke Akun :</p>
                                <small>{{$donation->receiver->account_bank_name}} - {{$donation->receiver->account_number}} - {{ucwords($donation->receiver->account_name)}}</small>
                                <h4 class="text-success"><b>+Rp{{number_format($donation->amount,0,',','.')}}</b></h4>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        @if($donation->status == 0)
            <form action="{{route('transaksi.sendDonation',$donation->id)}}" method="POST" id="form">
                @csrf
                <div class="form-group">
                    <div class="input-group box-shadow">
                        <span class="input-group-addon bg-white border-0"><i class="fa fa-bank"></i></span>
                        <input id="bank" name="bank" type="text" class="form-control bg-white border-0 form-box p-20" placeholder="Bank" aria-describedby="bank">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group box-shadow">
                        <span class="input-group-addon bg-white border-0"><i class="icon-account_balance_wallet"></i></span>
                        <input id="nomor_rekening" name="nomor_rekening" type="text" class="form-control border-0 form-box p-20" placeholder="Nomor Rekening" aria-describedby="nomor_rekening">
                    </div>
                </div>
                <div class="form-group m-b-lg">
                    <div class="input-group box-shadow">
                        <span class="input-group-addon bg-white border-0"><i class="icon-user"></i></span>
                        <input id="atas_nama" name="atas_nama" type="text" class="form-control border-0 form-box p-20" placeholder="Atas Nama" aria-describedby="atas_nama">
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" id="btn_submit" class="btn btn-rounded btn-block btn-danger p-10">Transfer</button>
                </div>
            </form>
        @else
            <div class="alert alert-success">
                <p>Mohon menunggu konfirmasi dari <b>{{ucwords($donation->receiver->name)}}</b> untuk bisa melakukan donasi selanjutnya. <br> Hubungi penerima <a class="text-success" href="tel:{{$donation->receiver->phone_number}}"><b><i class="fa fa-phone"></i> {{($donation->receiver->phone_number)}} - {{ucwords($donation->receiver->name)}}</b></a></p>
            </div>
        @endif
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
