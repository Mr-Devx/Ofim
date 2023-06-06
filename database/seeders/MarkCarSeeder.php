<?php

namespace Database\Seeders;

use App\Models\MarkCar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarkCarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name'  => "BMW"
            ],
            [
                'name'  => "Mercedez"
            ],
            [
                'name'  => "Range Rover"
            ],
            [
                'name'  => "HYUNDAI"
            ],
        ];

        foreach($roles as $role){
            MarkCar::create($role);
        }
    }
}
