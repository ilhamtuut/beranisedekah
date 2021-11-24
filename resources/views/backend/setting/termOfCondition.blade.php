@extends('layouts.backend',['active'=>'term','page'=>'setting'])

@section('title')
    <h3 class="breadcrumb-header">Aturan Sedekah</h3>
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
                <form action="{{ route('setting.update.term') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <textarea name="aturan_sedekah" id="aturan_sedekah" class="form-control summernote" cols="30" rows="10">{{$data->description}}</textarea>
                    </div>
                    <div class="text-right" id="action">
                        <button id="btn_submit" class="btn btn-default bg-purple rounded-0" type="submit">Simpan</button>
                    </div>
                    <div class="text-center hidden" id="loader">
                        <i class="fa fa-spinner fa-spin text-purple"></i>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script type="text/javascript">
    $(function(){
        $('#btn_submit').on('click',function () {
          $('#action').addClass('hidden');
          $('#loader').removeClass('hidden');
        });

        $('.summernote').summernote({
            height: 400,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['fontsize', ['fontsize']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ],
            placeholder: 'Aturan Sedekah'
        });
    });
</script>
@endsection

