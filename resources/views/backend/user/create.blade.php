@extends('layouts.backend',['page'=>'user','active'=>'tambah_member'])
@section('title')
    <h3 class="breadcrumb-header">Tambah Member</h3>
@endsection
@section('content')
<div class="row">
	<div class="col-md-3"></div>
  	<div class="col-md-6">
        <form class="form-horizontal form-label-left" action="{{route('user.create')}}" method="POST">
            @csrf
            @include('layouts.partials.alert')
            <div class="form-group">
                <label class="control-label">Sponsor</label>
                <input id="sponsor" name="sponsor" class="form-control" placeholder="Sponsor" type="text">
                <ul class="list-gpfrm" id="hdTuto_search"></ul>
            </div>
            <div class="form-group mt-2">
                <label> Username</label>
                <input class="form-control" id="username" name="username" type="text" placeholder="User Name">
            </div>
            <div class="form-group mt-2">
                <label> Nama</label>
                <input class="form-control" id="name" name="name" type="text" placeholder="Nama">
            </div>
            <div class="form-group mt-2">
                <label> Email</label>
                <input class="form-control" id="email" name="email" type="text" placeholder="Email">
            </div>
            <div class="form-group">
                <label> Telepon</label>
                <input class="form-control" id="phone_number" name="phone_number" type="text" placeholder="Telepon">
            </div>
            <div class="form-group">
                <label> Alamat</label>
                <input class="form-control" id="address" name="address" type="text" placeholder="Alamat">
            </div>
            <div class="form-group">
                    <label>Role</label>
                    <select id="role" name="role" class="form-control">
                    <option value="">Choose Role</option>
                    @foreach ($roles as $role)
                        <option value="{{$role->id}}">{{$role->display_name}}</option>
                    @endforeach
                    </select>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="input-group">
                    <input id="password" name="password" type="password" placeholder="Password" class="form-control border-r-0">
                    <span class="input-group-addon bg-transparent cursor-pointer" id="showPass"><i class="fa fa-eye-slash"></i></span>
                </div>
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

        $('#showPass').on('click', function(){
            var passInput = $("#password");
            if(passInput.attr('type') == 'password'){
                passInput.attr('type','text');
                $(this).html('<i class="fa fa-eye"></i>');
            }else{
                passInput.attr('type','password');
                $(this).html('<i class="fa fa-eye-slash"></i>');
            }
        });
    </script>
@endsection
