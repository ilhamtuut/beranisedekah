<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Balance;
use App\Models\Downline;
use Illuminate\Http\Request;
use App\Rules\IsValidEmail;
use App\Rules\IsValidPassword;
use App\Helpers\DirectDownline;
use App\Notifications\InfoRegister;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'referral' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email', new IsValidEmail],
            'address' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'password' => ['required','confirmed', new IsValidPassword],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $parent_id = null;
        $parent = User::where('username',$data['referral'])->first();
        if($parent){
            $parent_id = $parent->id;
        }
        $user = User::create([
            'parent_id' => $parent_id,
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['email'],
            'country' => $data['country'],
            'phone_number' => $data['phone_number'],
            'password' => Hash::make($data['password']),
            'trx_password' => Hash::make($data['password']),
            'email_verified_at' => now()
        ]);
        $user->attachRole('member');

        Balance::create([
            'user_id' => $user->id,
            'balance' => 0,
            'status' => 1,
            'description' => 'Koin'
        ]);

        Balance::create([
            'user_id' => $user->id,
            'balance' => 0,
            'status' => 1,
            'description' => 'Donasi'
        ]);

        if($parent_id){
            (new DirectDownline)->add($user->id, $parent_id);
        }
        $user->notify(new InfoRegister($data));
        return $user;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $user = User::where('username',$request->referral)->first();
        if($user){
                event(new Registered($user = $this->create($request->all())));
                $this->registered($request, $user)
                            ?: redirect($this->redirectPath());
                $request->session()->flash('success', 'Successfully register account, please login to account.');
                return redirect('/login');
        }else{
            $request->session()->flash('failed', 'Referal has not found.');
            return redirect()->back();
        }
    }
}
