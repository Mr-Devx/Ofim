<?php

namespace Database\Seeders;

use App\Models\PlateformPayement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlateformPayementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $roles = [
            [
                'name'  => "Carte bancaire"
            ],
            [
                'name'  => "Mobile Monney"
            ],
            [
                'name'  => "Paypal"
            ],
        ];

        foreach($roles as $role){
            PlateformPayement::create($role);
        }
    }
}
