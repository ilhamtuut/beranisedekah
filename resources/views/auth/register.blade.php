@extends('layouts.app')

@section('content')
@include('layouts.partials.alert')
<form class="#" action="{{ route('register') }}" method="POST">
    @csrf
    <div class="form-area text-left">
        <div class="form-group">
            <input name="referral" style="margin:0px;" class="form-control" type="text" @if(Session::get('ref:user:username')) readonly @endif placeholder="Referral" value="{{ Session::get('ref:user:username')}}">
            @error('referral')
                <p class="text-danger m-b-0">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group">
            <input name="username" style="margin:0px;" class="form-control" type="text" placeholder="Username" value="{{ old('username') }}">
            @error('username')
                <p class="text-danger m-b-0">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group">
            <input name="name" style="margin:0px;" class="form-control" type="text" placeholder="Name" value="{{ old('name') }}">
            @error('name')
                <p class="text-danger m-b-0">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group">
            <input name="email" style="margin:0px;" class="form-control" type="text" placeholder="Email" value="{{ old('email') }}">
            @error('email')
                <p class="text-danger m-b-0">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group">
            <select id="country" name="country" class="selectpicker" data-style="btn-select-tag" data-live-search="true" style="width: 100%;height: 36px;">
                <option value="">Choose Country</option>
            </select>
            @error('country')
                <p class="text-danger m-b-0">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group">
            <input name="phone_number" style="margin:0px;" class="form-control" type="text" placeholder="Phone Number" value="{{ old('phone_number') }}">
            @error('phone_number')
                <p class="text-danger m-b-0">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group">
            <input name="pin_authenticator" style="margin:0px;" class="form-control" type="password" placeholder="PIN Authenticator">
            @error('pin_authenticator')
                <p class="text-danger m-b-0">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group">
            <input name="password" style="margin:0px;" class="form-control" type="password" placeholder="Password">
            @error('password')
                <p class="text-danger m-b-0">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group">
            <input name="password_confirmation" style="margin:0px;" class="form-control" type="password" placeholder="Confirm Password">
            @error('password_confirmation')
                <p class="text-danger m-b-0">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <div class="col-xs-8">
            <div class="checkbox icheck">
                <label>
                <input type="checkbox">
                I Agree to the terms of use. </label>
                </div>
            </div>
            <div class="col-xs-4 m-t-1">
                <button type="submit" class="btn btn-primary btn-block btn-flat"><i class="fa fa-sign-in"></i> Sign Up</button>
            </div>
        </div>
    </div>
</form>
<div class="m-t-2">Already have an account? <a href="{{route('login')}}" class="text-center text-primary">Sign In</a></div>
@endsection
@section('script')
    <script type="text/javascript">
        load_country();
        function load_country() {
            $('#country').empty();
            var countries = [];
            $.ajax({
                type: 'GET',
                url: '{{url('countries.json')}}',
                dataType: 'json',
                success: function(data){
                    $('#country').append('<option value="">Choose Country</option>');
                    $.each(data, function(i, item) {
                        countries[i] = "<option value='" + item.country + "'>" + item.country + "</option>";
                    });
                    $('#country').append(countries);
                    $('#country').selectpicker('refresh');
                }
            });
        }
    </script>
@endsection
