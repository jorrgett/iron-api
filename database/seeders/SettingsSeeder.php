<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'twi'               => 1.6,
            'otd_standar'       => 9,
            'sequence_id'       => 1,
            'warning_threshold' => 75,
            'warning_color'     => '#CA0000',
            'danger_threshold'  => 85
        ]);
    }
}
