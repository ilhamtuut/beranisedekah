@extends('layouts.backend',['page'=>'term_of_condition'])

@section('title')
    <h3 class="breadcrumb-header"><i class="fa fa-file-text"></i> Aturan Sedekah</h3>
@endsection

@section('content')
    <div class="col-12">
        <!-- Ibox -->
        <div class="panel panel-white">
            <!-- Ibox Content -->
            <div class="panel-body">
                {!!$data->description!!}
            </div>
        </div>
    </div>
@endsection

