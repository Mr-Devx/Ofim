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
                'name'  => "Enregistrer" /* Ajouter mais pas encore approuver */
            ],
            [
                'name'  => "Valider" 
            ],
            [
                'name'  => "Disponible" /* Dispo pour location */
            ],
            [
                'name'  => "Louer" /** Louer a un locataire */
            ],
        ];

        foreach($roles as $role){
            StateCar::create($role);
        }
    }
}
