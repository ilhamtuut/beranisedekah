<?php

use App\Models\User;
use App\Models\Balance;
use App\Models\Downline;
use App\Helpers\DirectDownline;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parent_id = 4;
        for ($i=0; $i < 20; $i++) {
            $faker = \Faker\Factory::create();
            $user = User::create([
                'parent_id' => $parent_id,
                'username' => $faker->username,
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone_number' => $faker->phoneNumber,
                'country' => $faker->country,
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'trx_password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            ]);
            $user->attachRole('member');

            Balance::create([
                'user_id' => $user->id,
                'balance' => 0,
                'status' => 1,
                'description' => 'Advertising Point'
            ]);

            Balance::create([
                'user_id' => $user->id,
                'balance' => 0,
                'status' => 1,
                'description' => 'Hoki Credit Sharing'
            ]);

            Balance::create([
                'user_id' => $user->id,
                'balance' => 0,
                'status' => 1,
                'description' => 'Golden Wallet'
            ]);

            Balance::create([
                'user_id' => $user->id,
                'balance' => 0,
                'status' => 1,
                'description' => 'Silver Wallet'
            ]);

            Balance::create([
                'user_id' => $user->id,
                'balance' => 0,
                'status' => 1,
                'description' => 'Diamond Wallet'
            ]);

            Balance::create([
                'user_id' => $user->id,
                'balance' => 0,
                'status' => 1,
                'description' => 'Netcash Wallet'
            ]);

            (new DirectDownline)->add($user->id, $parent_id);
            $parent_id = $user->id;
        }
    }
}
