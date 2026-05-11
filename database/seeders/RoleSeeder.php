<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = array_map(
            fn (RoleEnum $role) => ['name' => $role->value],
            RoleEnum::cases()
        );

        DB::table('roles')->insertOrIgnore($roles);
    }
}