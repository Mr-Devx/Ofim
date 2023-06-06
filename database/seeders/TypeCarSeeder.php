<?php

namespace Database\Seeders;

use App\Models\TypeCar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeCarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name'  => "Berling"
            ],
            [
                'name'  => "4x4"
            ],
        ];

        foreach($roles as $role){
            TypeCar::create($role);
        }
    }
}
