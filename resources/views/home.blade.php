@extends('layouts.backend',['page'=>'home'])

@section('content')
@role('member')
    <a href="{{route('transaksi.donasi')}}" class="btn btn-success btn-block mb-4">Lakukan donasi untuk kenaikan level Anda</a>
@endrole
<div class="row">
    <div class="col-lg-6 col-md-6">
        <div class="panel panel-white stats-widget box-shadow">
            <div class="panel-body">
                <div class="pull-left">
                    <p class="stats-info">Total Koin</p>
                    <span class="stats-number">{{$total_koin}}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <div class="panel panel-white stats-widget box-shadow">
            <div class="panel-body">
                <div class="pull-left">
                    <p class="stats-info">Total Member</p>
                    <span class="stats-number">{{$total_member}} Member</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <div class="panel panel-white stats-widget box-shadow">
            <div class="panel-body">
                <div class="pull-left">
                    <p class="stats-info">Total Pendapatan</p>
                    <span class="stats-number"> Rp {{$total_in}}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <div class="panel panel-white stats-widget box-shadow">
            <div class="panel-body">
                <div class="pull-left">
                    <p class="stats-info">Total Pengeluaran</p>
                    <span class="stats-number"> Rp {{$total_out}}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
