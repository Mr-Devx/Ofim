<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entities = [
            [
                'mark_id'               => 1,
                'created_by'            => 4,
                'category_id'           => 1,
                'state_id'              => 3,
                'type_id'               => 1,
                'decription'            => "Desc 1",
                "model"                 => "Model 1",
                "year"                  => "2016",
                "color"                 => "Rouge",
                "lat"                   => 3.0458392012,
                "long"                  => 1.93847593,
                "day_price"             => 40000,
                "location_price"        => 3500,
                "client_price"          => 43500,
                "note"                  => 3.9,
                "km"                    => 38000,
                "registration"          => "AK-3049",
                "verified_at"           => "2023-06-05 20:20:01",
                "percentage_reduction"  => 10,
                "is_manuel"             => false,
            ],
            [
                'mark_id'               => 2,
                'created_by'            => 4,
                'category_id'           => 2,
                'state_id'              => 3,
                'type_id'               => 2,
                'decription'            => "Desc 2",
                "model"                 => "Model 2",
                "year"                  => "2017",
                "color"                 => "Noir",
                "lat"                   => 3.0458392012,
                "long"                  => 1.93847593,
                "day_price"             => 50000,
                "location_price"        => 9000,
                "client_price"          => 59000,
                "note"                  => 4.3,
                "km"                    => 8000,
                "registration"          => "AZ-3009",
                "verified_at"           => "2023-06-05 20:20:01",
                "percentage_reduction"  => 16,
                "is_manuel"             => true,
            ],
            [
                'mark_id'               => 3,
                'created_by'            => 5,
                'category_id'           => 2,
                'state_id'              => 3,
                'type_id'               => 2,
                'decription'            => "Desc 3",
                "model"                 => "Model 3",
                "year"                  => "2000",
                "color"                 => "Bleu citron",
                "lat"                   => 3.0458392012,
                "long"                  => 1.93847593,
                "day_price"             => 20000,
                "location_price"        => 1000,
                "client_price"          => 21000,
                "note"                  => 0.3,
                "km"                    => 800000,
                "registration"          => "CD-3989",
                "verified_at"           => "2023-06-05 20:20:01",
                "percentage_reduction"  => 16,
                "is_manuel"             => false,
            ],
            [
                'mark_id'               => 3,
                'created_by'            => 5,
                'category_id'           => 2,
                'state_id'              => 1,
                'type_id'               => 2,
                'decription'            => "Desc 4",
                "model"                 => "Model 4",
                "year"                  => "2023",
                "color"                 => "Noir",
                "lat"                   => 3.0458392012,
                "long"                  => 1.93847593,
                "day_price"             => 120000,
                "location_price"        => 40000,
                "client_price"          => 160000,
                "note"                  => 0,
                "km"                    => 2000,
                "registration"          => "DE-1001",
                "percentage_reduction"  => 16,
                "is_manuel"             => true,
            ],
        ];

        foreach($entities as $data){
            Car::create($data);
        }
    }
}
