<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::findOrCreate('user.index');
        Permission::findOrCreate('user.show');
        Permission::findOrCreate('user.store');
        Permission::findOrCreate('user.update');
        Permission::findOrCreate('user.destroy');
        Permission::findOrCreate('users.audit');
        Permission::findOrCreate('store.index');
        Permission::findOrCreate('vehicle.index');
        Permission::findOrCreate('vehicle.store');
        Permission::findOrCreate('vehicle.update');
        Permission::findOrCreate('vehicle_models.index');
        Permission::findOrCreate('vehicle_brands.index');
        Permission::findOrCreate('tire_sizes.index');
        Permission::findOrCreate('tire_model.index');
        Permission::findOrCreate('tire_brand.index');
        Permission::findOrCreate('service.index');
        Permission::findOrCreate('service_tires.index');
        Permission::findOrCreate('service_operators.index');
        Permission::findOrCreate('service_items.index');
        Permission::findOrCreate('product.index');
        Permission::findOrCreate('product_category.index');
        Permission::findOrCreate('odometer.index');
        Permission::findOrCreate('action.index');
        Permission::findOrCreate('vehicle_summaries.index');
        Permission::findOrCreate('tire_oem_depth.index');
        Permission::findOrCreate('tire_oem_depth.show');
        Permission::findOrCreate('tire_oem_depth.store');
        Permission::findOrCreate('tire_oem_depth.update');
        Permission::findOrCreate('tire_oem_depth.destroy');
        Permission::findOrCreate('category.index');
        Permission::findOrCreate('category.store');
        Permission::findOrCreate('category.update');
        Permission::findOrCreate('category.show');
        Permission::findOrCreate('category.destroy');
        Permission::findOrCreate('service_aligment.index');
        Permission::findOrCreate('service_oil.index');
        Permission::findOrCreate('service_battery.index');
        Permission::findOrCreate('service_balancing.index');
        Permission::findOrCreate('error.store');
        Permission::findOrCreate('error.index');
        Permission::findOrCreate('error.delete');
        Permission::findOrCreate('vehicle_model_photo.destroy');
        Permission::findOrCreate('vehicle_model_photo.index');
        Permission::findOrCreate('vehicle_model_photo.store');
        Permission::findOrCreate('vehicle_model_photo.update');
    }
}
