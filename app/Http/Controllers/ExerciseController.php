<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExerciseController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data =  $request->only(['description']);

            $request->validate([
                'description' => 'string|required|max:255',
            ]);

            $data['description'] = strtolower($data['description']);
            $data['user_id'] = auth()->user()->id;

            $exerciseExists = Exercise::where('description', $data['description'])
                ->where('user_id', $data['user_id'])
                ->first();

            if ($exerciseExists) {
                return $this->error('Conflito ao realizar cadastro. Este exercício já existe em sua lista.', Response::HTTP_CONFLICT);
            }

            $exercise = Exercise::create($data);

            return $this->response('Exercício cadastrado com sucesso.', Response::HTTP_CREATED, $exercise->makeHidden(['user_id']));
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function index()
    {
        $user = auth()->user();
        $exercises = Exercise::where('user_id', $user->id)
            ->orderBy('description', 'asc')
            ->get();

        return $this->response("Exercícios cadastrados por $user->name, listados com sucesso", Response::HTTP_OK, $exercises->makeHidden(['user_id'])->toArray());
    }

    public function destroy($id)
    {
        try {
            $exercise = Exercise::find($id);

            if (!$exercise) {
                return $this->error('Exercício não encontrado!', Response::HTTP_NOT_FOUND);
            }

            $user = auth()->user();

            if ($exercise->user_id != $user->id) {
                return $this->error('Ação não permitida.', Response::HTTP_FORBIDDEN);
            }

            if ($exercise->workouts()->count() > 0) {
                return $this->error('Conflito ao realizar exclusão. Este exercício está vinculado a um ou mais treinos.', Response::HTTP_CONFLICT);
            }

            $exercise->delete();

            return response('', Response::HTTP_NO_CONTENT);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
