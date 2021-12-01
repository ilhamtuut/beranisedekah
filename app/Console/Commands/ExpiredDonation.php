<?php

namespace App\Console\Commands;

use App\Models\Donation;
use Illuminate\Console\Command;

class ExpiredDonation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'donation:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expired Donation';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = date('Y-m-d H:i:s');
        $donation = Donation::where('status',0)->get();
        foreach($donation as $value){
            $date = date('Y-m-d H:i:s', strtotime("+1 day", strtotime($value->created_at)));
            if($now > $date){
                $value->update(['status' => 4]);
            }
        }
    }
}
