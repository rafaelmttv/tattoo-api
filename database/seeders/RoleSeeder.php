<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin'],
            ['name' => 'Customer'],
            ['name' => 'TattooArtist'],
            ['name' => 'Studio'],
        ];

        DB::table('roles')->insert($roles);
    }
}