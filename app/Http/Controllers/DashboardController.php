<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Student;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();

            $amountStudents = Student::where('user_id', $user->id)->count();
            $amountExercises = Exercise::where('user_id', $user->id)->count();
            $currentPlan = $user->plan->description;
            $planLimit = $user->plan->limit;
            $remainingPlan = $planLimit ? $planLimit - $amountStudents : null;

            return $this->response('', Response::HTTP_OK, [
                'registered_students' => $amountStudents,
                'registered_exercises' => $amountExercises,
                'current_user_plan' => "Plano $currentPlan",
                'remaining_students' => $remainingPlan

            ]);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
