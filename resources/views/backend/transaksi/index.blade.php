@extends('layouts.backend',['page'=>'transaksi'])

@section('content')
<div class="row mb-3">
    <div class="col-lg-6 col-md-6">
        <div class="panel panel-white stats-widget">
            <div class="panel-body">
                <div class="pull-left">
                    <span class="stats-number">{{$total_in}}</span>
                    <p class="stats-info">Total Pemasukan</p>
                </div>
                <div class="pull-right">
                    <i class="icon-arrow_downward stats-icon"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <div class="panel panel-white stats-widget">
            <div class="panel-body">
                <div class="pull-left">
                    <span class="stats-number">{{$total_out}}</span>
                    <p class="stats-info">Total Pengeluaran</p>
                </div>
                <div class="pull-right">
                    <i class="icon-arrow_upward stats-icon"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-xs-4 text-center">
        <a href="{{route('transaksi.donasi')}}">
            <button type="button" class="btn btn-social btn-danger mb-3"><i style="font-size: 24px !important;" class="icon-arrow_upward"></i></button>
            <p>Donasi</p>
        </a>
    </div>
    <div class="col-xs-4 text-center">
        <a href="{{route('transaksi.confirm')}}">
            <button type="button" class="btn btn-social btn-success mb-3"><i style="font-size: 24px !important;" class="icon-arrow_downward"></i></button>
            <p>Konfirmasi Donasi</p>
        </a>
    </div>
    <div class="col-xs-4 text-center">
        <a href="{{route('transaksi.history')}}">
            <button type="button" class="btn btn-social btn-info mb-3"><i style="font-size: 24px !important;" class="fa fa-usd"></i></button>
            <p>Riwayat Donasi</p>
        </a>
    </div>
</div>
@endsection
