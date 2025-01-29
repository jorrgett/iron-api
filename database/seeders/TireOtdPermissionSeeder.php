<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TireOtdPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::findOrCreate('tire_otd_standar.index');
        Permission::findOrCreate('tire_otd_standar.store');
        Permission::findOrCreate('tire_otd_standar.update');
        Permission::findOrCreate('tire_otd_standar.destroy');
    }
}
