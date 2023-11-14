<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entities = [
            [
                'firstname'     => "PADARO",
                'lastname'      => "rais",
                'email'         => "raiis.padaro@gmail.com",
                'phone'         => '+22891401680',
                'username'      => 'rais',
                'password'      => bcrypt('password'),
                'is_active'     => true,
                'id_role' => 1,
                'id_ChefSection' => null,
                'id_ChefDivision' => null,

            ],
            [
                'firstname'     => "User",
                'lastname'      => "user",
                'email'         => "awakipilazi@gmail.com",
                'phone'         => '+22890807060',
                'username'      => 'user',
                'password'      => bcrypt('password'),
                'is_active'     => true,
                'id_role' => 2,
                'id_ChefSection' => null,
                'id_ChefDivision' => null,
            ],
        ];

        foreach ($entities as $key => $value) {
            User::create($value);
        }
    }
}
