<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [
                'name'  => "monitoring-car"
            ],
            [
                'name'  => "monitoring-reservation"
            ],
        ];

        $actions = [
            [
                'name'  => "liste"
            ],
            [
                'name'  => "details"
            ],
            [
                'name'  => "add"
            ],
            [
                'name'  => "update"
            ],
            [
                'name'  => "delete"
            ],
            [
                'name'  => "validation"
            ],
            [
                'name'  => "revoke"
            ],
        ];

        foreach($modules as $module){
            foreach($actions as $action){
                $name = $module['name'].'-'.$action['name'];
                Permission::create([
                    'name'  => $name
                ]);
            }
        }
    }
}
