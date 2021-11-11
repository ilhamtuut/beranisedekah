@extends('layouts.backend',['active'=>'account','page'=>'setting'])

@section('title')
    <h3 class="breadcrumb-header">Akun Perusahaan</h3>
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
                          <th>Tipe Pembayaran</th>
                          <th>Nama</th>
                          <th>Nomor</th>
                          <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($data->count()>0)
                          @foreach ($data as $key => $h)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{$h->payment_method->name}}</td>
                                <td>{{$h->account_name}}</td>
                                <td>{{$h->account_number}}</td>
                                <td class="text-center">
                                    <button class="badge call_modal" data-id="{{$h->id}}" data-payment_method_id="{{$h->payment_method_id}}" data-name="{{$h->account_name}}"  data-nomor="{{$h->account_number}}"data-toggle="modal" data-target="#responsive-modal" type="button">Update</button>
                                </td>
                            </tr>
                          @endforeach
                        @else
                            <tr>
                              <td colspan="5" class="text-center">No data available in table</td>
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
            <form action="{{ route('setting.update.account') }}" method="POST">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-form-label">Tipe Pembayaran</label>
                        <input id="id_package" type="text" name="id" class="form-control form-control-sm hidden" placeholder="Percent">
                        <select name="tipe" id="tipe" class="form-control">
                            <option value="">Tipe Pembayaran</option>
                            @foreach ($method as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Nama</label>
                        <input id="name" type="text" name="nama" class="form-control" placeholder="Nama">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Nomor</label>
                        <input id="nomor" type="text" onkeypress="if(isNaN( String.fromCharCode(event.keyCode) )) return false;" name="nomor" class="form-control" placeholder="Nomor">
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
            $('#tipe').val($(this).data('payment_method_id'));
            $('#name').val($(this).data('name'));
            $('#nomor').val($(this).data('nomor'));
        });
        $('#btn_submit').on('click',function () {
          $('#action').addClass('hidden');
          $('#loader').removeClass('hidden');
        });
    });
</script>
@endsection

