<?php

namespace Tests\Feature;

use App\Models\Exercise;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExercisesTest extends TestCase
{
    public function test_user_can_create_exercise()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/exercises', [
            'description' => 'Supino Reto',
        ]);

        $response->assertStatus(201)->assertJson([
            'message' => 'Exercício cadastrado com sucesso.',
            'status' => 201,
            'data' => [
                'description' => 'supino reto',
            ],
        ]);
    }

    public function test_user_cannot_create_exercise_with_invalid_data()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/exercises', [
            'description' => '',
        ]);

        $response->assertStatus(400)->assertJson([
            'message' => 'O description deve ser uma string válida. (and 1 more error)',
            'status' => 400,
            'errors' => [],
            'data' => [],
        ]);
    }

    public function test_user_can_list_exercises()
    {
        $user = User::factory()->create();
        $exercise = Exercise::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/api/exercises');

        $response->assertStatus(200)->assertJson([
            'message' => 'Exercícios cadastrados por ' . $user->name . ', listados com sucesso',
            'status' => 200,
            'data' => [$exercise->makeHidden(['user_id'])->toArray()],
        ]);
    }

    public function test_user_cannot_list_exercises_different_user_id()
    {
        $user = User::factory()->create();
        Exercise::factory()->create(['user_id' => $user->id]);

        $user2 = User::factory()->create();

        $response = $this->actingAs($user2)->get('/api/exercises');

        $response->assertStatus(200)->assertJson([
            'message' => 'Exercícios cadastrados por ' . $user2->name . ', listados com sucesso',
            'status' => 200,
            'data' => [],
        ]);
    }

    public function test_user_can_delete_exercise()
    {
        $user = User::factory()->create();
        $exercise = Exercise::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete('/api/exercises/' . $exercise->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('exercises', ['id' => $exercise->id]);
    }
}
