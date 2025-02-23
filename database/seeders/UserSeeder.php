<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Gavin Ostheimer',
                'email' => 'gavinostheimer00@gmail.com',
                'email_verified_at' => null,
                'password' => bcrypt(value: 'Conr@dcorn1'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]

            ];
            User::insert($users);
    }
}
