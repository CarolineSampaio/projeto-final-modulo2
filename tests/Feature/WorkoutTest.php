<?php

namespace Tests\Feature;

use App\Models\Exercise;
use App\Models\Student;
use App\Models\User;
use App\Models\Workout;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Http\Response;
use Tests\TestCase;

class WorkoutTest extends TestCase
{
    public function test_user_can_create_workout()
    {
        $user = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user->id]);
        $exercise = Exercise::factory()->create(['user_id' => $user->id]);

        $data = [
            'student_id' => $student->id,
            'exercise_id' => $exercise->id,
            'repetitions' => 10,
            'weight' => 50.5,
            'break_time' => 60,
            'day' => 'SEGUNDA',
            'observations' => 'Observações do treino',
            'time' => 120,
        ];

        $response = $this->actingAs($user)->post('/api/workouts', $data, headers: ['Accept' => 'application/json']);

        $response->assertStatus(201)->assertJson([
            'message' => 'Treino cadastrado com sucesso.',
            'status' => 201,
            'data' => $data,
        ]);
    }

    public function test_user_cannot_create_workout_with_invalid_data()
    {
        $user = User::factory()->create();
        Student::factory()->create(['user_id' => $user->id]);
        Exercise::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post('/api/workouts', [
            'student_id' => null,
            'exercise_id' => null,
            'repetitions' => null,
            'weight' => null,
            'break_time' => null,
            'day' => 'dia inválido',
            'observations' => '',
            'time' => null,
            'id' => 1,
        ], headers: ['Accept' => 'application/json']);

        $response->assertStatus(400)->assertJson([
            'message' => 'O campo student id é obrigatório. (and 7 more errors)',
            'status' => 400,
            'errors' => [],
            'data' => [],
        ]);
    }

    public function test_user_cannot_create_workout_same_day_and_student()
    {
        $user = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user->id]);
        $exercise = Exercise::factory()->create(['user_id' => $user->id]);

        $data = [
            'student_id' => $student->id,
            'exercise_id' => $exercise->id,
            'repetitions' => 10,
            'weight' => 50.5,
            'break_time' => 60,
            'day' => 'SEGUNDA',
            'observations' => 'Observações do treino',
            'time' => 120,
        ];

        $this->actingAs($user)->post('/api/workouts', $data, headers: ['Accept' => 'application/json']);

        $response = $this->actingAs($user)->post('/api/workouts', $data, headers: ['Accept' => 'application/json']);

        $response->assertStatus(409)->assertJson([
            'message' => 'Já existe um treino com esse exercício cadastrado para esse estudante neste dia.',
            'status' => 409,
            'errors' => [],
            'data' => [],
        ]);
    }

    public function test_user_can_list_a_workout()
    {
        $user = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user->id]);
        $exercise = Exercise::factory()->create(['user_id' => $user->id]);

        $workout = Workout::factory()->create([
            'student_id' => $student->id,
            'exercise_id' => $exercise->id,
        ]);

        $response = $this->actingAs($user)->get("/api/students/$student->id/workouts", headers: ['Accept' => 'application/json']);

        $response->assertStatus(200)->assertJson([
            'message' => 'Treinos listados com sucesso',
            'status' => 200,
            'data' => [
                'student_id' => $student->id,
                'student_name' => $student->name,
                'workouts' => [
                    'SEGUNDA' => [$workout->toArray()],
                    'TERÇA' => [],
                    'QUARTA' => [],
                    'QUINTA' => [],
                    'SEXTA' => [],
                    'SÁBADO' => [],
                    'DOMINGO' => [],
                ],
            ],
        ]);
    }

    public function test_user_cannot_list_a_workout_with_invalid_student_id()
    {
        $user = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user->id]);
        $exercise = Exercise::factory()->create(['user_id' => $user->id]);

        Workout::factory()->create([
            'student_id' => $student->id,
            'exercise_id' => $exercise->id,
        ]);

        $response = $this->actingAs($user)->get("/api/students/999/workouts", headers: ['Accept' => 'application/json']);

        $response->assertStatus(404)->assertJson([
            'message' => 'Nenhum estudante encontrado com o ID fornecido',
            'status' => 404,
            'errors' => [],
            'data' => [],
        ]);
    }

    public function test_user_can_export_student_workout()
    {
        $user = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user->id]);
        $exercise = Exercise::factory()->create(['user_id' => $user->id]);

        $workout = Workout::factory()->create([
            'student_id' => $student->id,
            'exercise_id' => $exercise->id,
        ]);
        $workout->load('exercises:id,description');
        $expectedObject = [
            'SEGUNDA' => [$workout->toArray()],
            'TERÇA' => [],
            'QUARTA' => [],
            'QUINTA' => [],
            'SEXTA' => [],
            'SÁBADO' => [],
            'DOMINGO' => [],
        ];
        PDF::shouldReceive('loadView')
            ->withArgs(function ($view, $data) use ($student, $expectedObject) {
                return (
                    $view === 'pdfs.studentWorkoutsPdf' &&
                    $data['student_name'] === $student->name &&
                    $data['workouts'] == $expectedObject
                );
            })
            ->once()
            ->andReturnSelf()
            ->getMock()
            ->shouldReceive('stream')
            ->with('StudentWorkouts.pdf')
            ->once()
            ->andReturn(new Response());

        $response = $this->actingAs($user)->get(
            "/api/students/export?id_do_estudante={$student->id}",
            headers: ['Accept' => 'application/json']
        );

        $response->assertStatus(200);
    }

    public function test_user_cannot_export_student_workout_with_invalid_student_id()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(
            "/api/students/export?id_do_estudante=999",
            headers: ['Accept' => 'application/json']
        );

        $response->assertStatus(404)->assertJson([
            'message' => 'Nenhum estudante encontrado com o ID fornecido',
            'status' => 404,
            'errors' => [],
            'data' => [],
        ]);
    }
}
