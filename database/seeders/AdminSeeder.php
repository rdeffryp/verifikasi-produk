<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        DB::table('admins')->insert([
            'username'   => 'admin',
            'password'   => bcrypt('password'),
            'full_name'  => 'Administrator',
            'email'      => 'admin@zjcookies.com',
            'is_active'  => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
