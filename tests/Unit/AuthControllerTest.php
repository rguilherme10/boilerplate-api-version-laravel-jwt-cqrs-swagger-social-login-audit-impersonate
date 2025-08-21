<?php

use App\Application\Buses\CommandBus;
use App\Application\Commands\User\CreateUserCommand;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_com_credenciais_validas()
    {
        $user = Bus::dispatchSync(new CreateUserCommand('test', 'ribamar@example.com', 'senha123'));

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'senha123'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'access_token',
                     'token_type',
                     'expires_in'
                 ]);
    }

    public function test_login_com_credenciais_invalidas()
    {
        $response = $this->postJson('/api/v1/login', [
            'email' => 'naoexiste@example.com',
            'password' => 'errada'
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'error' => 'Credenciais inválidas'
                 ]);
    }
}