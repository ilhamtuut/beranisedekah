<?php

use App\Models\Eshare;
use Illuminate\Database\Seeder;

class EshareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $price = 0.2;
        for ($i=0; $i < 20; $i++) {
            Eshare::create([
                'name' => 'Step '.($i + 1),
                'amount' => 2000000,
                'sold' => 0,
                'rest' => 2000000,
                'price' => $price,
            ]);
            $price += 0.01;
        }
    }
}
