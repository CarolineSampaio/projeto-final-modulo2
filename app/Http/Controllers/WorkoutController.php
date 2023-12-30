<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Workout;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkoutController extends Controller
{
    public function store(Request $request)
    {
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
            ->where('exercise_id', $data['exercise_id'])
            ->exists();

        if ($workoutExists) return $this->error('Já existe um treino com esse exercício cadastrado para esse estudante neste dia.', Response::HTTP_CONFLICT);

        $workout = Workout::create($data);
        return $this->response('Treino cadastrado com sucesso.', Response::HTTP_CREATED, $workout);
    }

    public function show($id)
    {
        $student = Student::find($id);
        if (!$student) return $this->error('Nenhum estudante encontrado com o ID fornecido', Response::HTTP_NOT_FOUND);

        $sortedWorkouts = $this->getWorkouts($student->id);

        return $this->response('Treinos listados com sucesso', Response::HTTP_OK, [
            'student_id' => $student->id,
            'student_name' => $student->name,
            'workouts' => $sortedWorkouts,
        ]);
    }

    public function exportStudentWorkouts(Request $request)
    {
        $id = $request->get('id_do_estudante');
        $student = Student::find($id);

        if (!$student) return $this->error('Nenhum estudante encontrado com o ID fornecido', Response::HTTP_NOT_FOUND);

        $sortedWorkouts = $this->getWorkouts($student->id);
        $name = $student->name;

        $pdf = PDF::loadView('pdfs.studentWorkoutsPdf', [
            'student_name' => $name,
            'workouts' => $sortedWorkouts,
        ]);

        return $pdf->stream('StudentWorkouts.pdf');
    }

    private function getWorkouts($student_id)
    {
        $workouts = Workout::where('student_id', $student_id)
            ->with('exercises:id,description')
            ->orderby('created_at')
            ->get()
            ->groupBy('day')->toArray();

        $DaysOfWeek = ['SEGUNDA', 'TERÇA', 'QUARTA', 'QUINTA', 'SEXTA', 'SÁBADO', 'DOMINGO'];

        $sortedWorkouts = array_reduce($DaysOfWeek, function ($result, $day) use ($workouts) {
            $result[$day] = isset($workouts[$day]) ? $workouts[$day] : [];
            return $result;
        });

        return $sortedWorkouts;
    }
}
