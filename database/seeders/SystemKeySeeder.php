<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class SystemKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        DB::table('system_keys')->insert([
            'key_name'   => 'main_public_key',
            'public_key' => "-----BEGIN PUBLIC KEY-----\nMFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAEZZDtmwqmTV7dSmTbkcbiGfFwsDHIhGz4\nRqmBJ+GL+cOuZZjn3KkxbUt+0Ia47IiXBDJH8GiOa8g868x0ATCLgw==\n-----END PUBLIC KEY-----",
            'algorithm'  => 'ECDSA-secp256k1',
            'is_active'  => 1,
            'created_at' => now(),
        ]);
    }
}
