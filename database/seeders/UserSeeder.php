<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::create(['name' => 'admin']);
        $client = Role::create(['name' => 'client']);

        User::factory()->create([
            'name' => 'Carlos Molina',
            'email' => 'carlos@admin.com',
            'password' => bcrypt('password')
        ])->assignRole($admin);
        User::factory()->create([
            'name' => 'Prueba prueba',
            'email' => 'prueba@prueba.com',
            'password' => bcrypt('password')
        ])->assignRole($client);
    }
}
