<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CeafAdminSeeder extends Seeder
{
    public function run(): void
    {
      DB::table('ceaf')->updateOrInsert(
            ['email' => 'admin@ceaf.org'],
            [
                'name' => 'System Admin',
                'role' => 'admin',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

    }
}
