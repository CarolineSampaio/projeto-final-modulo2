<?php

namespace Tests\Feature;

use App\Mail\SendWelcomeToNewUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'supersecretpassword'),
        ]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200)->assertJson([
            'message' => 'Autenticação realizada com sucesso',
            'data' => [
                'token' => true,
                'name' => $user->name,
            ],
        ]);
    }

    public function test_user_cannot_login_with_incorrect_credentials()
    {
        $response = $this->post('/api/login', [
            'email' => "test@example.com",
            'password' => 'incorrectpassword',
        ]);

        $response->assertStatus(401)->assertJson([
            'message' => 'Credenciais inválidas',
        ]);
    }

    public function test_user_registration_valid()
    {
        Mail::fake();
        $response = $this->post('/api/users', [
            'name' => 'Test User',
            'email' => 'test3@example.com',
            'date_birth' => '1990-01-01',
            'cpf' => '12345678900',
            'password' => 'supersecretpassword',
            'plan_id' => 1,
        ]);

        Mail::assertSent(SendWelcomeToNewUser::class, function ($mail) {
            return $mail->hasTo('test3@example.com');
        });

        $response->assertStatus(201)->assertJson([
            'message' => 'Usuário cadastrado com sucesso.'
        ]);
    }

    public function test_user_registration_invalid()
    {
        $response = $this->post('/api/users', [
            'name' => 'Test User',
            'email' => 'test2@example.com',
        ]);

        $response->assertStatus(400)->assertJson([
            'message' => 'O campo date birth é obrigatório. (and 3 more errors)',
        ]);
    }
}
