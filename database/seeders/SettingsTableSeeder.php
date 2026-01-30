<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;


class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

public function run()
{
    Setting::create([
        'key' => 'approved_amount',
        'value' => '5000',
    ]);
}

}
