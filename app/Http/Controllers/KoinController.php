<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Buy;
use App\Models\Sell;
use App\Models\User;
use App\Models\Level;
use App\Models\Balance;
use App\Models\Setting;
use App\Models\Notification;
use App\Models\HistoryTransaction;
use App\Models\PaymentMethod;
use App\Models\PaymentAccount;
use App\Rules\IsValidEmail;
use App\Rules\IsValidPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class KoinController extends Controller
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
        $user = Auth::user();
        $type = PaymentMethod::has('account')->get();
        $data = $user->buy()->limit(20)->orderBy('id','desc')->get();
        $price = Setting::where('name','Harga Koin')->first()->value;
        $total_koin = number_format($user->balance()->where('description','Koin')->first()->balance,0,',','.');
        return view('backend.koin.index', compact('total_koin','price','type','data'));
    }

    public function buy(Request $request)
    {
        $this->validate($request, [
            'jumlah_koin' => 'required|numeric|min:1',
            'tipe_pembayaran' => 'required|integer|exists:payment_methods,id',
        ]);
        $amount = $request->jumlah_koin;
        $price = Setting::where('name','Harga Koin')->first()->value;
        $total = $amount * $price;
        $account = PaymentAccount::where('payment_method_id',$request->tipe_pembayaran)->first();
        $data = Buy::create([
            'user_id' => Auth::id(),
            'amount' => $amount,
            'price' => $price ,
            'total' => $total,
            'payment_method_id' => $request->tipe_pembayaran,
            'account_name' => $account->account_name,
            'account_number' => $account->account_number
        ]);
        return redirect()->back()->with(['flash_buy' => true,'title' => 'Berhasil','message' => 'Menambahkan Koin','data'=>$data]);
    }

    public function sell(Request $request)
    {
        $this->validate($request, [
            'jumlah_koin' => 'required|numeric|min:1',
            'username' => 'required|string|exists:users,username',
            'kata_sandi' => 'required'
        ]);
        $amount = $request->jumlah_koin;
        $username = $request->username;
        $kata_sandi = $request->kata_sandi;
        $user = Auth::user();
        $hasPassword = Hash::check($kata_sandi, $user->password);
        if(!$hasPassword){
            return redirect()->back()->with(['failed' => 'Gagal, Kata sandi salah']);
        }
        $receiver = User::where('username',$username)->first();
        $from_id = $user->id;
        $to_id = $receiver->id;
        $balanceSender = Balance::where(['user_id'=>$from_id,'description'=>'Koin'])->first();

        // masih kurang validasi jumlah koin level
        $level = $user->hasRank ? Auth::user()->hasRank->level->level : 0;
        $sum_koin = Level::where('level','<=',$level)->sum('coin');

        if($amount > ($balanceSender->balance - $sum_koin)){
            return redirect()->back()->with(['failed' => 'Gagal, Jumlah koin Anda tidak cukup']);
        }

        Sell::create([
            'seller_id' => $from_id,
            'buyer_id' => $to_id,
            'amount' => $amount,
            'status' => 1,
            'type' => 'koin'
        ]);

        $balanceSender->balance = $balanceSender->balance - $amount;
        $balanceSender->save();

        HistoryTransaction::create([
            'balance_id'=>$balanceSender->id,
            'from_id'=>$from_id,
            'to_id'=>$to_id,
            'amount'=>$amount,
            'description'=> 'Penjualan Koin',
            'status'=>1,
            'type'=> 'OUT'
        ]);

        Notification::create([
            'user_id' => $from_id,
            'from_id' => $to_id,
            'message' => 'Penjualan Koin '.number_format($amount,0,',','.').' ke '.ucfirst($receiver->username).' telah berhasil',
            'status' => 1,
        ]);

        $balanceReceiver = Balance::where(['user_id'=>$to_id,'description'=>'Koin'])->first();
        $balanceReceiver->balance = $balanceReceiver->balance + $amount;
        $balanceReceiver->save();

        HistoryTransaction::create([
            'balance_id'=>$balanceReceiver->id,
            'from_id'=>$from_id,
            'to_id'=>$to_id,
            'amount'=>$amount,
            'description'=> 'Pembelian Koin',
            'status'=>1,
            'type'=> 'IN'
        ]);

        Notification::create([
            'user_id' => $to_id,
            'from_id' => $from_id,
            'message' => 'Pembelian Koin '.number_format($amount,0,',','.').' dari '.ucfirst($user->username).' telah berhasil',
            'status' => 1,
        ]);

        return redirect()->back()->with(['flash_success' => true,'title' => 'Penjualan Koin Anda Berhasil','message' => 'Silahkan hubungi pembeli untuk konfirmasi pembelian.']);
    }

    public function history_buy(Request $request)
    {
        $user = Auth::user();
        $status = $request->status;
        $search = $request->search;
        $data = $user->buy()->when($search, function ($query) use ($search){
                    $query->whereDate('created_at',$search);
                })
                ->when($status, function ($query) use ($status){
                    $status = $status - 1;
                    $query->where('status',$status);
                })
                ->orderBy('id','desc')->paginate(20);
        $total = number_format($user->buy()->sum('amount'),0,',','.');
        return view('backend.koin.buy', compact('total','data'))->with('i', (request()->input('page', 1) - 1) * 20);
    }

    public function history_sell(Request $request)
    {
        $user = Auth::user();
        $search = $request->search;
        $data = $user->sell()->when($search, function ($query) use ($search){
                    $query->whereDate('created_at',$search);
                })
                ->orderBy('id','desc')->paginate(20);
        return view('backend.koin.sell', compact('data'))->with('i', (request()->input('page', 1) - 1) * 20);
    }

    public function list_buy(Request $request)
    {
        $status = $request->status;
        $search = $request->search;
        $to_date = $request->to_date;
        $from_date = $request->from_date;
        if(!$to_date){
            $to_date = date('Y-m-d');
        }
        if(!$from_date){
            $from_date = date('Y-01-01');
        }
        $to_date = date('Y-m-d', strtotime("+1 day", strtotime($to_date)));
        $data = Buy::when($search, function ($query) use ($search){
                    $query->whereHas('user',function ($user) use ($search){
                        $user->where('users.name',$search)
                        ->orWhere('users.username',$search);
                    });
                })
                ->when($status, function ($query) use ($status){
                    $status = $status - 1;
                    $query->where('status',$status);
                })
                ->whereBetween('created_at',[$from_date,$to_date])
                ->orderBy('id','desc')->paginate(20);
        $amount = Buy::when($search, function ($query) use ($search){
                    $query->whereHas('user',function ($user) use ($search){
                        $user->where('users.name',$search)
                        ->orWhere('users.username',$search);
                    });
                })
                ->when($status, function ($query) use ($status){
                    $status = $status - 1;
                    $query->where('status',$status);
                })
                ->whereBetween('created_at',[$from_date,$to_date])
                ->sum('amount');
        $amount = number_format($amount,0,',','.');
        $total = Buy::when($search, function ($query) use ($search){
                    $query->whereHas('user',function ($user) use ($search){
                        $user->where('users.name',$search)
                        ->orWhere('users.username',$search);
                    });
                })
                ->when($status, function ($query) use ($status){
                    $status = $status - 1;
                    $query->where('status',$status);
                })
                ->whereBetween('created_at',[$from_date,$to_date])
                ->sum('total');
        $total = number_format($total,0,',','.');
        return view('backend.koin.list_buy', compact('amount','total','data'))->with('i', (request()->input('page', 1) - 1) * 20);
    }

    public function confirm(Request $request,$type,$id)
    {
        $status = 2;
        $message = 'Membatalkan pembelian.';
        $data = Buy::find($id);
        $amount = $data->amount;
        $message_notif = 'Pembelian Koin Anda sejumlah '.number_format($amount,0,',','.').' koin telah gagal';
        if($type == 'confirm'){
            $from_id = 2;
            $to_id = $data->user_id;
            $status = 1;
            $message = 'Mengkonfirmasi pembelian.';
            $message_notif = 'Pembelian Koin Anda sejumlah '.number_format($amount,0,',','.').' koin telah berhasil';
            $balanceAdmin = Balance::where(['user_id'=>$from_id,'description'=>'Koin'])->first();
            if($amount > $balanceAdmin->balance){
                return redirect()->back()->with(['flash_success' => true,'title' => 'Gagal','message' => 'Jumlah Koin Admin tidak cukup, silahkan tambahkan terlebih dahulu.']);
            }

            $balanceAdmin->balance = $balanceAdmin->balance - $amount;
            $balanceAdmin->save();

            HistoryTransaction::create([
                'balance_id'=>$balanceAdmin->id,
                'from_id'=>$from_id,
                'to_id'=>$to_id,
                'amount'=>$amount,
                'description'=> 'Pembelian Koin dari '.ucfirst($data->user->name),
                'status'=>1,
                'type'=> 'OUT'
            ]);

            $balanceUser = Balance::where(['user_id'=>$to_id,'description'=>'Koin'])->first();
            $balanceUser->balance = $balanceUser->balance + $amount;
            $balanceUser->save();

            HistoryTransaction::create([
                'balance_id'=>$balanceUser->id,
                'from_id'=>$from_id,
                'to_id'=>$to_id,
                'amount'=>$amount,
                'description'=> 'Pembelian Koin',
                'status'=>1,
                'type'=> 'IN'
            ]);
        }
        $data->update([
            'status' => $status
        ]);

        Notification::create([
            'user_id' => $data->user_id,
            'from_id' => 2,
            'message' => $message_notif,
            'status' => 1,
        ]);
        return redirect()->back()->with(['flash_success' => true,'title' => 'Berhasil','message' => $message]);
    }

    public function list_buy_sell(Request $request)
    {
        $search = $request->search;
        $to_date = $request->to_date;
        $from_date = $request->from_date;
        if(!$from_date){
            $from_date = date('Y-01-01');
        }
        if(!$to_date){
            $to_date = date('Y-m-d');
        }
        $to_date = date('Y-m-d', strtotime("+1 day", strtotime($to_date)));
        $data = Sell::when($search, function ($query) use ($search){
                    $query->whereHas('seller',function ($user) use ($search){
                        $user->where('users.name',$search)
                        ->orWhere('users.username',$search);
                    })->orWhereHas('buyer',function ($user) use ($search){
                        $user->where('users.name',$search)
                        ->orWhere('users.username',$search);
                    });
                })
                ->whereBetween('created_at',[$from_date,$to_date])
                ->orderBy('id','desc')->paginate(20);
        $total = Sell::when($search, function ($query) use ($search){
                    $query->whereHas('seller',function ($user) use ($search){
                        $user->where('users.name',$search)
                        ->orWhere('users.username',$search);
                    })->orWhereHas('buyer',function ($user) use ($search){
                        $user->where('users.name',$search)
                        ->orWhere('users.username',$search);
                    });
                })
                ->whereBetween('created_at',[$from_date,$to_date])
                ->sum('amount');
        $total = number_format($total,0,',','.');
        return view('backend.koin.list_buy_sell_member', compact('total','data'))->with('i', (request()->input('page', 1) - 1) * 20);
    }
}
