@extends('layouts.backend',['page'=>'balance','active'=>'history'])
@section('title')
    <h3 class="breadcrumb-header">Riwayat Koin @if($username) Member [{{ucfirst($username)}}] @endif</h3>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        @if($id)
            <form action="{{ route('balance.wallet_member',[str_replace(" ", "_", $wallet),$id]) }}" method="get" id="form-search">
        @else
            <form action="{{ route('balance.wallet', str_replace(" ", "_", $wallet)) }}" method="get" id="form-search">
        @endif
            <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                <input name="from_date" class="form-control" type="date" placeholder="From Date">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group">
                        <input name="to_date" class="form-control" type="date" placeholder="To Date">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-default bg-purple"><i class="fa fa-search"></i></button>
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
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th class="text-center">Tipe</th>
                        <th class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $value)
                        <tr>
                            <td>{{++$i}}</td>
                            <td>{{date('d F Y H:i:s', strtotime($value->created_at))}}</td>
                            <td>{{$value->description}}</td>
                            <td class="text-center">
                                @if($value->type == 'IN')
                                    <span class="badge p-1 bg-success">IN</span>
                                @else
                                    <span class="badge p-1 bg-danger">OUT</span>
                                @endif
                            </td>
                            <td class="text-right">{{number_format($value->amount,0,',','.')}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No data available in table</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">Total</td>
                        <td class="text-right">{{number_format($total,0,',','.')}}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        {{$data->render()}}
    </div>
</div>
@endsection
