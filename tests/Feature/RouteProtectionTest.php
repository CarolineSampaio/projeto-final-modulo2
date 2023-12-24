<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RouteProtectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_protected_routes()
    {
        $protectedRoutes = [
            'dashboard' => 'get',
            'exercises' => 'post',
            'exercises/1' => 'get', // assuming 1 is a valid exercise ID
            'exercises/1' => 'put', // assuming 1 is a valid exercise ID
            'exercises/1' => 'delete', // assuming 1 is a valid exercise ID
            'workouts' => 'post',
            '1/workouts' => 'get', // assuming 1 is a valid user ID
            'students' => 'get',
            'students' => 'post',
            'students/1' => 'get', // assuming 1 is a valid student ID
            'students/1' => 'put', // assuming 1 is a valid student ID
            'students/1' => 'delete', // assuming 1 is a valid student ID
        ];

        foreach ($protectedRoutes as $route => $method) {
            $response = $this->{$method}('/api/' . $route, headers: ['Accept' => 'application/json']);
            $response->assertStatus(401)->assertJson([
                'message' => 'Unauthenticated.',
            ]);
        }
    }
}
