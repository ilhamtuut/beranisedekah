<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Balance;
use App\Rules\IsValidEmail;
use App\Rules\IsValidPassword;
use App\Models\TermOfCondition;
use App\Helpers\DirectDownline;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $childs = Auth::user()->childs()
                ->when($search, function ($query) use ($search){
                    $query->where('username',$search)
                        ->orWhere('name',$search);
                })->paginate(20);
        return view('backend.team.index', compact('childs'));
    }

    public function add_member()
    {
        return view('backend.team.add_member');
    }

    public function save_member(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|string|max:255',
            'username' => 'required|unique:users,username|alpha_num|max:17',
            'telepon' => 'required|string',
            'alamat' => 'required|string',
            'email' => ['required', 'email', 'unique:users,email',new IsValidEmail],
            'kata_sandi' => ['required', new isValidPassword],
        ]);
        $data = $input = $request->all();
        $user = User::create([
            'parent_id' => Auth::id(),
            'name' => $data['nama'],
            'username' => $data['username'],
            'phone_number' => $data['telepon'],
            'address' => $data['alamat'],
            'email' => $data['email'],
            'password' => Hash::make($data['kata_sandi']),
            'trx_password' => Hash::make($data['kata_sandi']),
            'status' => 1,
        ]);

        $user->roles()->attach(3);
        Balance::create([
            'user_id' => $user->id,
            'balance' => 0,
            'status' => 1,
            'description' => 'Koin'
        ]);

        (new DirectDownline)->add($user->id, Auth::id());
        return redirect()->back()->with(['flash_success' => true,'title' => 'Selamat Pendaftaran Member Baru Berhasil','message' => 'Berikan Akses User dan Kata sandi ke Member yang Anda bawa.']);
    }

    public function term_of_condition()
    {
        $data = TermOfCondition::first();
        return view('backend.team.term_of_condition',compact('data'));
    }
}
