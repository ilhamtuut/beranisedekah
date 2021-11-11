<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('block-user');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $total_koin = number_format($user->balance()->where('description','Koin')->first()->balance,0);
        $total_member = $user->childs()->count();
        $total_in = number_format($user->total_in(),0,',','.');
        $total_out = number_format($user->total_out(),0,',','.');
        return view('home',compact('total_koin','total_member','total_in','total_out'));
    }
}
