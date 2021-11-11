@extends('layouts.backend',['page'=>'koin'])
@section('title')
    <h3 class="breadcrumb-header"><i class="fa fa-clock-o"></i> Riwayat Pembelian Koin</h3>
@endsection
@section('content')
<div class="row mb-3">
    <form action="{{ route('koin.history.buy') }}" method="get" id="form-search">
        <div class="col-lg-6"></div>
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
                    <input name="search" class="form-control" type="date" placeholder="Search" required>
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
                        <th class="text-left">Tipe Pembayaran</th>
                        <th class="text-left">Kirim ke Akun</th>
                        <th class="text-right">Jumlah Koin</th>
                        <th class="text-right">Harga(Rp)</th>
                        <th class="text-right">Total(Rp)</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $key => $h)
                        <tr>
                            <td>{{++$i}}</td>
                            <td>{{$h->created_at}}</td>
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No data available in table</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{$data->appends(['status'=>request()->status,'search'=>request()->search])->render()}}
        </div>
    </div>
</div>
@endsection
