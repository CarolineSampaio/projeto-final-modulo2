<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/api/dashboard');

        $response->assertStatus(200)->assertJson([
            'message' => '',
            'data' => [
                'registered_students' => 0,
                'registered_exercises' => 0,
                'current_user_plan' => 'Plano BRONZE',
                'remaining_students' => 10,
            ]
        ]);
    }
}
