<?php

namespace Tests\Feature;

use App\Models\Exercise;
use App\Models\Student;
use App\Models\User;
use App\Models\Workout;
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

    public function test_user_cannot_delete_exercise_with_invalid_id()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete('/api/exercises/999');

        $response->assertStatus(404)->assertJson([
            'message' => 'Exercício não encontrado!',
            'status' => 404,
            'errors' => [],
            'data' => [],
        ]);
    }

    public function test_user_cannot_delete_exercise_different_user_id()
    {
        $user = User::factory()->create();
        $exercise = Exercise::factory()->create(['user_id' => $user->id]);

        $user2 = User::factory()->create();

        $response = $this->actingAs($user2)->delete('/api/exercises/' . $exercise->id);

        $response->assertStatus(403)->assertJson([
            'message' => 'Ação não permitida.',
            'status' => 403,
            'errors' => [],
            'data' => [],
        ]);

        $this->assertDatabaseHas('exercises', ['id' => $exercise->id]);
    }

    public function test_user_cannot_delete_exercise_with_workout()
    {
        $user = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user->id]);
        $exercise = Exercise::factory()->create(['user_id' => $user->id]);

        Workout::factory()->create([
            'student_id' => $student->id,
            'exercise_id' => $exercise->id,
        ]);

        $response = $this->actingAs($user)->delete('/api/exercises/' . $exercise->id);

        $response->assertStatus(409)->assertJson([
            'message' => 'Conflito ao realizar exclusão. Este exercício está vinculado a um ou mais treinos.',
            'status' => 409,
            'errors' => [],
            'data' => [],
        ]);

        $this->assertDatabaseHas('exercises', ['id' => $exercise->id]);
    }
}
