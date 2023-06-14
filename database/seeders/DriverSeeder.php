<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entities = [
            [
                "fullname"      => "KOKU Marcus",
                "phone"         => "+22890345968",
                "license_number"      => "DK938-039-3958-3049",
                "license_expire_date" => "2028-10-12",
                "owner_id"      => 1,
                "is_active"     => true
            ],
            [
                "fullname"      => "JARDIN Durant",
                "phone"         => "+22890345968",
                "license_number"      => "DK938-039-3958-3049",
                "license_expire_date" => "2028-10-12",
                "owner_id"      => 1,
                "is_active"     => false
            ],
            [
                "fullname"      => "BENOIS Dupont",
                "phone"         => "+22890345968",
                "license_number"      => "DK938-039-3958-3049",
                "license_expire_date" => "2028-10-12",
                "owner_id"      => 2,
                "is_active"     => true
            ],
            [
                "fullname"      => "DELAPICINE Louis",
                "phone"         => "+22890345968",
                "license_number"      => "DK938-039-3958-",
                "license_expire_date" => "2028-10-12",
                "owner_id"      => 3,
                "is_active"     => true
            ],
        ];

        foreach($entities as $data){
            Driver::create($data);
        }
    }
}
