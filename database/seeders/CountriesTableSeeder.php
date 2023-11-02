<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Chargez les données à partir du fichier JSON (ajustez le chemin du fichier selon vos besoins)
       $data = json_decode(file_get_contents(__DIR__ . '/countries.json'), true);


       foreach ($data as $countryData) {
           $countryId = DB::table('countries')->insertGetId([
               'name' => $countryData['country_name'],
           ]);

           foreach ($countryData['cities'] as $city) {
               DB::table('cities')->insert([
                   'name' => $city,
                   'country_id' => $countryId,
               ]);
           }
       }
    
    }
}
