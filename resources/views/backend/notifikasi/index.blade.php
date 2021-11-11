@extends('layouts.backend',['page'=>'notifikasi'])
@section('title')
    <h3 class="breadcrumb-header"><i class="fa fa-bell-o"></i> Notifikasi</h3>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="profile-timeline">
            <ul class="list-unstyled">
                @forelse ($data as $key => $h)
                    <li class="timeline-item">
                        <div class="panel panel-white p-10 box-shadow">
                            <div class="panel-body">
                                <div class="timeline-item-header">
                                    <img class="img-60" src="{{ $h->user->picture ? asset('images/picture/'.$h->user->picture) : asset('images/user.png')}}">
                                    <p><b class="text-success">{{$h->user->name}}</b></p>
                                    <p>{{$h->message}}</p>
                                    <small>{{$h->created_at}}</small>
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="timeline-item text-center">
                        <p>Belum ada data transaksi</p>
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
