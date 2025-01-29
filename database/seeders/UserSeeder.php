<?php

namespace Database\Seeders;

use App\Models\External;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Repositories\User\UserRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userRepository = new UserRepository(new User());

        $data = [
            "full_name" => "Administrador",
            "email" => "admin@autobox.com",
            "phone" => '4120000000',
            'country_code' => '+58',
            "password" => "Secret20a.",
            'res_partner_id' => 10
        ];

        $userRepository->create($data);

        External::create([
            'tokenable_type' => 'Sync',
            'tokenable_id'   => 1,
            'name' => 'Sincronizacion with Odoo',
            'token' => 'UFL1qNmXohobfYmPkk6Eiwcv5HDQIQZVflOhV7ffouMJKtkEla',
            'abilities' => 'UFL1qNmXohobfYmPkk6Eiwcv5HDQIQZVflOhV7ffouMJKtkEla',
            'last_used_at' => now(),
            'expires_at' => now()->addCenturies(1)
        ]);
    }
}
