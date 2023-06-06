<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\TypeCar;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,

            /**Sync des permission au role */
            SynAdminPermission::class,
            SynEditeurPermission::class,
            SynLecteurPermission::class,
            SynProfessionnelPermission::class,
            SynUtilisateurPermission::class,

            /** entité parametre */
            CategoryCarSeeder::class,
            MarkCarSeeder::class,
            PlateformPayementSeeder::class,
            StateCarSeeder::class,
            TypeCarSeeder::class,

            /** data */
            UserSeeder::class,
            CarSeeder::class,
        ]);
    }
}
