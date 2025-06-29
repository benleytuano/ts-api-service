<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// database/seeders/RBACSeeder.php
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RBACSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $agent = Role::firstOrCreate(['name' => 'agent']);
        $user = Role::firstOrCreate(['name' => 'user']);

        // Permissions
        $perms = [
            'create tickets',
            'view tickets',
            'update tickets',
            'delete tickets',
            'assign tickets',
        ];
        foreach ($perms as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Attach permissions to roles
        $admin->permissions()->sync(Permission::all()->pluck('id'));
        $agent->permissions()->sync(Permission::whereIn('name', [
            'view tickets', 'update tickets', 'assign tickets'
        ])->pluck('id'));
        $user->permissions()->sync(Permission::whereIn('name', [
            'create tickets', 'view tickets'
        ])->pluck('id'));
        }
    }
