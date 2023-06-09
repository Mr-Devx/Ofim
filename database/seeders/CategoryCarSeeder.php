<?php

namespace Database\Seeders;

use App\Models\CategoryCar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryCarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name_fr'  => "Electrique"
            ],
            [
                'name_fr'  => "A carburant"
            ],
            [
                'name_fr'  => "Hybride"
            ],
        ];

        foreach($roles as $role){
            CategoryCar::create($role);
        }
    }
}
