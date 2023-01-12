<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(['name' => 'Torben Evald Hanen', 'email' => 'tvupper@gmail.com', 'password' => bcrypt('hundelort'), 'refresh_token' => config('services.energioverblik.refresh_token')]);
    }
}
