<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $super_admin = Role::findByName('Super Admin');
        $client = Role::findByName('Client');

        $permissions = DB::table('permissions')->pluck('name')->toArray();

        $super_admin->givePermissionTo($permissions);
        $client->givePermissionTo(
            [
                'user.show',
                'user.update',
                'store.index',
                'vehicle.index',
                'vehicle.store',
                'vehicle.update',
                'vehicle_models.index',
                'vehicle_brands.index',
                'tire_sizes.index',
                'tire_oem_depth.index',
                'tire_model.index',
                'tire_brand.index',
                'service.index',
                'service_tires.index',
                'service_operators.index',
                'service_items.index',
                'product.index',
                'product_category.index',
                'odometer.index',
                'category.index',
                'vehicle_summaries.index',
                'service_aligment.index',
                'service_oil.index',
                'error.store'
            ]
        );

        $user = User::findOrFail(1);
        $user->assignRole($super_admin);
    }
}
