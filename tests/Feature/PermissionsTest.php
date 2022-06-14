<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Tests\CreateData;

class PermissionsTest extends TestCase
{
    use RefreshDatabase, CreateData;

    /** @test */
    public function the_user_needs_to_have_a_role_to_access_admin_pages()
    {
        $this->get('/admin')
            ->assertRedirect(route('login'));

        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->get('/admin')
            ->assertStatus(403); //Ya que el usuario no tiene un rol
    }

    /** @test */
    public function a_user_with_the_admin_role_can_access_to_admin_pages()
    {
        $admin = Role::create(['name' => 'admin']);
        $user = User::factory()->create()->assignRole($admin);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->get('/admin')
            ->assertStatus(200);
    }
}
