@extends('layouts.backend',['page'=>'user','active'=>$role])
@section('title')
    <h3 class="breadcrumb-header">Daftar {{ucfirst($role)}}</h3>
@endsection
@section('header')

@section('content')
<div class="row">
    <div class="col-md-12">
        @include('layouts.partials.alert')
        <!-- Ibox -->
        <div class="card">
            <!-- Ibox Content -->
            <div class="card-body">
                <form action="{{ route('user.list',$role) }}" method="get" id="form-search">
                    <div class="row">
                    <div class="col-lg-6"></div>
                    <div class="col-lg-3">
                        <select id="status" name="status" class="form-control">
                        <option value="">Choose Status</option>
                        <option @if(request()->status == 1) selected @endif value="1">Active</option>
                        <option @if(request()->status == 2) selected @endif value="2">Suspend</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="input-group">
                                <input name="search" class="form-control" type="text" placeholder="Search">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-search"></i></button>
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
                            <th>Level</th>
                            <th class="text-center">Prioritas</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $value)
                                <tr>
                                <td>{{++$i}}</td>
                                <td>{{ucwords($value->name)}}</td>
                                <td>{{ucfirst($value->username)}}</td>
                                <td>{{$value->email}}</td>
                                <td>{{$value->phone_number}}</td>
                                <td>{{$value->hasRank ? $value->hasRank->level->name : 'Level 0'}}</td>
                                <td class="text-center">
                                    @if($value->is_priority == 1)
                                        <span class="badge p-1 bg-success">Ya</span>
                                    @elseif($value->is_priority == 0)
                                        <span class="badge p-1 bg-danger">Tidak</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($value->status == 1)
                                        <span class="badge p-1 bg-success">Aktif</span>
                                    @elseif($value->status == 2)
                                        <span class="badge p-1 bg-danger">Block</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" style="vertical-align: unset;">
                                        <button type="button" class="badge border-0 p-1 dropdown-toggle" data-toggle="dropdown" data-boundary="window" aria-expanded="false">
                                            Pilihan <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                                <a href="{{ route('user.block_unclock', $value->id) }}"><i class="fa fa-ban"></i> {{($value->status == 2)? 'UnBlock': 'Block'}}</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('user.edit', $value->id) }}"><i class="fa fa-edit"></i> Edit</a>
                                            </li>
                                            <li>
                                                <a href="#" data-target="#bd-user-modal-lg" data-toggle="modal" class="call_modal_user" data-sponsor="{{($value->parent)? $value->parent->username: '-'}}" data-username="{{$value->username}}" data-name="{{$value->name}}" data-email="{{$value->email}}" data-phone_number="{{$value->phone_number}}" data-date="{{$value->created_at}}"><i class="fa fa-info-circle"></i> Detail</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('user.list_donwline_user', $value->id) }}"><i class="fa fa-eye"></i> Downline</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('user.priority', $value->id) }}"><i class="fa fa-check"></i> {{($value->is_priority)? 'Hapus Prioritas': 'Prioritas'}}</a>
                                            </li>
                                            <li>
                                                <a href="#" data-target="#modal-level" data-toggle="modal" class="call_modal_level" data-id="{{$value->id}}" data-name="{{ucwords($value->name)}}" data-username="{{ucfirst($value->username)}}" data-level="{{$value->hasRank ? $value->hasRank->level->level : ''}}"><i class="fa fa-signal"></i> Update Level</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="text-left">
                                        @include('backend.user.modal_detail_user', ['user' => $value])
                                    </div>
                                </td>
                                </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">No data available in table</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {!! $data->appends(['status'=>request()->status,'search'=>request()->search])->render() !!}
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-level" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="responsive-modal">Update Level</h4>
            </div>
            <form action="{{ route('user.updateLevel') }}" method="POST">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-form-label">Nama</label>
                        <input id="user_id" type="text" name="user_id" class="form-control form-control-sm hidden" placeholder="Percent">
                        <input id="name" type="text" readonly class="form-control" placeholder="Nama">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Username</label>
                        <input id="username" type="text" readonly class="form-control" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <label>Level</label>
                        <select name="level" id="level" class="form-control">
                            <option value="">Pilih Level</option>
                            @foreach ($level as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-right" id="action">
                        <button id="btn_submit" class="btn btn-default bg-purple rounded-0" type="submit">Submit</button>
                        <button type="button" class="btn btn-danger rounded-0" data-dismiss="modal">Cancel</button>
                    </div>
                    <div class="text-center hidden" id="loader">
                        <i class="fa fa-spinner fa-spin text-purple"></i>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    .dropdown-menu:before{
        left: 100px;
    }
    .dropdown-menu:after{
        left: 101px;
    }
</style>
@endsection
@section('script')
<script type="text/javascript">
    var user_id = 0;
    function submit() {
        $("#form-search").submit();
    }

    $('.call_modal_user').on('click',function(){
        $('#modal_user_sponsor').html($(this).data('sponsor'));
        $('#modal_user_username').html($(this).data('username'));
        $('#modal_user_name').html($(this).data('name'));
        $('#modal_user_email').html($(this).data('email'));
        $('#modal_user_date').html($(this).data('date'));
        $('#modal_user_phone_number').html($(this).data('phone_number'));
        $('#modal_user_bank_name').html($(this).data('bank_name'));
        $('#modal_user_account_number').html($(this).data('account_number'));
        $('#modal_user_account_name').html($(this).data('account_name'));
    });

    $('.call_modal_level').on('click',function(){
        user_id = $(this).data('id');
        $('#user_id').val(user_id);
        $('#name').val($(this).data('name'));
        $('#username').val($(this).data('username'));
        $('#level').val($(this).data('level'));
    });

    $(document).on('shown.bs.dropdown', '.table-responsive', function (e) {
        // The .dropdown container
        var $container = $(e.target);
        var $table = $('.table-responsive'),
            $menu = $container.find('.dropdown-menu'),
            tableOffsetHeight = $table.offset().top + $table.height(),
            menuOffsetHeight = $container.offset().top + $container.outerHeight(true);

        // Find the actual .dropdown-menu
        var $dropdown = $container.find('.dropdown-menu');
        if ($dropdown.length) {
            // Save a reference to it, so we can find it after we've attached it to the body
            $container.data('dropdown-menu', $dropdown);
        } else {
            $dropdown = $container.data('dropdown-menu');
        }

        $dropdown.css('top', ($container.offset().top + $container.outerHeight()) + 'px');
        $dropdown.css('left', 'unset');
        $dropdown.css('right', '30px');
        $dropdown.css('position', 'absolute');
        $dropdown.css('display', 'block');
        $dropdown.appendTo('body');
        if (menuOffsetHeight > tableOffsetHeight)
        $table.css("padding-bottom", menuOffsetHeight - tableOffsetHeight);
    });

    $(document).on('hide.bs.dropdown', '.table-responsive', function (e) {
        // Hide the dropdown menu bound to this button
        $(e.target).data('dropdown-menu').css('display', 'none');
        $(this).css("padding-bottom", 0);
    });
</script>
@endsection
