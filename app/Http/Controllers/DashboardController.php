<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Student;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $amountStudents = Student::where('user_id', $user->id)->count();
        $amountExercises = Exercise::where('user_id', $user->id)->count();
        $currentPlan = $user->plan->description;
        $planLimit = $user->plan->limit;
        $remainingPlan = $planLimit ? $planLimit - $amountStudents : null;

        return $this->response('', Response::HTTP_OK, [
            'registered_students' => $amountStudents,
            'registered_exercises' => $amountExercises,
            'current_user_plan' => "Plano " . ucfirst(strtolower($currentPlan)),
            'remaining_students' => $remainingPlan
        ]);
    }
}
