<?php

namespace App\Http\Controllers\Auth;

use App\Models\LogIp;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\NotifLogin;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    protected function credentials(Request $request)
    {
        $field = filter_var($request->get($this->username()), FILTER_VALIDATE_EMAIL)
            ? $this->username()
            : 'username';

        return [
            $field => $request->get($this->username()),
            'password' => $request->password,
        ];
    }

    protected function authenticateds(Request $request, $user)
    {
        $previous_session = $user->session_id;
        if ($previous_session) {
            \Session::getHandler()->destroy($previous_session);
        }

        $user->session_id = \Session::getId();
        $user->save();
    }

    protected function authenticated(Request $request, $user)
    {
        $previous_session = $user->session_id;
        if ($previous_session) {
            \Session::getHandler()->destroy($previous_session);
        }

        $user->session_id = \Session::getId();
        $user->save();

        LogIp::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip()
        ]);
    }
}
