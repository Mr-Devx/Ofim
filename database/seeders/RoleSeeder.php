<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name'  => "Admin"
            ],
            [
                'name'  => "Editeur"
            ],
            [
                'name'  => "Lecteur"
            ],
            [
                'name'  => "Professionnel"
            ],
            [
                'name'  => "Utilisateur"
            ],
        ];

        foreach($roles as $role){
            Role::create($role);
        }
    }
}
