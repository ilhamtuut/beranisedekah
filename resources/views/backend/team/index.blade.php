@extends('layouts.backend',['page'=>'member'])
@section('title')
    <h3 class="breadcrumb-header"><i class="icon-users"></i> Member</h3>
@endsection
@section('content')
<div class="row mb-3">
    <div class="col-lg-8"></div>
    <div class="col-lg-4">
        <form action="{{ route('team.index') }}" method="get" id="form-search">
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
                @forelse ($childs as $item)
                    <li class="timeline-item">
                        <div class="panel panel-white p-10 box-shadow">
                            <div class="panel-body">
                                <div class="timeline-item-header">
                                    <div class="pull-left" style="width: 80%;">
                                        <img src="{{ $item->picture ? asset('images/picture/'.$item->picture) : asset('images/user.png')}}">
                                        <p><b class="text-success">{{ucwords($item->name)}} ({{$item->username}})</b> <br> Member {{$item->childs()->count()}}</p>
                                        <p>
                                            @php
                                                $level = 0;
                                            @endphp
                                            @if ($item->hasRank)
                                                @php
                                                    $level = $item->hasRank->level->level;
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
                                    </div>
                                    <div class="pull-right text-center">
                                        <a href="tel:{{$item->phone_number}}" class="btn btn-social btn-success">
                                            <i style="font-size: 20px !important;" class="fa fa-phone"></i>
                                        </a>
                                        <br><span class="text-success">Live Call</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="timeline-item text-center">Data tidak ditemukan</li>
                @endforelse
            </ul>
        </div>
        <div class="text-center">
            {!! $childs->render() !!}
        </div>
    </div>
</div>
@endsection
