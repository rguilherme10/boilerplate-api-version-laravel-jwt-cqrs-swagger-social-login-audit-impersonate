<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $user = \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt("admin123")
        ]);

        // Criar permissão
        Permission::create(['name' => 'user-set-role']);

        // Criar papel
        $role = Role::create(['name' => 'admin']);

        // Associar permissão ao papel
        $role->givePermissionTo('user-set-role');

        // Associar papel ao usuário
        $user->assignRole('admin');
    }
}
