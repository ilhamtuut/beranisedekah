@extends('layouts.backend',['page'=>'user','active'=>'koin_member'])

@section('title')
    <h3 class="breadcrumb-header">Koin Member</h3>
@endsection

@section('content')
<div class="col-12">
    @include('layouts.partials.alert')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('balance.index') }}" method="get" id="form-search">
                <div class="row">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="input-group">
                            <input name="search" class="form-control" type="text" placeholder="Search">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
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
                          <th width="3%">No</th>
                          <th>Nama</th>
                          <th>Username</th>
                          <th class="text-right">Jumlah Koin</th>
                          <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                      	@forelse ($data as $value)
                            <tr>
                              <td>{{++$i}}</td>
                              <td>{{ucfirst($value->user->name)}}</td>
                              <td>{{ucfirst($value->user->username)}}</td>
                              <td class="text-right">{{number_format($value->balance,0)}}</td>
                              <td class="text-center">
                                <a href="{{ route('balance.wallet_member',[ strtolower($value->description),$value->user_id]) }}" class="badge bg-info">Riwayat</a>
                                <a href="#" data-target="#responsive-modal" data-toggle="modal" class="badge bg-success call_modal" data-id="{{$value->id}}" data-title="Penambahan Koin" data-type="tambah">Tambah Koin</a>
                                <a href="#" data-target="#responsive-modal" data-toggle="modal" class="badge bg-danger call_modal" data-id="{{$value->id}}" data-title="Pengurangan Koin" data-type="kurang">Kurangi Koin</a>
                              </td>
                            </tr>
                        @empty
                          <tr>
                            <td colspan="4" class="text-center">No data available in table</td>
                          </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{$data->render()}}
        </div>
    </div>
</div>

<div class="modal fade" id="responsive-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="title-modal"></h4>
            </div>
            <form action="{{ route('balance.change') }}" method="POST">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-form-label">Jumlah Koin</label>
                        <input id="id" type="text" name="id" class="form-control form-control-sm hidden">
                        <input id="tipe" type="text" name="tipe" class="form-control form-control-sm hidden">
                        <input id="jumlah" type="text" onkeypress="if(isNaN( String.fromCharCode(event.keyCode) )) return false;" name="jumlah" class="form-control" placeholder="Jumlah Koin">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Keterangan</label>
                        <input id="keterangan" type="text" name="keterangan" class="form-control" placeholder="Keterangan">
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
@endsection
@section('script')
<script type="text/javascript">
    $(function(){
        $('.call_modal').on('click',function(){
            $('#id').val($(this).data('id'));
            $('#tipe').val($(this).data('type'));
            $('#title-modal').html($(this).data('title'));
        });
        $('#btn_submit').on('click',function () {
          $('#action').addClass('hidden');
          $('#loader').removeClass('hidden');
        });
    });
</script>
@endsection
