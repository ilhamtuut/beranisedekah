@extends('layouts.backend',['page'=>'user','active'=>'create'])

@section('title')
    <h3 class="breadcrumb-header">Edit Member</h3>
@endsection

@section('content')
<div class="row">
	<div class="col-md-3">
    </div>
    <div class="col-md-6">
        @include('layouts.partials.alert')
        <form class="form-horizontal form-label-left" action="{{route('user.updateData',$user->id)}}" method="POST">
            @csrf
            <div class="form-group mt-2">
                <label> Username</label>
                <input class="form-control" id="username" readonly value="{{ucfirst($user->username)}}" type="text" placeholder="User Name">
            </div>
            <div class="form-group mt-2">
                <label> Nama</label>
                <input class="form-control" id="name" name="name" value="{{ucfirst($user->name)}}" type="text" placeholder="Name">
            </div>
            <div class="form-group mt-2">
                <label> Email</label>
                <input class="form-control" id="email" name="email" value="{{$user->email}}" type="text" placeholder="Email">
            </div>
            <div class="form-group">
                <label> Telepon</label>
                <input class="form-control" id="phone_number" name="phone_number" value="{{$user->phone_number}}" type="text" placeholder="Telepon">
            </div>
            <div class="form-group">
                <label> Alamat</label>
                <input class="form-control" id="address" name="address" value="{{$user->address}}" type="text" placeholder="Alamat">
            </div>
            <div class="form-group">
                <label>Role</label>
                <select id="role" name="role" class="form-control">
                    <option value="">Choose Role</option>
                    @foreach ($roles as $role)
                        <option
                            value="{{$role->id}}"
                            @foreach ($user->roles as $r)
                                @if ($role->id == $r->id)
                                    selected
                                @endif
                            @endforeach
                        >
                            {{$role->display_name}}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input id="password" name="password" type="password" placeholder="Password" class="form-control">
            </div>

            <div class="form-group">
                <button id="btn_submit" class="btn btn-success btn-block btn-rounded" type="submit">Submit</button>
                <div class="text-center hidden" id="loader">
                    <i class="fa fa-spinner fa-spin fa-2x"></i>
                </div>
            </div>
        </form>
  	</div>
</div>
@endsection
@section('script')
    <script type="text/javascript">
    	$('#sponsor').keyup(function(e){
	        e.preventDefault();
	        if(this.value == ''){
	            $('#hdTuto_search').hide();
	        }else{
	            $.ajax({
	              type: 'GET',
	              url: '{{ route('user.get_user') }}',
	              data: {search : this.value},
	              dataType: 'json',
	              success: function(response){
	                if(response.error){
	                }else{
	                  $('#hdTuto_search').show().html(response.data);
	                }
	              }
	            });
	        }
	    });

	    $(document).on('click', '.list-gpfrm-list', function(e){
	        e.preventDefault();
	        $('#hdTuto_search').hide();
	        var fullname = $(this).data('fullname');
	        var id = $(this).data('id');
	        $('#sponsor').val(fullname);
	    });
	    $('#btn_submit').on('click',function () {
	        $(this).addClass('hidden');
	        $('#loader').removeClass('hidden');
	    });
    </script>
@endsection
