<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $token_account = bcrypt(Str::random(60));

        User::create([
            'email' => Str::random(10) . '@gmail.com',
            'user_identifier' => mt_rand(000000, 999999),
            'name' => 'Ndamou',
            'firstname' => 'Audrey',
            'password' => bcrypt('password'),
            'avatar' => NULL,
            'role_id' => 2,
            'rank_id' => 2,
            'token' => NULL,
            'email_verified_at' => Carbon::now(),
            'token_account' => str_replace('/', '$', $token_account)
        ]);
    }
}
