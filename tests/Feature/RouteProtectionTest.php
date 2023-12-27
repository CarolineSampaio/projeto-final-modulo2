<?php

namespace Tests\Feature;

use Tests\TestCase;

class RouteProtectionTest extends TestCase
{
    public function test_unauthenticated_user_cannot_access_protected_routes()
    {
        $protectedRoutes = [
            'dashboard' => 'get',
            'exercises' => 'post',
            'exercises/1' => 'get',
            'exercises/1' => 'put',
            'exercises/1' => 'delete',
            'workouts' => 'post',
            '1/workouts' => 'get',
            'students' => 'get',
            'students' => 'post',
            'students/1' => 'get',
            'students/1' => 'put',
            'students/1' => 'delete',
        ];

        foreach ($protectedRoutes as $route => $method) {
            $response = $this->{$method}('/api/' . $route, headers: ['Accept' => 'application/json']);
            $response->assertStatus(401)->assertJson([
                'message' => 'Unauthenticated.',
            ]);
        }
    }
}
