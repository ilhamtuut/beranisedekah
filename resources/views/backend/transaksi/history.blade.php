@extends('layouts.backend',['page'=>'transaksi'])
@section('title')
    <h3 class="breadcrumb-header"><i class="fa fa-clock-o"></i> Riwayat Donasi</h3>
@endsection
@section('content')
<div class="row mb-3">
    <div class="col-lg-8"></div>
    <div class="col-lg-4">
        <form action="{{ route('transaksi.history') }}" method="get" id="form-search">
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
                        <div class="panel panel-white p-10 box-shadow">
                            <div class="panel-body">
                                <div class="timeline-item-header">
                                    <div class="pull-left" style="width: 60%;">
                                        @if ($h->receiver->id == Auth::id())
                                            <img class="img-60" src="{{ $h->user->picture ? asset('images/picture/'.$h->user->picture) : asset('images/user.png')}}">
                                        @else
                                            <img class="img-60" src="{{ $h->receiver->picture ? asset('images/picture/'.$h->receiver->picture) : asset('images/user.png')}}">
                                        @endif
                                        <p class="p-5"><b class="text-success">{{ $h->receiver->id == Auth::id() ? ucwords($h->user->name) : ucwords($h->receiver->name)}}</b> <br> {{ $h->receiver->id == Auth::id() ? ucfirst($h->user->username) : ucfirst($h->receiver->username)}}</p>
                                        <small>{{$h->created_at}}</small>
                                    </div>
                                    <div class="pull-right">
                                        <p style="margin-top: 10px;" class="text-right">
                                            <b class="text-{{$h->receiver->id == Auth::id() ? 'success' : 'danger'}}">{{$h->receiver->id == Auth::id() ? '+' : '-'}}Rp{{number_format($h->amount,0,',','.')}}</b><br>
                                            @if ($h->status == 0 || $h->status == 1)
                                                <span class="badge bg-warning text-white">Tertunda</span>
                                            @elseif ($h->status == 2)
                                                <span class="badge bg-success text-white">Berhasil</span>
                                            @else
                                                <span class="badge bg-danger text-white">Gagal</span>
                                            @endif
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
@endsection
