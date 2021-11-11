@extends('layouts.backend',['page'=>'transaksi'])
@section('title')
    <h3 class="breadcrumb-header"><i class="fa fa-check-circle"></i> Konfirmasi Donasi</h3>
@endsection
@section('content')
<div class="row mb-3">
    <div class="col-lg-8"></div>
    <div class="col-lg-4">
        <form action="{{ route('transaksi.confirm') }}" method="get" id="form-search">
            <div class="form-group">
                <div style="margin-bottom:15px;" class="input-group">
                    <input name="search" class="form-control" type="text" placeholder="Search" required>
                    <span class="input-group-btn">
                        <button class="btn btn-default" onclick="submit();" type="button"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="profile-timeline">
            <ul class="list-unstyled">
                @forelse ($data as $key => $h)
                    <li class="timeline-item">
                        <div class="panel panel-white box-shadow">
                            <div class="panel-body">
                                <div class="timeline-item-header">
                                    <img src="{{ $h->user->picture ? asset('images/picture/'.$h->user->picture) : asset('images/user.png')}}" class="img-60 mb-2" style="float: initial; margin-right:0px;" alt="">
                                    <p>
                                        <b class="text-success">{{ ucwords($h->user->name) }}</b> <br> {{ ucwords($h->user->username) }} <br>
                                        @php
                                            $level = 0;
                                        @endphp
                                        @if ($h->user->hasRank)
                                            @php
                                                $level = $h->user->hasRank->level->level;
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
                                    <small>{{$h->account_bank_name}} - {{$h->account_number}} - {{$h->account_name}}</small> <br>
                                    <small>{{$h->created_at}}</small>
                                    <h4 class="text-success"><b>+Rp{{ number_format($h->amount,0,',','.') }}</b></h4>
                                    <button type="button" class="btn btn-success-gradient btn-addon mb-2 call_modal"
                                        data-id="{{$h->id}}"
                                        data-name="{{ucwords($h->user->name)}}"
                                        data-username="{{ucwords($h->user->username)}}"
                                        data-bank="{{$h->account_bank_name}}"
                                        data-account_number="{{$h->account_number}}"
                                        data-account_name="{{$h->account_name}}"
                                        data-receiver="{{$h->json_data}}"
                                        data-receiver_name="{{ucwords($h->receiver->name)}}"
                                        data-receiver_username="{{ucwords($h->receiver->username)}}"
                                        data-amount="{{number_format($h->amount,0,',','.')}}"
                                        data-type="confirm" data-toggle="modal" data-target="#confirm-modal"><i class="fa fa-check"></i> Terima Donasi</button>
                                    <button type="button" class="btn btn-default-gradient btn-addon mb-2 call_modal"
                                        data-id="{{$h->id}}"
                                        data-name="{{ucwords($h->user->name)}}"
                                        data-username="{{ucwords($h->user->username)}}"
                                        data-bank="{{$h->account_bank_name}}"
                                        data-account_number="{{$h->account_number}}"
                                        data-account_name="{{$h->account_name}}"
                                        data-receiver="{{$h->json_data}}"
                                        data-receiver_name="{{ucwords($h->receiver->name)}}"
                                        data-receiver_username="{{ucwords($h->receiver->username)}}"
                                        data-amount="{{number_format($h->amount,0,',','.')}}"
                                        data-type="reject" data-toggle="modal" data-target="#confirm-modal"><i class="fa fa-times"></i> Belum Terima</button>
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="timeline-item text-center">
                        @if (request()->search)
                            <p>Data tidak ditemukan</p>
                        @else
                            <p>Belum ada donasi</p>
                        @endif
                    </li>
                @endforelse
            </ul>
            <div class="text-center">
                {!! $data->render() !!}
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="mySmallModalLabel">Informasi</h4>
            </div>
            <div class="modal-body">
                <h4 class="text-center">Apakah anda yakin <span id="title"></span></h4>
                <h4 class="text-center"><b>Jumlah Donasi : Rp<span id="amount"></span></b></h4>
                <div class="row">
                    <div class="col-sm-6">
                        <h5><b>Pengirim :</b></h5>
                        <p class="mb-0">Nama : <span id="name"></span></p>
                        <p class="mb-0">Username : <span id="username"></span></p>
                        <p class="mb-0">Bank : <span id="bank"></span></p>
                        <p class="mb-0">Nomor Rekening : <span id="account_number"></span></p>
                        <p class="mb-0">Atas Nama : <span id="account_name"></span></p>
                    </div>
                    <div class="col-sm-6">
                        <h5><b>Penerima :</b></h5>
                        <p class="mb-0">Nama : <span id="receiver_name"></span></p>
                        <p class="mb-0">Username : <span id="receiver_username"></span></p>
                        <p class="mb-0">Bank : <span id="receiver_bank"></span></p>
                        <p class="mb-0">Nomor Rekening : <span id="receiver_account_number"></span></p>
                        <p class="mb-0">Atas Nama : <span id="receiver_account_name"></span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="confirmAction();">Konfirmasi</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    var item_id = 0, type = '';
    $('.call_modal').on('click', function(){
        var receiver = $(this).data('receiver');
        item_id = $(this).data('id');
        type = $(this).data('type');
        $('#title').html('sudah menerima donasi ini?');
        if(type == 'reject'){
            $('#title').html('membatalkan donasi ini?');
        }
        $('#name').html($(this).data('name'));
        $('#username').html($(this).data('username'));
        $('#bank').html($(this).data('bank'));
        $('#account_name').html($(this).data('account_name'));
        $('#account_number').html($(this).data('account_number'));
        $('#receiver_name').html($(this).data('receiver_name'));
        $('#receiver_username').html($(this).data('receiver_username'));
        $('#receiver_bank').html(receiver.receiver_account.account_bank_name);
        $('#receiver_account_number').html(receiver.receiver_account.account_number);
        $('#receiver_account_name').html(receiver.receiver_account.account_name);
        $('#amount').html($(this).data('amount'));
    });

    function confirmAction(){
        var url = '{{ url('transaksi/accept') }}/'+type+'/'+item_id;
        location.href = url;
    }
</script>
@endsection
