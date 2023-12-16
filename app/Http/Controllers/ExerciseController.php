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
}
