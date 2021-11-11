@extends('layouts.backend',['page'=>'user','active'=>'team_member'])

@section('title')
    <h3 class="breadcrumb-header">Team Member</h3>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <form action="{{ route('user.list_sponsor') }}" method="get" id="form-search">
            <div class="row">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                <div class="form-group">
                    <div class="input-group">
                        <input name="search" class="form-control" type="text" placeholder="Search Username" required>
                        <span class="input-group-btn">
                            <button type="button" onclick="submit();" class="btn btn-default bg-purple"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </div>
                </div>
            </div>
        </form>
        <!-- Table Responsive -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th width="3%">#</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th class="text-center">Total Member</th>
                </tr>
                </thead>
                <tbody>
                    @if($data->count()>0)
                        @foreach ($data as $h)
                            <tr>
                            <td>{{++$i}}</td>
                            <td><a class="text-success" href="{{route('user.list_donwline_user',$h->id)}}">{{ucfirst($h->username)}}</a></td>
                            <td>{{ucfirst($h->username)}}</td>
                            <td>{{$h->email}}</td>
                            <td>{{$h->phone_number}}</td>
                            <td class="text-center">{{number_format($h->childs()->count())}}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center">No data available in table</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="text-center">
            {!! $data->render() !!}
            </div>
        </div>
    </div>
</div>
@endsection
