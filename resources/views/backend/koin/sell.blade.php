@extends('layouts.backend',['page'=>'koin'])
@section('title')
    <h3 class="breadcrumb-header"><i class="fa fa-clock-o"></i> Riwayat Transaksi Koin</h3>
@endsection
@section('content')
<div class="row mb-3">
    <div class="col-lg-8"></div>
    <div class="col-lg-4">
        <form action="{{ route('koin.history.sell') }}" method="get" id="form-search">
            <div class="form-group">
                <div style="margin-bottom:15px;" class="input-group">
                    <input name="search" class="form-control" type="date" placeholder="Search" required>
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
                        <div class="panel panel-white p-10 box-shadow">
                            <div class="panel-body">
                                <div class="timeline-item-header">
                                    <div class="pull-left" style="width: 60%;">
                                        @if ($h->buyer->id == Auth::id())
                                            <img class="img-60" src="{{ $h->seller->picture ? asset('images/picture/'.$h->seller->picture) : asset('images/user.png')}}">
                                        @else
                                            <img class="img-60" src="{{ $h->buyer->picture ? asset('images/picture/'.$h->buyer->picture) : asset('images/user.png')}}">
                                        @endif
                                        <p class="p-5"><b class="text-success">{{ $h->buyer->id == Auth::id() ? ucwords($h->seller->name) : ucwords($h->buyer->name)}}</b> <br> {{ $h->buyer->id == Auth::id() ? ucfirst($h->seller->username) : ucfirst($h->buyer->username)}}</p>
                                        <small>{{$h->created_at}}</small>
                                    </div>
                                    <div class="pull-right">
                                        <p style="margin-top: 20px;">
                                            <b class="text-{{$h->buyer->id == Auth::id() ? 'success' : 'danger'}}">{{$h->buyer->id == Auth::id() ? 'Beli' : 'Jual'}} {{number_format($h->amount,0,',','.')}} Koin</b>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="timeline-item text-center">
                        @if (request()->search)
                            <p>Data tidak ditemukan</p>
                        @else
                            <p>Belum ada data transaksi</p>
                        @endif
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
