<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Configuration;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $year = now()->year;

        $configs = [
            'events_per_year' => 5,
            'amount_per_event' => 5000,
            'seed_payment_start' => '2026-01-01',
            'seed_payment_due' => '2026-02-15',
            'seed_late_deadline' => '2026-02-28',
            'late_fee_type' => 'flat',
            'late_fee_value' => 500,
        ];

        foreach ($configs as $key => $value) {
            Configuration::updateOrCreate(
                ['year' => $year, 'key' => $key],
                ['value' => $value]
            );
        }
    }
}
