@extends('layouts.backend',['active'=>'method','page'=>'setting'])

@section('title')
    <h3 class="breadcrumb-header">Tipe Pembayaran</h3>
@endsection

@section('content')
  <div class="col-12">
    @include('layouts.partials.alert')
  </div>
  <div class="col-12">
    <!-- Ibox -->
    <div class="card">
        <!-- Ibox Content -->
        <div class="card-body">
            <!-- Table Responsive -->
            <button class="badge call_modal" data-id="" data-name="" data-toggle="modal" data-target="#responsive-modal" type="button">Tambah</button>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                          <th width="3%">No</th>
                          <th>Nama</th>
                          <th>Logo</th>
                          <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($data->count()>0)
                          @foreach ($data as $key => $h)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{$h->name}}</td>
                                <td>
                                    @if ($h->logo)
                                        <img height="30px" src="{{asset('images/logo/'.$h->logo)}}" alt="">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a class="badge bg-info call_modal" data-id="{{$h->id}}" data-name="{{$h->name}}" data-toggle="modal" data-target="#responsive-modal" type="button">Update</a>
                                    <a href="{{route('setting.method.delete',$h->id)}}" class="badge bg-danger">Hapus</a>
                                </td>
                            </tr>
                          @endforeach
                        @else
                            <tr>
                              <td colspan="3" class="text-center">No data available in table</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
  </div>

  <div class="modal fade" id="responsive-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="responsive-modal">Add/Update Data</h4>
            </div>
            <form action="{{ route('setting.update.method') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-form-label">Nama</label>
                        <input id="percent" type="text" name="nama" class="form-control" placeholder="Nama">
                        <input id="id_package" type="text" name="id" class="form-control form-control-sm hidden" placeholder="Nama">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Logo</label>
                        <input type="file" name="logo" class="form-control">
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
            $('#id_package').val($(this).data('id'));
            $('#percent').val($(this).data('name'));
        });
        $('#btn_submit').on('click',function () {
          $('#action').addClass('hidden');
          $('#loader').removeClass('hidden');
        });
    });
</script>
@endsection

