<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Balance;
use App\Models\Notification;
use App\Models\HistoryTransaction;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class BalanceController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $wallet = $request->wallet;
        $from_date = str_replace('/', '-', $request->from_date);
        $to_date = str_replace('/', '-', $request->to_date);
        if($from_date && $to_date){
            $from = date('Y-m-d',strtotime($from_date));
            $to = date('Y-m-d',strtotime($to_date));
        }else{
            $from = date('Y-m-d',strtotime('01/01/2018'));
            $to = date('Y-m-d');
            $from_date = date('01/01/Y');
            $to_date = date('d/m/Y');
        }

        $data = Balance::where('user_id','!=',1)
                ->when($wallet, function ($query) use ($wallet){
                    $query->where('description',$wallet);
                })
                ->whereHas('user', function ($query) use($search) {
                    $query->where('users.username','LIKE', $search.'%');
                })
                ->whereDate('created_at','>=',$from)
                ->whereDate('created_at','<=',$to)
                ->paginate(20);
        $total = Balance::where('user_id','!=',1)
                ->when($wallet, function ($query) use ($wallet){
                    $query->where('description',$wallet);
                })
                ->whereHas('user', function ($query) use($search) {
                    $query->where('users.username','LIKE', $search.'%');
                })
                ->whereDate('created_at','>=',$from)
                ->whereDate('created_at','<=',$to)
                ->sum('balance');
        $type = Balance::select('description')->limit(3)->get();

        return view('backend.balance.index', compact('data','total','from_date','to_date','search','wallet','type'))->with('i', (request()->input('page', 1) - 1) * 20);

    }

    public function wallet(Request $request,$wallet)
    {
        $from_date = str_replace('/', '-', $request->from_date);
        $to_date = str_replace('/', '-', $request->to_date);
        if($from_date && $to_date){
            $from = date('Y-m-d',strtotime($from_date));
            $to = date('Y-m-d',strtotime($to_date));
        }else{
            // $from_date = '01/01/2018';
            $from = date('Y-m-d',strtotime('01/01/2018'));
            $to = date('Y-m-d');
            $from_date = date('01/01/Y');
            $to_date = date('d/m/Y');
        }
        $wallet = str_replace("_", " ", $wallet);
        $balance = Auth::user()->balance()->where('description',$wallet)->first();
        if(!$balance){
        	abort(404);
        }
        $data = $balance->history()
        		->whereDate('created_at','>=',$from)
                ->whereDate('created_at','<=',$to)
                ->orderBy('id','desc')
                ->paginate(20);
        $in = $balance->history()
                ->where('type','IN')
        		->whereDate('created_at','>=',$from)
                ->whereDate('created_at','<=',$to)
                ->sum('amount');
        $out = $balance->history()
                ->where('type','OUT')
        		->whereDate('created_at','>=',$from)
                ->whereDate('created_at','<=',$to)
                ->sum('amount');
        $a = number_format($in,8, '.', '');
        $b = number_format($out,8, '.', '');
        $total = $a - $b;
        $id = null;
        $username = null;
        return view('backend.balance.history', compact('data','total','from_date','to_date','id','wallet','username'))->with('i', (request()->input('page', 1) - 1) * 20);
    }

    public function wallet_member(Request $request,$wallet,$id)
    {
    	$user = User::find($id);
    	if(!$user){
    		abort(404);
    	}
        $from_date = str_replace('/', '-', $request->from_date);
        $to_date = str_replace('/', '-', $request->to_date);
        if($from_date && $to_date){
            $from = date('Y-m-d',strtotime($from_date));
            $to = date('Y-m-d',strtotime($to_date));
        }else{
            // $from_date = '01/01/2018';
            $from = date('Y-m-d',strtotime('01/01/2018'));
            $to = date('Y-m-d');
            $from_date = date('01/01/Y');
            $to_date = date('d/m/Y');
        }

        $wallet = str_replace("_", " ", $wallet);
        $balance = Balance::where(['user_id'=>$id,'description'=>$wallet])->first();
        if($balance){
            $data = HistoryTransaction::where('balance_id',$balance->id)
                ->whereDate('created_at','>=',$from)
                ->whereDate('created_at','<=',$to)
                ->orderBy('id','desc')
                ->paginate(20);
            $in = HistoryTransaction::where('balance_id',$balance->id)->where('type','IN')
                ->whereDate('created_at','>=',$from)
                ->whereDate('created_at','<=',$to)
                ->sum('amount');
            $out = HistoryTransaction::where('balance_id',$balance->id)->where('type','OUT')
                ->whereDate('created_at','>=',$from)
                ->whereDate('created_at','<=',$to)
                ->sum('amount');
            $a = number_format($in,8, '.', '');
            $b = number_format($out,8, '.', '');
            $total = $a - $b;
        }else{
            $data = HistoryTransaction::whereYear('created_at','2017')
                ->orderBy('created_at','desc')
                ->paginate(20);
            $total = 0;
        }
        $username = $user->username;
        return view('backend.balance.history', compact('data','total','from_date','to_date','id','wallet','username'))->with('i', (request()->input('page', 1) - 1) * 20);
    }

    public function change_balance(Request $request)
    {
        $this->validate($request, [
            'id'=>'required|exists:balances,id',
            'jumlah'=>'required|numeric',
            'tipe'=>'required|string|in:tambah,kurang',
            'keterangan'=>'required|string',
            'password'=>'required'
        ]);

        $hasPassword = Hash::check($request->password, Auth::user()->password);
        if($hasPassword){
            $balanceAdmin = Balance::where(['user_id'=>2,'description'=>'Koin'])->first();
            $saldo = Balance::find($request->id);
            if($saldo){
                $tipe = $request->tipe;
                $role = $saldo->user->roles[0]->name;
                if($tipe == 'tambah'){
                    $type = 'IN';
                    $message = 'Menambahkan jumlah koin '.ucfirst($saldo->user->username);
                    $message_notif = 'Admin telah menambahkan koin Anda sejumlah '.number_format($request->jumlah,0,',','.');
                    $saldo->balance = $saldo->balance + $request->jumlah;
                    if($role == 'admin'){
                        $from_id = $saldo->user_id;
                        $to_id = $saldo->user_id;
                    }else{
                        $from_id = 2;
                        $to_id = $saldo->user_id;
                        $type_ = 'OUT';
                        $balanceAdmin->balance = $balanceAdmin->balance - $request->jumlah;
                    }
                }else{
                    $type = 'OUT';
                    $message = 'Mengurangi jumlah koin '.ucfirst($saldo->user->username);
                    $message_notif = 'Admin telah mengurangi koin Anda sejumlah '.number_format($request->jumlah,0,',','.').'. Untuk lebih lanjut silahkan hubungi kami.';
                    if($saldo->balance < $request->jumlah){
                        $request->session()->flash('failed', 'Gagal, Jumlah koin member kurang dari jumlah pengurangan');
                        return redirect()->back();
                    }
                    $saldo->balance = $saldo->balance - $request->jumlah;
                    if($role == 'admin'){
                        $from_id = $saldo->user_id;
                        $to_id = $saldo->user_id;
                    }else{
                        $from_id = $saldo->user_id;
                        $to_id = 2;
                        $type_ = 'IN';
                        $balanceAdmin->balance = $balanceAdmin->balance + $request->jumlah;
                    }
                }
                $saldo->save();
                $balanceAdmin->save();
                HistoryTransaction::create([
                    'balance_id'=>$saldo->id,
                    'from_id'=>$from_id,
                    'to_id'=>$to_id,
                    'amount'=>$request->jumlah,
                    'description'=>ucwords(strtolower($request->keterangan)),
                    'status'=>1,
                    'type'=>$type
                ]);
                if($role == 'member'){
                    HistoryTransaction::create([
                        'balance_id'=>$balanceAdmin->id,
                        'from_id'=>$from_id,
                        'to_id'=>$to_id,
                        'amount'=>$request->jumlah,
                        'description'=>ucwords(strtolower($request->keterangan)),
                        'status'=>1,
                        'type'=>$type_
                    ]);

                    Notification::create([
                        'user_id' => $saldo->user_id,
                        'from_id' => 2,
                        'message' => $message_notif,
                        'status' => 1,
                    ]);
                }
                return redirect()->back()->with(['flash_success' => true,'title' => 'Berhasil','message' => $message]);
            }else{
                $request->session()->flash('failed', 'Gagal, Coba ulangi kembali atau muat ulang halaman');
                return redirect()->back();
            }
        }else{
            $request->session()->flash('failed', 'Gagal, Kata sandi salah');
            return redirect()->back();
        }
    }
}
