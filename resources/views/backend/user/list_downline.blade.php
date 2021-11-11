@extends('layouts.backend',['active'=>'members','page'=>'user'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-8">
                <h4>
                    <i class="icon-user"></i> Daftar Downline [
                    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('super_admin'))
                        <a class="text-success" href="{{ (Auth::user()->id == $id) ? '#' : route('user.list_donwline_user',\App\Models\User::where('id',$id)->first()->parent->id) }}">{{ucfirst($username)}}</a>
                    @else
                        <a class="text-success" href="{{ (Auth::user()->id == $id) ? '#' : route('user.list_donwline_user',\App\Models\User::where('id',$id)->first()->parent->id) }}">{{ucfirst($username)}}</a>
                    @endif ]
                </h4>
            </div>
            <div class="col-md-4">
                @if($id)
                    <form action="{{ route('user.list_donwline_user',$id) }}" method="get" id="form-search">
                @else
                    <form action="{{ route('user.list_donwline') }}" method="get" id="form-search">
                @endif
                <div class="form-group">
                    <div class="input-group">
                        <input name="search" class="form-control" type="text" placeholder="Search Username" required>
                        <span class="input-group-btn">
                            <button type="button" onclick="submit();" class="btn btn-default bg-purple"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <!-- Table Responsive -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="3%">No</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th class="text-center">Date Join</th>
                        <th class="text-right">Total Member</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $value)
                        <tr>
                            <td>{{++$i}}</td>
                            <td><a class="text-success" href="{{route('user.list_donwline_user',$value->id)}}">{{ucfirst($value->username)}}</a></td>
                            <td>{{($value->name)? ucfirst($value->name) :'-'}}</td>
                            <td>{{($value->email)}}</td>
                            <td>{{($value->phone_number)? $value->phone_number :'-'}}</td>
                            <td class="text-center">{{date('d F Y H:i:s', strtotime($value->created_at))}}</td>
                            <td class="text-right">{{(number_format($value->childs()->count()))}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No data available in table</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="text-center">
                {!! $data->render() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
  function submit() {
    $("#form-search").submit();
  }
</script>
@endsection
