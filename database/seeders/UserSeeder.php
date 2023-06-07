<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entities = [
            [
                'firstname'     => "AGBOKA",
                'lastname'      => "Fabrice",
                'email'         => "fabrice@gmail.com",
                'phone'         => '+22890807060',
                'username'      => 'fabrice',
                'password'      => bcrypt('password'),
                'role_id'       =>1,
                'is_active'     => true,
            ],
            [
                'firstname'     => "ADMIN",
                'lastname'      => "Admin",
                'email'         => "admin@maloc.com",
                'phone'         => '+22890807060',
                'username'      => 'admin',
                'password'      => bcrypt('password'),
                'role_id'       =>1,
                'is_active'     => true,
            ],
            [
                'firstname'     => "ADMIN",
                'lastname'      => "Editeur",
                'email'         => "editeur@maloc.com",
                'phone'         => '+22890807061',
                'username'      => 'editeur',
                'password'      => bcrypt('password'),
                'role_id'       =>2,
                'is_active'     => true,
            ],
            [
                'firstname'     => "ADMIN",
                'lastname'      => "Lecteur",
                'email'         => "lecteur@maloc.com",
                'phone'         => '+22890807062',
                'username'      => 'lecteur',
                'password'      => bcrypt('password'),
                'role_id'       =>3,
                'is_active'     => true,
            ],
            [
                'firstname'     => "JOHN",
                'lastname'      => "Doe",
                'email'         => 'john@maloc.com',
                'phone'         => '+22890807063',
                'username'      => 'john',
                'password'      => bcrypt('password'),
                'role_id'       => 4,
                'is_active'     => true,
            ],
            [
                'firstname'     => "JANE",
                'lastname'      => "Doe",
                'email'         => 'jane@maloc.com',
                'phone'         => '+22890807064',
                'username'      => 'jane',
                'password'      => bcrypt('password'),
                'role_id'       => 5,
                'is_active'     => true,
            ],
            [
                'firstname'     => "DUPRAT",
                'lastname'      => "Patrick",
                'email'         => 'patric@maloc.com',
                'phone'         => '+22890807065',
                'username'      => 'patric',
                'password'      => bcrypt('password'),
                'role_id'       => 5,
                'is_active'     => true,
            ],
            [
                'firstname'     => "JEAN",
                'lastname'      => "Luc",
                'email'         => 'luc@maloc.com',
                'phone'         => '+22890807066',
                'username'      => 'luc',
                'password'      => bcrypt('password'),
                'role_id'       => 5,
                'is_active'     => false,
            ],
        ];

        foreach ($entities as $key => $value) {
            $user = User::create($value);

            $user->assignRole($user->role->name);
            $permissions = $user->getPermissionsViaRoles();
            $user->syncPermissions($permissions);
        }
    }
}
