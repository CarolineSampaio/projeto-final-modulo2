<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Tests\TestCase;

class StudentTest extends TestCase
{
    public function test_user_can_create_student()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/students', [
            'name' => 'Test User',
            'email' => 'test4@example.com',
            'date_birth' => '1990-01-01',
            'cpf' => '12345678900',
            'contact' => '12345678900',
            'cep' => '12345678900',
            'street' => 'Rua do teste',
            'state' => 'SP',
            'neighborhood' => 'Bairro do teste',
            'city' => 'Cidade do teste',
            'number' => '123',
        ]);

        $response->assertStatus(201)->assertJson([
            'message' => 'Estudante cadastrado com sucesso.',
            'status' => 201,
            'data' => [
                'name' => 'Test User',
                'email' => 'test4@example.com',
                'date_birth' => '1990-01-01',
                'cpf' => '12345678900',
                'contact' => '12345678900',
                'cep' => '12345678900',
                'street' => 'Rua do teste',
                'state' => 'SP',
                'neighborhood' => 'Bairro do teste',
                'city' => 'Cidade do teste',
                'number' => '123',
            ],
        ]);
    }

    public function test_user_cannot_create_student_with_invalid_data()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/students', [
            'name' => '',
            'email' => '',
            'date_birth' => '',
            'cpf' => '',
            'contact' => '',
            'cep' => '',
            'street' => '',
            'state' => '',
            'neighborhood' => '',
            'city' => '',
            'number' => '',
        ]);

        $response->assertStatus(400)->assertJson([
            'message' => 'O name deve ser uma string válida. (and 15 more errors)',
            'status' => 400,
            'errors' => [],
            'data' => [],
        ]);
    }

    public function test_user_cannot_create_student_plan_BRONZE_limit_exceeded()
    {
        $user = User::factory()->create(['plan_id' => 1]);
        Student::factory()->count(10)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post('/api/students', [
            'name' => 'Test User',
            'email' => 'test4@example.com',
            'date_birth' => '1990-01-01',
            'cpf' => '12345678900',
            'contact' => '12345678900',
        ]);

        $response->assertStatus(403)->assertJson([
            'message' => 'Você atingiu o limite de estudantes para o seu plano.',
            'status' => 403,
            'errors' => [],
            'data' => [],
        ]);
    }

    public function test_user_cannot_create_student_plan_PRATA_limit_exceeded()
    {
        $user = User::factory()->create(['plan_id' => 2]);
        Student::factory()->count(20)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post('/api/students', [
            'name' => 'Test User',
            'email' => 'test4@example.com',
            'date_birth' => '1990-01-01',
            'cpf' => '12345678900',
            'contact' => '12345678900',
        ]);

        $response->assertStatus(403)->assertJson([
            'message' => 'Você atingiu o limite de estudantes para o seu plano.',
            'status' => 403,
            'errors' => [],
            'data' => [],
        ]);
    }

    public function test_user_can_create_student_plan_OURO_ilimited()
    {
        $user = User::factory()->create(['plan_id' => 3]);
        Student::factory()->count(21)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post('/api/students', [
            'name' => 'Test User',
            'email' => 'test4@example.com',
            'date_birth' => '1990-01-01',
            'cpf' => '12345678900',
            'contact' => '12345678900',
        ]);

        $response->assertStatus(201)->assertJson([
            'message' => 'Estudante cadastrado com sucesso.',
            'status' => 201,
        ]);
    }

    public function test_user_can_list_all_students_associated_with_their_user_id()
    {
        $user = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user->id]);

        $user2 = User::factory()->create();
        Student::factory()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user)->get('/api/students');

        $response->assertStatus(200)->assertJson(
            [
                'message' => 'Estudantes listados com sucesso.',
                'status' => 200,
                'data' => [$student->makehidden(['user_id'])->toArray()]
            ],
        );
    }

    public function test_user_can_list_a_student()
    {
        $user = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get("/api/students/{$student->id}");

        $response->assertStatus(200)->assertJsonStructure([
            'message',
            'status',
            'data' => [
                'id',
                'name',
                'email',
                'date_birth',
                'cpf',
                'contact',
                'address' => [
                    'cep',
                    'street',
                    'number',
                    'neighborhood',
                    'city',
                    'state',
                ],
            ],
        ]);
    }

    public function test_user_can_search()
    {
        $user = User::factory()->create();
        $student1 = Student::factory()->create(['user_id' => $user->id, 'name' => 'Teste 1']);
        Student::factory()->create(['user_id' => $user->id, 'name' => 'Teste 2']);
        Student::factory()->create(['user_id' => $user->id, 'name' => 'Teste 3']);

        $response = $this->actingAs($user)->get('/api/students?pesquisa_geral=Teste 1');

        $response->assertStatus(200)->assertJson([
            'message' => 'Estudantes listados com sucesso.',
            'status' => 200,
            'data' => [$student1->makehidden(['user_id'])->toArray()],
        ]);
    }

    public function test_user_can_update_student_information()
    {
        $user = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user->id]);
        $newData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        $response = $this->actingAs($user)->put("/api/students/{$student->id}", $newData);

        $updatedStudent = Student::find($student->id);
        $this->assertEquals('Updated Name', $updatedStudent->name);
        $this->assertEquals('updated@example.com', $updatedStudent->email);

        $response->assertStatus(200)->assertJson([
            'message' => 'Estudante atualizado com sucesso.',
            'status' => 200,
            'data' => $updatedStudent->makehidden(['user_id'])->toArray(),
        ]);
    }

    public function test_user_cannot_update_student_information_with_invalid_data()
    {
        $user = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user->id]);
        $newData = [
            'name' => '',
            'email' => '',
        ];

        $response = $this->actingAs($user)->put("/api/students/{$student->id}", $newData);

        $response->assertStatus(400)->assertJson([
            'message' => 'O name deve ser uma string válida. (and 2 more errors)',
            'status' => 400,
            'errors' => [],
            'data' => [],
        ]);
    }

    public function test_user_cannot_update_student_belonging_to_another_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $student = Student::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)->put("/api/students/{$student->id}", [
            'name' => 'Novo Nome',
        ]);

        $response->assertStatus(403)->assertJson([
            'message' => 'Ação não permitida.',
            'status' => 403,
        ]);

        $this->assertDatabaseMissing('students', [
            'id' => $student->id,
            'name' => 'Novo Nome',
        ]);
    }

    public function test_user_can_delete_student()
    {
        $user = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete("/api/students/{$student->id}");

        $response->assertStatus(204);

        $this->assertNull(Student::find($student->id));
    }

    public function test_user_cannot_delete_student_belonging_to_another_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $student = Student::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)->delete("/api/students/{$student->id}");

        $response->assertStatus(403)->assertJson([
            'message' => 'Ação não permitida.',
            'status' => 403,
        ]);

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
        ]);
    }
}
