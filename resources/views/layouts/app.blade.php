<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ config('app.name') }}">
        <meta name="keywords" content="{{ config('app.name') }}" />

        <!-- Title -->
        <title>{{ config('app.name') }}</title>
        <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/logo/favicon.png')}}">
        <!-- Styles -->
        <link href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{asset('assets/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
        <link href="{{asset('assets/plugins/icomoon/style.css')}}" rel="stylesheet">
        <link href="{{asset('assets/plugins/uniform/css/default.css')}}" rel="stylesheet"/>
        <link href="{{asset('assets/plugins/switchery/switchery.min.css')}}" rel="stylesheet"/>

        <!-- Theme Styles -->
        <link href="{{asset('assets/css/space.min.css')}}?v={{time()}}" rel="stylesheet">
        <link href="{{asset('assets/css/custom.css')}}?v={{time()}}" rel="stylesheet">
    </head>
    <body>
        <div class="page-container">
            <div class="page-content bg-white-smoke" style="width: 100%;">
                <div class="box-login text-center">
                    <img height="150px" src="{{asset('images/logo/bg-login.png')}}" alt="">
                </div>
                <div class="header-login text-center">
                    <span>{{(Route::current()->getName() == 'login') ? 'Masuk' : ((Route::current()->getName() == 'password.reset') ? 'Reset Password' : ' Lupa Password')}}</span>
                </div>
                <div class="max-w400 center">
                    <div class="p">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>

        <!-- Javascripts -->
        <script src="{{asset('assets/plugins/jquery/jquery-3.1.0.min.js')}}"></script>
        <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
        <script src="{{asset('assets/plugins/uniform/js/jquery.uniform.standalone.js')}}"></script>
        <script src="{{asset('assets/plugins/switchery/switchery.min.js')}}"></script>
        <script src="{{asset('assets/js/space.min.js')}}"></script>
        @yield('script')
    </body>
</html>
