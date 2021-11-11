<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Buy;
use App\Models\User;
use App\Models\Level;
use App\Helpers\DirectDownline;
use App\Models\Donation;
use App\Models\Notification;
use Illuminate\Http\Request;

class TransaksiController extends Controller
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
    public function index()
    {
        $user = Auth::user();
        $total_in = number_format($user->total_in(),0,',','.');
        $total_out = number_format($user->total_out(),0,',','.');
        return view('backend.transaksi.index',compact('total_in','total_out'));
    }

    public function donasi(Request $request)
    {
        $level = Auth::user()->hasRank ? Auth::user()->hasRank->level->level : 0;
        $level = $level + 1;
        if($level > 4){
            $level = 1;
        }
        $levels = Level::where('level',$level)->first();
        $userNot = Auth::user()->donation()->where(['status'=>2,'amount'=>$levels->amount])->pluck('receive_id')->toArray();
        array_push($userNot, Auth::id());
        $user = User::select('id','name','username')
                ->whereNotIn('id',$userNot)
                ->whereHas('roles', function ($query) {
                    $query->where('roles.name', 'member');
                })
                ->whereHas('hasRank', function ($query) use ($level) {
                    $query->whereHas('level', function ($rank) use ($level){
                        $rank->where('levels.level',$level);
                    });
                })
                ->where('is_priority',1)
                ->inRandomOrder()->first();
        if(is_null($user)){
            $user = User::select('id','name','username')
                ->whereNotIn('id',$userNot)
                ->whereHas('roles', function ($query) {
                    $query->where('roles.name', 'member');
                })
                ->whereHas('hasRank', function ($query) use ($level) {
                    $query->whereHas('level', function ($rank) use ($level){
                        $rank->where('levels.level',$level);
                    });
                })
                ->where('is_priority',0)
                ->inRandomOrder()->first();
        }
        if(is_null($user)){
            return redirect()->route('home');
        }

        $donation = Auth::user()->donation()->whereIn('status',[0,1])->first();
        if(is_null($donation)){
            $donation = Donation::create([
                'user_id' => Auth::id(),
                'receive_id' => $user->id,
                'amount' => $levels->amount,
            ]);
        }
        return view('backend.transaksi.donasi',compact('donation'));
    }

    public function sendDonation(Request $request,$id)
    {
        $this->validate($request, [
            'bank' => 'required',
            'nomor_rekening' => 'required',
            'atas_nama' => 'required',
        ]);
        $donation = Donation::find($id);
        $json_data = array(
            'receiver_account'=> array(
                'account_bank_name' => $donation->receiver->account_bank_name,
                'account_number' => $donation->receiver->account_number,
                'account_name' => $donation->receiver->account_name,
            )
        );

        $donation->update([
            'account_bank_name' => $request->bank,
            'account_number' => $request->nomor_rekening,
            'account_name' => $request->atas_nama,
            'status' => 1,
            'json_data' => json_encode($json_data)
        ]);
        return redirect()->back()->with(['flash_success' => true,'title' => 'Terima kasih telah memberikan donasi','message' => 'Hubungi Member untuk konfirmasi bahwa anda telah memberikan donasi.']);
    }

    public function confirm(Request $request)
    {
        $search = $request->search;
        $data = Auth::user()->receive_donation()
            ->when($search, function ($query) use ($search){
                $query->whereHas('user', function ($receiver) use ($search){
                    $receiver->where('users.name',$search)
                        ->orWhere('users.username',$search);
                });
            })
            ->paginate(10);
        return view('backend.transaksi.confirm',compact('data'));
    }

    public function history(Request $request)
    {
        $search = $request->search;
        $data = Auth::user()->my_donation()
            ->when($search, function ($query) use ($search){
                $query->whereHas('user', function ($user) use ($search){
                    $user->where('users.name',$search)
                        ->orWhere('users.username',$search);
                })->orWhereHas('receiver', function ($receiver) use ($search){
                    $receiver->where('users.name',$search)
                        ->orWhere('users.username',$search);
                });
            })
            ->where('status','!=',0)
            ->paginate(10);
        return view('backend.transaksi.history',compact('data'));
    }

    public function list(Request $request)
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
        $data = Donation::when($search, function ($query) use ($search){
                    $query->whereHas('user', function ($user) use ($search){
                        $user->where('users.name',$search)
                            ->orWhere('users.username',$search);
                    })->orWhereHas('receiver', function ($receiver) use ($search){
                        $receiver->where('users.name',$search)
                            ->orWhere('users.username',$search);
                    });
                })
                ->when($status, function ($query) use ($status){
                    $query->where('status',$status);
                })
                ->where('status','!=',0)
                ->whereBetween('created_at',[$from_date,$to_date])
                ->orderBy('id','desc')->paginate(20);
        $amount = Donation::when($search, function ($query) use ($search){
                    $query->whereHas('user', function ($user) use ($search){
                        $user->where('users.name',$search)
                            ->orWhere('users.username',$search);
                    })->orWhereHas('receiver', function ($receiver) use ($search){
                        $receiver->where('users.name',$search)
                            ->orWhere('users.username',$search);
                    });
                })
                ->when($status, function ($query) use ($status){
                    $query->where('status',$status);
                })
                ->where('status','!=',0)
                ->whereBetween('created_at',[$from_date,$to_date])
                ->sum('amount');
        $total = number_format($amount,0,',','.');
        return view('backend.transaksi.list', compact('total','data'))->with('i', (request()->input('page', 1) - 1) * 20);
    }

    public function confirm_donasi(Request $request,$type,$id)
    {
        $status = 3;
        $message = 'Membatalkan Donasi';
        $data = Donation::find($id);
        $amount = $data->amount;
        $message_notif = 'Donasi dari '.ucwords($data->user->name).' sejumlah Rp'.number_format($amount,0,',','.').' telah gagal';
        $message_notif_from = 'Donasi ke '.ucwords($data->receiver->name).' sejumlah Rp'.number_format($amount,0,',','.').' telah gagal';
        if($type == 'confirm'){
            $status = 2;
            $message = 'Mengkonfirmasi Donasi';
            $message_notif = 'Donasi dari '.ucwords($data->user->name).' sejumlah Rp'.number_format($amount,0,',','.').' telah berhasil';
            $message_notif_from = 'Donasi ke '.ucwords($data->receiver->name).' sejumlah Rp'.number_format($amount,0,',','.').' telah berhasil';

        }
        $data->update([
            'status' => $status
        ]);

        Notification::create([
            'user_id' => $data->user_id,
            'from_id' => $data->receive_id,
            'message' => $message_notif_from,
            'status' => 1,
        ]);

        Notification::create([
            'user_id' => $data->receive_id,
            'from_id' => $data->user_id,
            'message' => $message_notif,
            'status' => 1,
        ]);

        // update level
        (new DirectDownline())->updateLevel($data->user->id);

        return redirect()->back()->with(['flash_success' => true,'title' => 'Berhasil','message' => $message]);
    }
}
