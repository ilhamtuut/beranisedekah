<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Response;
use App\Models\Role;
use App\Models\User;
use App\Models\Level;
use App\Models\Balance;
use App\Models\Downline;
use App\Models\UserLevel;
use Illuminate\Http\Request;
use App\Rules\IsValidEmail;
use App\Rules\IsValidPassword;
use App\Helpers\DirectDownline;
use Illuminate\Support\Facades\URL;
use App\Notifications\ResetQuestion;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index(Request $request)
    {
        $roles = Role::select('display_name','id')->orderBy('name')->get();
        if(Auth::user()->hasRole('admin')){
            $roles = Role::select('display_name','id')->where('name','!=','super_admin')->orderBy('name')->get();
        }
        return view('backend.user.create',compact('roles'));
    }

    public function profile(Request $request)
    {
        return view('backend.user.profile');
    }

    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.Auth::id(),
            'telepon' => 'required|string',
            'alamat' => 'required|string',
            'kata_sandi' => ['nullable', new isValidPassword],
            'kata_sandi_baru' => ['nullable', new isValidPassword],
        ]);

        $user = Auth::user();
        $dataUpdate = array(
            'name'=>$request->nama,
            'email'=>$request->email,
            'phone_number'=>$request->telepon,
            'address'=>$request->alamat
        );
        $password = $request->kata_sandi;
        if($password){
            $hasPassword = Hash::check($password,$user->password);
            if(!$hasPassword){
                $request->session()->flash('failed', 'Gagal, Kata Sandi Salah');
                return redirect()->back();
            }
            array_merge($dataUpdate,array('password' => Hash::make($request->kata_sandi_baru),'trx_password' => Hash::make($request->kata_sandi_baru)));
        }
        $user->update($dataUpdate);
        return redirect()->back()->with(['flash_success' => true,'title' => 'Selamat','message' => 'Akun Anda telah berhasil diperbaharui']);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'sponsor'=>'required',
            'name' => 'required|string|max:255',
            'username' => 'required|unique:users,username|alpha_num|max:17',
            'phone_number' => 'required|string',
            'address' => 'required|string',
            'email' => ['required', 'email', 'unique:users,email',new IsValidEmail],
            'role' => 'required',
            'password' => ['required', new isValidPassword],
        ]);
        $upline = User::where('username',$request->sponsor)->first();
        if($upline){
            $data = $input = $request->all();
            $user = User::create([
                'parent_id' => $upline->id,
                'name' => $data['name'],
                'username' => $data['username'],
                'phone_number' => $data['phone_number'],
                'address' => $data['address'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'trx_password' => Hash::make($data['password']),
                'status' => 1,
            ]);

            $user->roles()->attach($request->role);
            Balance::create([
                'user_id' => $user->id,
                'balance' => 0,
                'status' => 1,
                'description' => 'Koin'
            ]);

            (new DirectDownline)->add($user->id, $upline->id);
            return redirect()->back()->with(['flash_success' => true,'title' => 'Berhasil','message' => 'Menambahkan member']);
        }else{
            $request->session()->flash('failed', 'Gagal Upline tidak ditemukan');
        }
        return redirect()->back();
    }

    public function edit(Request $request, $id)
    {
        $role_user = $request->session()->get('roles');
        $roles = Role::select('display_name','id')->orderBy('name')->get();
        if(Auth::user()->hasRole('admin')){
            $roles = Role::select('display_name','id')->where('name','!=','super_admin')->orderBy('name')->get();
        }
        $user = User::find($id);
        return view('backend.user.edit', compact('user', 'roles', 'role_user'));
    }

    public function updateData(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$id,
            'address' => 'required|string',
            'role' => 'required',
            'password' => ['nullable', new isValidPassword],
        ]);

        $data = $request->except(['password']);
        if($request->password){
            $request->merge([
                'password' => Hash::make($request->password),
                'trx_password' => Hash::make($request->password)
            ]);
            $data = $request->all();
        }
        $users = User::find($id);
        $users->update($data);
        $users->roles()->sync($request->role);
        return redirect()->to('user/list/'.$request->session()->get('roles'))->with(['flash_success' => true,'title' => 'Berhasil','message' => 'Memperbaharui data profile '.$users->username]);
    }

    public function list(Request $request,$role_name)
    {
        $search = $request->search;
        $status = $request->status;
        $data = User::whereHas('roles', function ($query) use($role_name) {
                    $query->where('roles.name', $role_name);
                })
                ->when($search, function ($cari) use ($search) {
                    return $cari->where('username', 'LIKE' ,$search.'%')
                    ->orWhere('name', 'LIKE', $search.'%')
                    ->orWhere('email', 'LIKE', $search.'%');
                })
                ->when($status, function ($cari) use ($status) {
                    return $cari->where('status', $status);
                })->paginate(20);
        $role = $role_name;
        $request->session()->put('roles', $role);
        $level = Level::orderBy('level')->get();
        return view('backend.user.list', compact('data', 'role','level'))->with('i', (request()->input('page', 1) - 1) * 20);
    }

    public function getUsername(Request $request)
    {
        $results = array('error' => false, 'data' => '');
        $search = $request->search;
        if($search){
            $data = DB::table("users")
                    ->select("id","username")
                    ->whereNotIn('id',[1,2])
                    ->where('username','LIKE',"$search%")
                    ->get();
            if(count($data) > 0){
                foreach ($data as $key => $value) {
                    $results['data'] .= "
                        <li class='list-gpfrm-list' data-fullname='".ucfirst($value->username)."' data-id='".$value->id."'>".ucfirst($value->username)."</li>
                    ";
                }
            }else{
                $results['data'] = "
                    <li class='list-gpfrm-list'>No found data matches Records</li>
                ";
            }
        }else{
            $results['error'] = true;
        }
        echo json_encode($results);
    }

    public function searchUser(Request $request)
    {
        $username = $request->username;
        $user = User::select('id','username')->where('username',$username)->first();
        $results = array('error' => false, 'data' => '');
        if($user){
            $results['data'] = $user;
        }else{
            $results['error'] = true;
        }
        return Response::json($results);
    }

    public function block_unclock(Request $request,$id)
    {
        $user = User::find($id);
        $status = $user->status;
        if($status == 2){
            $block = 1;
            $msg = 'Mengaktifkan username '.$user->username;
        }else{
            $block = 2;
            $msg = 'Menonaktifkan username '.$user->username;
        }
        $user->status = $block;
        $user->save();
        return redirect()->back()->with(['flash_success' => true,'title' => 'Berhasil','message' => $msg]);
    }

    public function priority(Request $request,$id)
    {
        $user = User::find($id);
        $status = $user->is_priority;
        if($status){
            $is_priority = 0;
            $msg = 'Menonaktifkan priotitas username '.$user->username;
        }else{
            $is_priority = 1;
            $msg = 'Mengaktifkan priotitas username '.$user->username;
        }
        $user->is_priority = $is_priority;
        $user->save();
        return redirect()->back()->with(['flash_success' => true,'title' => 'Berhasil','message' => $msg]);
    }

    public function list_sponsor(Request $request)
    {
        $search = $request->search;
        $data = User::whereNotIn('id',[1])
                ->when($search,function ($cari) use ($search) {
                    return $cari->where('username', $search);
                })->paginate(20);

        return view('backend.user.list_sponsor',compact('data'))->with('i', (request()->input('page', 1) - 1) * 20);
    }

    public function list_donwline(Request $request)
    {
        $search = $request->search;
        $data = Auth::user()->childs()
            ->when($search,function ($cari) use ($search) {
                return $cari->where('username', 'LIKE', $search.'%');
            })->paginate(20);
        $id = Auth::user()->id;
        $username = Auth::user()->username;
        return view('backend.user.list_downline',compact('data','date','id','username'))->with('i', (request()->input('page', 1) - 1) * 20);
    }

    public function list_donwline_user(Request $request,$id)
    {
        $search = $request->search;
        $user = User::find($id);
        if($user){
            $username = $user->username;
            $data = User::where('parent_id',$id)
                    ->when($search,function ($cari) use ($search) {
                        return $cari->where('username', 'LIKE', $search.'%');
                    })->paginate(20);

            return view('backend.user.list_downline',compact('data','id','username'))->with('i', (request()->input('page', 1) - 1) * 20);
        }else{
            return redirect()->back();
        }
    }

    public function viewBank(Request $request)
    {
        return view('backend.user.bank');
    }

    public function saveBank(Request $request)
    {
        $this->validate($request, [
            'bank' => 'required',
            'nomor_rekening' => 'required',
            'atas_nama' => 'required'
        ]);

        $user = Auth::user();
        $user->fill([
            'account_bank_name' => $request->bank,
            'account_number' => $request->nomor_rekening,
            'account_name' => $request->atas_nama,
        ]);
        $user->save();
        return redirect()->back()->with(['flash_success' => true,'title' => 'Selamat','message' => 'Akun Rekening Anda telah berhasil diperbaharui']);
    }

    public function upload_foto(Request $request)
    {
        $this->validate($request, [
            'picture' => 'required|mimes:png,jpg,jpeg|max:2048'
        ]);

        $user = Auth::user();
        $path = 'images/picture/';
        if($user->picture){
            $dir = $path.$user->picture;
            if(\file_exists($dir)){
                unlink($dir);
            }
        }
        $file = $request->file('picture');
        $filename = time().$file->getClientOriginalName();
        $file->move($path,$filename);

        $user->fill([
            'picture' => $filename,
        ]);
        $user->save();
        return redirect()->back()->with(['flash_success' => true,'title' => 'Selamat','message' => 'Foto profile Anda telah berhasil diperbaharui']);
    }

    public function updateLevel(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'level' => 'required|exists:levels,id',
            'password' => 'required'
        ]);
        $user = Auth::user();
        $hasPassword = Hash::check($request->password, $user->password);
        if(!$hasPassword){
            return redirect()->back()->with(['failed' => 'Gagal, Kata sandi salah']);
        }
        $member = User::find($request->user_id);
        $level = UserLevel::where('user_id',$member->id)->first();
        if($level){
            $level->update(['level_id'=>$request->level]);
        }else{
            UserLevel::create([
                'user_id'=>$member->id,
                'level_id'=>$request->level,
            ]);
        }
        return redirect()->back()->with(['flash_success' => true,'title' => 'Berhasil','message' => 'Level '.ucwords($member->name).' telah berhasil diperbaharui']);
    }
}
