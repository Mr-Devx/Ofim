<?php

namespace Database\Seeders;

use App\Models\Parameters;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParametersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name'  => "Param 1",
                'value' => 2000
            ],
        ];

        foreach($roles as $role){
            Parameters::create($role);
        }
    }
}
