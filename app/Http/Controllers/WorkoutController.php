<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkoutController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = $request->all();

            $request->validate([
                'student_id' => 'required|exists:students,id',
                'exercise_id' => 'required|exists:exercises,id',
                'repetitions' => 'required|integer',
                'weight' => 'required|numeric',
                'break_time' => 'required|integer',
                'day' => 'required|in:SEGUNDA,TERÇA,QUARTA,QUINTA,SEXTA,SÁBADO,DOMINGO',
                'observations' => 'string',
                'time' => 'required|integer',
            ]);

            $workoutExists = Workout::where('student_id', $data['student_id'])
                ->where('day', $data['day'])
                ->first();

            if ($workoutExists) return $this->error('Já existe um treino cadastrado para esse aluno neste dia.', Response::HTTP_CONFLICT);

            $workout = Workout::create($data);

            return $this->response('Treino cadastrado com sucesso.', Response::HTTP_CREATED, $workout);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function show($id)
    {
        try {
            $workouts = Workout::where('student_id', $id)
                ->with(['students' => fn ($query) => $query->select('id', 'name')])
                ->orderby('created_at')
                ->get()
                ->groupBy('day')
                ->sortBy(fn ($grouped, $day) => array_search($day, ['SEGUNDA', 'TERÇA', 'QUARTA', 'QUINTA', 'SEXTA', 'SÁBADO', 'DOMINGO']));

            $student = $workouts->first()->first()->students;

            return $this->response("Treinos listados com sucesso", Response::HTTP_OK, [
                'student_id' => $student->id,
                'student_name' => $student->name,
                'workouts' => $workouts
            ]);


            return $workouts->makeHidden(['students']);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
