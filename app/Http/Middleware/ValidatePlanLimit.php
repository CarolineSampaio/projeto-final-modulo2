<?php

namespace App\Http\Middleware;

use App\Models\Student;
use App\Traits\HttpResponses;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class ValidatePlanLimit
{
    use HttpResponses;
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $plan = $user->plan;

        if ($plan->description === 'OURO') return $next($request);

        $students = Student::where('user_id', $user->id)->count();

        if ($plan->limit <= $students) return $this->error('Você atingiu o limite de alunos para o seu plano.', Response::HTTP_FORBIDDEN);

        return $next($request);
    }
}
