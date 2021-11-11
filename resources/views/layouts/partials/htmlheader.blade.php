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
