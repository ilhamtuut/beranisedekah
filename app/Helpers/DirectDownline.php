<?php
namespace App\Helpers;

use DB;
use App\Models\User;
use App\Models\Level;
use App\Models\Balance;
use App\Models\Donation;
use App\Models\Downline;
use App\Models\UserLevel;
use App\Models\Notification;
use App\Models\HistoryTransaction;

class DirectDownline {

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function add($user_id, $upline_id)
    {
        $upline = $upline_id;
        Downline::create([
            'user_id' => $upline,
            'downline_id' => $user_id,
            'status' => 1
        ]);
        for($i = 1; $i <= 5000; $i++){
            $upline =  $this->downlines($upline,$user_id);
            if(is_null($upline)){
                break;
            }else{
                $upline = $upline;
            }
        }
    }

    public function downlines($upline_id,$user_id)
    {
        $check_downline = Downline::where('downline_id',$upline_id)->orderBy('id','asc')->first();
        if($check_downline){
            $upline = $check_downline->user_id;
            Downline::create([
                'user_id' => $upline,
                'downline_id' => $user_id,
                'status' => 1
            ]);
        }else{
            $upline = null;
        }
        return $upline;
    }

    public function updateLevel($user_id)
    {
        $user = User::find($user_id);
        $level = $user->hasRank ? $user->hasRank->level->level : 0;
        $level = $level + 1;
        // $batas_koin = Level::where('level','<=',$level)->sum('coin');
        $batas_donasi = Level::where('level','<=',$level)->sum(DB::raw('amount * count'));

        // $koin = Balance::where(['user_id'=>$user->id,'description' => 'Koin'])->first();
        // $jumlah_koin = $koin->balance - $batas_koin;
        $jumlah_donasi = Donation::where(['user_id'=>$user->id,'status' => 2])->sum('amount');
        // if($jumlah_koin >= 0 && $jumlah_donasi >= $batas_donasi){
        if($jumlah_donasi >= $batas_donasi){
            $levels = Level::where('level',$level)->first();
            $checklevel = UserLevel::where('user_id',$user->id)->first();
            if($checklevel){
                $checklevel->update(['level_id'=>$levels->id]);
            }else{
                UserLevel::create([
                    'user_id'=>$user->id,
                    'level_id'=>$levels->id,
                ]);
            }
            Notification::create([
                'user_id' => $user->id,
                'from_id' => $user->id,
                'message' => 'Selamat level anda telah naik ke '.$levels->name,
                'status' => 1,
            ]);
        }
        $this->rewardKoin($user_id);
    }

    public function rewardKoin($user_id)
    {
        $amount = 1;
        $from_id = 2;
        $user = User::find($user_id);
        $description = 'Reward koin dari '.ucfirst($user->username);
        $parent = $user->parent;
        $to_id = $parent->id;
        $check = HistoryTransaction::where([
            'from_id'=>$user_id,
            'to_id'=>$to_id,
            'description'=>$description,
        ])->first();

        if(is_null($check)){
            $koin_admin = Balance::where(['user_id'=>$from_id,'description'=>'Koin'])->first();
            $koin_admin->balance = $koin_admin->balance - $amount;
            $koin_admin->save();

            HistoryTransaction::create([
                'balance_id'=>$koin_admin->id,
                'from_id'=>$from_id,
                'to_id'=>$to_id,
                'amount'=>$amount,
                'description'=> 'Reward koin untuk '.ucfirst($parent->username),
                'status'=>1,
                'type'=> 'OUT'
            ]);

            $koin = $parent->balance()->where('description','Koin')->first();
            $koin->balance = $koin->balance + $amount;
            $koin->save();

            HistoryTransaction::create([
                'balance_id'=>$koin->id,
                'from_id'=>$user_id,
                'to_id'=>$to_id,
                'amount'=>$amount,
                'description'=> $description,
                'status'=>1,
                'type'=> 'IN'
            ]);

            Notification::create([
                'user_id' => $to_id,
                'from_id' => $to_id,
                'message' => 'Selamat anda mendapatkan '.$amount.' koin reward dari '.ucfirst($user->username),
                'status' => 1,
            ]);
        }
    }
}
