@extends('layouts.backend',['page'=>'transaksi','active'=>'list_buy'])
@section('title')
    <h3 class="breadcrumb-header"><i class="fa fa-clock-o"></i> Daftar Pembelian Koin</h3>
@endsection
@section('content')
<div class="row mb-3">
    <form action="{{ route('koin.list.buy') }}" method="get" id="form-search">
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
                        <th>Nama</th>
                        <th class="text-left">Tipe Pembayaran</th>
                        <th class="text-left">Kirim ke Akun</th>
                        <th class="text-right">Jumlah Koin</th>
                        <th class="text-right">Harga(Rp)</th>
                        <th class="text-right">Total(Rp)</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $key => $h)
                        <tr>
                            <td>{{++$i}}</td>
                            <td>{{$h->created_at}}</td>
                            <td>{{$h->user->name}}</td>
                            <td class="text-left">{{$h->method->name}}</td>
                            <td class="text-left">{{$h->account_name}} - {{$h->account_number}}</td>
                            <td class="text-right">{{number_format($h->amount,0,',','.')}}</td>
                            <td class="text-right">{{number_format($h->price,0,',','.')}}</td>
                            <td class="text-right">{{number_format($h->total,0,',','.')}}</td>
                            <td class="text-center">
                                @if ($h->status == 0)
                                    <span class="badge bg-warning">Tertunda</span>
                                @elseif ($h->status == 1)
                                    <span class="badge bg-success">Berhasil</span>
                                @else
                                    <span class="badge bg-danger">Gagal</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($h->status == 0)
                                    <span class="cursor-pointer badge bg-success call_modal" data-id="{{$h->id}}" data-name="{{$h->user->name}}" data-amount="{{number_format($h->amount,0,',','.')}}" data-total="{{number_format($h->total,0,',','.')}}" data-type="confirm" data-toggle="modal" data-target="#confirm-modal">Konfirmasi</span>
                                    <span class="cursor-pointer badge bg-danger call_modal" data-id="{{$h->id}}" data-name="{{$h->user->name}}" data-amount="{{number_format($h->amount,0,',','.')}}" data-total="{{number_format($h->total,0,',','.')}}" data-type="reject" data-toggle="modal" data-target="#confirm-modal">Tolak</span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No data available in table</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-right">Total</td>
                        <td class="text-right">{{$amount}}</td>
                        <td></td>
                        <td class="text-right">{{$total}}</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            {{$data->appends(['from_date'=>request()->from_date,'to_date'=>request()->to_date,'status'=>request()->status,'search'=>request()->search])->render()}}
        </div>
    </div>
</div>
<div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="mySmallModalLabel">Peringatan</h4>
            </div>
            <div class="modal-body text-center">
                <h4>Apakah anda yakin?</h4>
                <p id="title">Mengkonfirmasi pembelian koin ini.</p>
                <p class="mb-0">Nama : <span id="name"></span></p>
                <p class="mb-0">Jumlah Koin : <span id="amount"></span></p>
                <p class="mb-0">Total Pembayaran : <span id="total"></span></p>
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
        item_id = $(this).data('id');
        type = $(this).data('type');
        $('#title').html('Mengkonfirmasi pembelian koin ini.');
        if(type == 'reject'){
            $('#title').html('Membatalkan pembelian koin ini.');
        }
        $('#name').html($(this).data('name'));
        $('#amount').html($(this).data('amount'));
        $('#total').html('Rp'+$(this).data('total'));
    });

    function confirmAction(){
        var url = '{{ url('koin/accept') }}/'+type+'/'+item_id;
        location.href = url;
    }
</script>
@endsection
