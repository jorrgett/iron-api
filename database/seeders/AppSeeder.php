<?php

namespace Database\Seeders;

use App\Models\Application;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Application::create([
            'version' => '1.0.0',
            'platform' => 'android',
            'enable' => true,
            'note' => 'release date' . now()
        ]);

        Application::create([
            'version' => '1.0.0',
            'platform' => 'windows',
            'enable' => true,
            'note' => 'release date' . now()
        ]);
    }
}
