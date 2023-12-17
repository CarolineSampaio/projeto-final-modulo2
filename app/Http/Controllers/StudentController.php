<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = $request->all();

            $request->validate([
                'name' => 'string|required|max:255',
                'email' => 'string|required|email|max:255|unique:students',
                'date_birth' => 'string|required|date_format:Y-m-d',
                'cpf' => 'string|required|max:14|unique:students',
                'contact' => 'string|required|max:20',
                'cep' => 'string',
                'street' => 'string',
                'state' => 'string',
                'neighborhood' => 'string',
                'city' => 'string',
                'number' => 'string',
            ]);

            $user_id = auth()->user()->id;
            $data['user_id'] = $user_id;

            $student = Student::create($data);

            return $this->response('Usuário cadastrado com sucesso.', Response::HTTP_CREATED, $student);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
