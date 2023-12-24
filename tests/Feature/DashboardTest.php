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
        $userPlanMapping = [
            1 => ['name' => 'BRONZE', 'remaining_students' => 10],
            2 => ['name' => 'PRATA', 'remaining_students' => 20],
            3 => ['name' => 'OURO', 'remaining_students' => null],
        ];

        foreach ($userPlanMapping as $planId => $plan) {
            $user = User::factory()->create(['plan_id' => $planId]);
            $response = $this->actingAs($user)->get('/api/dashboard');
            $response->assertStatus(200)->assertJson([
                'message' => '',
                'data' => [
                    'registered_students' => 0,
                    'registered_exercises' => 0,
                    'current_user_plan' => 'Plano ' . $plan['name'],
                    'remaining_students' => $plan['remaining_students'],
                ]
            ]);
        }
    }
}
