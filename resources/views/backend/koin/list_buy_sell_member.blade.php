@extends('layouts.backend',['page'=>'transaksi','active'=>'buy_sell'])
@section('title')
    <h3 class="breadcrumb-header"><i class="fa fa-clock-o"></i> Daftar Jual/Beli Koin Member</h3>
@endsection
@section('content')
<div class="row mb-3">
    <form action="{{ route('koin.list.buy_sell') }}" method="get" id="form-search">
        <div class="col-lg-4">
            <div class="form-group">
                <div style="margin-bottom:15px;">
                    <input name="from_date" class="form-control" type="date" placeholder="Dari Tanggal" >
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <div style="margin-bottom:15px;">
                    <input name="to_date" class="form-control" type="date" placeholder="Sampai Tanggal" >
                </div>
            </div>
        </div>
        <div class="col-lg-4">
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
                        <th>Nama Penjual</th>
                        <th>Nama Pembeli</th>
                        <th class="text-right">Jumlah Koin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $key => $h)
                        <tr>
                            <td>{{++$i}}</td>
                            <td>{{$h->created_at}}</td>
                            <td>{{$h->seller->name}}</td>
                            <td>{{$h->buyer->name}}</td>
                            <td class="text-right">{{number_format($h->amount,0,',','.')}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No data available in table</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right">Total</td>
                        <td class="text-right">{{$total}}</td>
                    </tr>
                </tfoot>
            </table>
            {{$data->appends(['from_date'=>request()->from_date,'to_date'=>request()->to_date,'search'=>request()->search])->render()}}
        </div>
    </div>
</div>
@endsection
