@extends('layouts.backend',['page'=>'transaksi','active'=>'donasi'])
@section('title')
    <h3 class="breadcrumb-header"><i class="fa fa-clock-o"></i> Daftar Donasi</h3>
@endsection
@section('content')
<div class="row mb-3">
    <form action="{{ route('transaksi.donasi.list') }}" method="get" id="form-search">
        <div class="col-lg-3">
            <div class="form-group">
                <div style="margin-bottom:15px;">
                    <input name="from_date" class="form-control" type="date" placeholder="Dari Tanggal" >
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <div style="margin-bottom:15px;">
                    <input name="to_date" class="form-control" type="date" placeholder="Sampai Tanggal" >
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <div style="margin-bottom:15px;">
                    <select name="status" class="form-control">
                        <option value="">Pilih Status</option>
                        <option value="1" @if(request()->status == 1) selected @endif>Tertunda</option>
                        <option value="2" @if(request()->status == 2) selected @endif>Berhasil</option>
                        <option value="3" @if(request()->status == 3) selected @endif>Gagal</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <div style="margin-bottom:15px;" class="input-group">
                    <input name="search" class="form-control" value="{{request()->search}}" type="text" placeholder="Search" >
                    <span class="input-group-btn">
                        <button class="btn btn-default" onclick="submit();" type="button"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="3%">No</th>
                        <th>Tanggal</th>
                        <th>Nama Pengirim</th>
                        <th>Nama Penerima</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Jumlah Donasi (Rp)</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $key => $h)
                        <tr>
                            <td>{{++$i}}</td>
                            <td>{{$h->created_at}}</td>
                            <td>{{$h->user->name}}</td>
                            <td>{{$h->receiver->name}}</td>
                            <td class="text-center">
                                @if ($h->status == 0 || $h->status == 1)
                                    <span class="badge bg-warning">Tertunda</span>
                                @elseif ($h->status == 2)
                                    <span class="badge bg-success">Berhasil</span>
                                @else
                                    <span class="badge bg-danger">Gagal</span>
                                @endif
                            </td>
                            <td class="text-right">{{number_format($h->amount,0,',','.')}}</td>
                            <td class="text-center">
                                @if ($h->status == 0 || $h->status == 1)
                                    <span class="cursor-pointer badge bg-success call_modal"
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
                                        data-type="confirm" data-toggle="modal" data-target="#confirm-modal">Konfirmasi</span>
                                    <span class="cursor-pointer badge bg-danger call_modal"
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
                                        data-amount="{{number_format($h->amount,0,',','.')}}"
                                        data-type="reject" data-toggle="modal" data-target="#confirm-modal">Tolak</span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No data available in table</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-right">Total</td>
                        <td class="text-right">{{$total}}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            {{$data->appends(['from_date'=>request()->from_date,'to_date'=>request()->to_date,'status'=>request()->status,'search'=>request()->search])->render()}}
        </div>
    </div>
</div>
<div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="mySmallModalLabel">Peringatan</h4>
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
        $('#title').html('mengkonfirmasi donasi ini?');
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
