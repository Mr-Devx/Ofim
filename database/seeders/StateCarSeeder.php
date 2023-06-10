<?php

namespace Database\Seeders;

use App\Models\StateCar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StateCarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name_fr'  => "Enregistrer" /* Ajouter mais pas encore approuver */
            ],
            [
                'name_fr'  => "Rejeter" 
            ],
            [
                'name_fr'  => "Brouillon" /* Not dispo pour location */
            ],
            [
                'name_fr'  => "Valider" 
            ],
            [
                'name_fr'  => "Publier" /* Dispo pour location */
            ],
            [
                'name_fr'  => "Louer" /** Louer a un locataire */
            ],
        ];

        foreach($roles as $role){
            StateCar::create($role);
        }
    }
}
