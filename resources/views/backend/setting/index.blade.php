@extends('layouts.backend',['active'=>'price','page'=>'setting'])

@section('title')
    <h3 class="breadcrumb-header">Harga Koin</h3>
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
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                          <th width="3%">No</th>
                          <th>Name</th>
                          <th class="text-right">Value</th>
                          <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($data->count()>0)
                          @foreach ($data as $key => $h)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{$h->name}}</td>
                                <td class="text-right">
                                    {{number_format($h->value,0,',','.')}} {{$h->type}}
                                </td>
                                <td class="text-center">
                                    <button class="badge call_modal" data-id="{{$h->id}}" data-percent="{{($h->type == '%')? $h->value*100 : $h->value}}" data-toggle="modal" data-target="#responsive-modal" type="button">Update</button>
                                </td>
                            </tr>
                          @endforeach
                        @else
                            <tr>
                              <td colspan="4" class="text-center">No data available in table</td>
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
                <h4 class="modal-title" id="responsive-modal">Update Data</h4>
            </div>
            <form action="{{ route('setting.update') }}" method="POST">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-form-label">Value</label>
                        <input id="percent" type="text" name="value" class="form-control" placeholder="Value">
                        <input id="id_package" type="text" name="id" class="form-control form-control-sm hidden" placeholder="Percent">
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
            $('#percent').val($(this).data('percent'));
        });
        $('#btn_submit').on('click',function () {
          $('#action').addClass('hidden');
          $('#loader').removeClass('hidden');
        });
    });
</script>
@endsection

