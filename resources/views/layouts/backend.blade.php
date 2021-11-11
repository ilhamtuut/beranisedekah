<!DOCTYPE html>
<html lang="en">
    @include('layouts.partials.htmlheader')
    <body>
        <!-- Page Container -->
        <div class="page-container">
            <!-- Page Sidebar -->
            @include('layouts.partials.sidebar')
            <!-- /Page Sidebar -->
            <!-- Page Content -->
            <div class="page-content">
                <!-- Page Header -->

                <div class="img-header">
                    <img height="150px;" src="{{asset('images/hand.png')}}">
                </div>
                <div class="page-header">
                    <div class="logo-sm">
                        <a href="javascript:void(0)" id="sidebar-toggle-button"><i class="fa fa-bars"></i></a>
                    </div>
                </div>
                <!-- /Page Header -->
                <div class="area-user">
                    <div class="user-info">
                        <p>
                            @if (Auth::user()->picture)
                                <img height="80px" width="80px" class="cursor-pointer img-circle" src="{{asset('images/picture/'.Auth::user()->picture)}}" id="change-picture">
                            @else
                                <i class="icon-account_circle font-100 cursor-pointer" id="change-picture"></i>
                            @endif
                            <form action="{{route('user.upload_foto')}}" method="post" id="upload" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="picture" accept=".png, .jpg, .jpeg" id="picture" class="hidden">
                            </form>
                        </p>
                        <b>{{ucfirst(Auth::user()->name)}}</b><br>
                        <p class="mb-0 font-weight-normal">{{(Auth::user()->hasRank) ? Auth::user()->hasRank->level->name : 'Level 0'}} <br> Member {{number_format(Auth::user()->childs()->count())}}</p>
                    </div>
                </div>
                <div class="half-circle">

                </div>
                <!-- Page Inner -->
                <div class="page-inner">
                    @error('picture')
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>Gagal!</strong> {{ $message }}
                        </div>
                    @enderror
                    <div class="page-title">
                        @yield('title')
                    </div>
                    <div id="main-wrapper">
                        @yield('content')
                        @role('member')
                            <div class="fab">
                                <span class="fab-action-button">
                                    <i style="font-size: 20px !important;" class="fa fa-share-alt"></i>
                                </span>
                                <ul class="fab-buttons">
                                    @foreach ($contact as $item)
                                        @if ($item->name == 'Whatsapp')
                                            <li class="fab-buttons__item">
                                                <a href="https://api.whatsapp.com/send/?phone={{'+62'.ltrim($item->value,'0')}}&text=Hay Admin" class="btn btn-social btn-whatsapp">
                                                    <i style="font-size: 25px !important;" class="fa fa-whatsapp"></i>
                                                </a>
                                            </li>
                                        @else
                                            <li class="fab-buttons__item">
                                                <a href="tel:{{$item->value}}" class="btn btn-social btn-rss">
                                                    <i style="font-size: 25px !important;" class="fa fa-phone"></i>
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endrole
                    </div>
                    <!-- Main Wrapper -->
                </div>
                <!-- /Page Inner -->
            </div><!-- /Page Content -->
        </div><!-- /Page Container -->

        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body text-center min-h-50v min-w-500">
                        <img src="{{asset('images/check.png')}}" height="100px" class="mb-5">
                        <h4>{{Session::get('title')}}</h4>
                        <p>{{Session::get('message')}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success-gradient btn-rounded btn-block mb-2" data-dismiss="modal"><i class="fa fa-check"></i> Selesai</button>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.partials.script')
        @yield('script')
    </body>
</html>

