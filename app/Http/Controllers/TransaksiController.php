<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Models\Buy;
use App\Models\User;
use App\Models\Level;
use App\Models\Balance;
use App\Models\Donation;
use App\Models\LevelTerm;
use App\Models\Notification;
use App\Helpers\DirectDownline;
use App\Models\HistoryTransaction;
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
        $donation_old = Auth::user()->donation()->whereIn('status',[0])->first();
        if($donation_old){
            $now = date('Y-m-d H:i:s');
            $date = date('Y-m-d H:i:s', strtotime("+1 day", strtotime($donation_old->created_at)));
            if($now > $date){
                $donation_old->update(['status' => 4]);
            }
        }

        $user_level = Auth::user()->hasRank ? Auth::user()->hasRank->level->level : 0;
        $level = $user_level + 1;
        $is_level_four = false;
        if($user_level == 4){
            $level = 1;
        }elseif($user_level == 3){
            $is_level_four = true;
        }
        $levels = Level::where('level',$level)->first();
        $level_id = $levels->id;
        $amount = $levels->amount;
        $count_receive = $levels->count_r;
        $userNot = Auth::user()
                ->donation()
                ->where([
                    'status'=>2,
                    'from_level' => $user_level,
                    'to_level'=>$level
                ])
                ->groupBy('user_id')
                ->pluck('receive_id')->toArray();
        array_push($userNot, Auth::id());
        $notIn = implode(',',$userNot);
        $is_priority = 1;
        $user_priority = User::where('is_priority',$is_priority)->first();
        if(is_null($user_priority)){
            $is_priority = 0;
        }
        $step = 0;
        $user_id = 0;
        if($is_level_four){
            $count1 = LevelTerm::where(['level_id'=>4,'step'=>1])->first()->count;
            $count2 = LevelTerm::where(['level_id'=>4,'step'=>2])->first()->count;
            $count3 = LevelTerm::where(['level_id'=>4,'step'=>3])->first()->count;
            $count4 = LevelTerm::where(['level_id'=>4,'step'=>4])->first()->count;
            $count5 = LevelTerm::where(['level_id'=>4,'step'=>5])->first()->count;

            $user = DB::select('
                select `user_levels`.`user_id`, count(`donations`.`user_id`) as total,
                (select count(*) from `donations` as a where (`a`.`user_id` = `user_levels`.`user_id` and `a`.`from_level` = 4 and `a`.`to_level` = 1 and `a`.`status` = 2 and `a`.`step` = 1)) as kirim_donasi,
                (select count(*) from `donations` as b where (`b`.`receive_id` = `donations`.`user_id` and `b`.`from_level` = 3 and `b`.`to_level` = 4 and `b`.`status` in (0,1,2))) as terima_donasi,step
                from `donations`
                right join `user_levels` on `donations`.`user_id` = `user_levels`.`user_id`
                right join `users` on `users`.`id` = `user_levels`.`user_id`
                where `user_levels`.`level_id` = 4
                and `user_levels`.`user_id` not in ('.$notIn.')
                and `is_priority` = '.$is_priority.'
                group by `user_levels`.`user_id`
                having kirim_donasi = '.$count1.' and terima_donasi = 0
                UNION ALL
                select `user_levels`.`user_id`, count(`donations`.`user_id`) as total,
                (select count(*) from `donations` as a where (`a`.`user_id` = `user_levels`.`user_id` and `a`.`from_level` = 4 and `a`.`to_level` = 1 and `a`.`status` = 2 and `a`.`step` = 2)) as kirim_donasi,
                (select count(*) from `donations` as b where (`b`.`receive_id` = `donations`.`user_id` and `b`.`from_level` = 3 and `b`.`to_level` = 4 and `b`.`status` in (0,1,2))) as terima_donasi,step
                from `donations`
                right join `user_levels` on `donations`.`user_id` = `user_levels`.`user_id`
                right join `users` on `users`.`id` = `user_levels`.`user_id`
                where `user_levels`.`level_id` = 4
                and `user_levels`.`user_id` not in ('.$notIn.')
                and `is_priority` = '.$is_priority.'
                group by `user_levels`.`user_id`
                having kirim_donasi = '.$count2.' and terima_donasi = 1
                UNION ALL
                select `user_levels`.`user_id`, count(`donations`.`user_id`) as total,
                (select count(*) from `donations` as a where (`a`.`user_id` = `user_levels`.`user_id` and `a`.`from_level` = 4 and `a`.`to_level` = 1 and `a`.`status` = 2 and `a`.`step` = 3)) as kirim_donasi,
                (select count(*) from `donations` as b where (`b`.`receive_id` = `donations`.`user_id` and `b`.`from_level` = 3 and `b`.`to_level` = 4 and `b`.`status` in (0,1,2))) as terima_donasi,step
                from `donations`
                right join `user_levels` on `donations`.`user_id` = `user_levels`.`user_id`
                right join `users` on `users`.`id` = `user_levels`.`user_id`
                where `user_levels`.`level_id` = 4
                and `user_levels`.`user_id` not in ('.$notIn.')
                and `is_priority` = '.$is_priority.'
                group by `user_levels`.`user_id`
                having kirim_donasi = '.$count3.' and terima_donasi = 2
                UNION ALL
                select `user_levels`.`user_id`, count(`donations`.`user_id`) as total,
                (select count(*) from `donations` as a where (`a`.`user_id` = `user_levels`.`user_id` and `a`.`from_level` = 4 and `a`.`to_level` = 1 and `a`.`status` = 2 and `a`.`step` = 4)) as kirim_donasi,
                (select count(*) from `donations` as b where (`b`.`receive_id` = `donations`.`user_id` and `b`.`from_level` = 3 and `b`.`to_level` = 4 and `b`.`status` in (0,1,2))) as terima_donasi,step
                from `donations`
                right join `user_levels` on `donations`.`user_id` = `user_levels`.`user_id`
                right join `users` on `users`.`id` = `user_levels`.`user_id`
                where `user_levels`.`level_id` = 4
                and `user_levels`.`user_id` not in ('.$notIn.')
                and `is_priority` = '.$is_priority.'
                group by `user_levels`.`user_id`
                having kirim_donasi = '.$count4.' and terima_donasi = 3
                UNION ALL
                select `user_levels`.`user_id`, count(`donations`.`user_id`) as total,
                (select count(*) from `donations` as a where (`a`.`user_id` = `user_levels`.`user_id` and `a`.`from_level` = 4 and `a`.`to_level` = 1 and `a`.`status` = 2 and `a`.`step` = 5)) as kirim_donasi,
                (select count(*) from `donations` as b where (`b`.`receive_id` = `donations`.`user_id` and `b`.`from_level` = 3 and `b`.`to_level` = 4 and `b`.`status` in (0,1,2))) as terima_donasi,step
                from `donations`
                right join `user_levels` on `donations`.`user_id` = `user_levels`.`user_id`
                right join `users` on `users`.`id` = `user_levels`.`user_id`
                where `user_levels`.`level_id` = 4
                and `user_levels`.`user_id` not in ('.$notIn.')
                and `is_priority` = '.$is_priority.'
                group by `user_levels`.`user_id`
                having kirim_donasi >= '.$count5.' and terima_donasi = 4
                ORDER BY RAND()
                limit 1;
            ');

            if(count($user) > 0){
                $user_id = $user[0]->user_id;
            }
        }else{
            $user = DB::select('
                select `user_levels`.`user_id`, count(`donations`.`user_id`) as total,
                (select count(*) from `donations` as b where (`b`.`receive_id` = `user_levels`.`user_id` and `b`.`to_level` = '.$level.' and `b`.`status` in (0,1,2))) as terima_donasi
                from `donations`
                right join `user_levels` on `donations`.`user_id` = `user_levels`.`user_id`
                right join `users` on `users`.`id` = `user_levels`.`user_id`
                where `user_levels`.`level_id` = '.$level_id.'
                and `user_levels`.`user_id` not in ('.$notIn.')
                and `is_priority` = '.$is_priority.'
                group by `user_levels`.`user_id`
                having terima_donasi < '.$count_receive.'
                ORDER BY RAND()
                limit 1;
            ');
            if(count($user) > 0){
                $user_id = $user[0]->user_id;
            }
            if($user_level == 4){
                $step = 1;
                $mydonation = Auth::user()
                    ->donation()
                    ->where([
                        'status'=>2,
                        'from_level' => 4,
                        'to_level' => 1,
                    ])
                    ->orderBy('step','desc')
                    ->first();
                if($mydonation){
                    $steplevel = $mydonation->step;
                    $count = LevelTerm::where(['level_id'=>4,'step'=>$steplevel])->first()->count;
                    $count_donation = Auth::user()
                        ->donation()
                        ->where([
                            'status'=>2,
                            'from_level' => 4,
                            'to_level' => 1,
                            'step' => $steplevel
                        ])
                        ->count();
                    if($count_donation < $count){
                        $step = $steplevel;
                    }else{
                        $step = $steplevel + 1;
                    }
                }
            }
        }

        if($user_id == 0){
            return redirect()->route('home');
        }

        $donation = Auth::user()->donation()->whereIn('status',[0,1])->first();
        if(is_null($donation)){
            $donation = Donation::create([
                'user_id' => Auth::id(),
                'receive_id' => $user_id,
                'amount' => $amount,
                'from_level' => $user_level,
                'to_level' => $level,
                'step' => $step,
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

        $user = Auth::user();
        $donation = Donation::find($id);
        $from_id = $user->id;
        $to_id = 2;
        $user_level = $user->hasRank ? $user->hasRank->level->level : 0;
        $level = $user_level + 1;
        if($user_level == 4){
            $level = 1;
        }
        $levels = Level::where('level',$level)->first();
        $nameLevel = $levels->name;
        $amount = $levels->coin;
        if($levels->level == 4){
            $amount = $levels->term()->first()->coin;
        }
        $koin = $user->balance()->where('description','Koin')->first();
        if($amount > $koin->balance){
            return redirect()->back()->with(['failed' => 'Jumlah koin Anda tidak cukup, untuk bisa melakukan donasi setidaknya harus memiliki '.$amount.' koin.']);
        }

        $koin->balance = $koin->balance - $amount;
        $koin->save();

        HistoryTransaction::create([
            'balance_id'=>$koin->id,
            'from_id'=>$from_id,
            'to_id'=>$to_id,
            'amount'=>$amount,
            'description'=> 'Penggunaan koin untuk donasi ke level '.$nameLevel.' ['.$donation->receiver->username.']',
            'status'=>1,
            'type'=> 'OUT'
        ]);

        $koin_admin = Balance::where(['user_id'=>$to_id,'description'=>'Koin'])->first();
        $koin_admin->balance = $koin_admin->balance + $amount;
        $koin_admin->save();

        HistoryTransaction::create([
            'balance_id'=>$koin_admin->id,
            'from_id'=>$from_id,
            'to_id'=>$to_id,
            'amount'=>$amount,
            'description'=> 'Penerimaan koin dari '.ucfirst($user->username).' untuk donasi ke level '.$nameLevel.' ['.$donation->receiver->username.']',
            'status'=>1,
            'type'=> 'IN'
        ]);

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
            'json_data' => json_encode($json_data),
            'coin' => $amount
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
                ->whereNotIn('status',[0,4])
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
                ->whereNotIn('status',[0,4])
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
        $amount_koin = $data->coin;
        $message_notif = 'Donasi dari '.ucwords($data->user->name).' sejumlah Rp'.number_format($amount,0,',','.').' telah gagal';
        $message_notif_from = 'Donasi ke '.ucwords($data->receiver->name).' sejumlah Rp'.number_format($amount,0,',','.').' telah gagal';
        if($type == 'confirm'){
            $status = 2;
            $message = 'Mengkonfirmasi Donasi';
            $message_notif = 'Donasi dari '.ucwords($data->user->name).' sejumlah Rp'.number_format($amount,0,',','.').' telah berhasil';
            $message_notif_from = 'Donasi ke '.ucwords($data->receiver->name).' sejumlah Rp'.number_format($amount,0,',','.').' telah berhasil';
        }else{
            $from_id = 2;
            $to_id = $data->user->id;
            $koin_admin = Balance::where(['user_id'=>$from_id,'description'=>'Koin'])->first();
            $koin_admin->balance = $koin_admin->balance - $amount_koin;
            $koin_admin->save();

            HistoryTransaction::create([
                'balance_id'=>$koin_admin->id,
                'from_id'=>$from_id,
                'to_id'=>$to_id,
                'amount'=>$amount_koin,
                'description'=> 'Pembatalan Donasi ke '.ucfirst($data->user->username),
                'status'=>1,
                'type'=> 'OUT'
            ]);

            $koin = Balance::where(['user_id'=>$to_id,'description'=>'Koin'])->first();
            $koin->balance = $koin->balance + $amount_koin;
            $koin->save();

            HistoryTransaction::create([
                'balance_id'=>$koin->id,
                'from_id'=>$from_id,
                'to_id'=>$to_id,
                'amount'=>$amount_koin,
                'description'=> 'Pembatalan Donasi',
                'status'=>1,
                'type'=> 'IN'
            ]);
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
