<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();

        $request->validate([
            'name' => 'string|required|max:255',
            'email' => 'string|required|email|max:255|unique:students',
            'date_birth' => 'string|required|date_format:Y-m-d',
            'cpf' => 'string|required|size:11|regex:/^\d{11}$/|unique:students',
            'contact' => 'string|required|max:20',
            'cep' => 'string|max:20',
            'street' => 'string|max:30',
            'number' => 'string|max:30',
            'neighborhood' => 'string|max:50',
            'city' => 'string|max:50',
            'state' => 'string|max:2',
        ]);

        $user_id = auth()->user()->id;
        $data['user_id'] = $user_id;

        $student = Student::create($data);
        return $this->response('Estudante cadastrado com sucesso.', Response::HTTP_CREATED, $student);
    }

    public function index(Request $request)
    {
        $search = $request->get('pesquisa');
        $user_id = auth()->user()->id;

        $searchStudents = Student::query()
            ->where('user_id', $user_id)
            ->orderBy('name', 'asc');

        if ($search) {
            $searchStudents->where(function ($query) use ($search) {
                $query->where('name', 'ilike', "%$search%")
                    ->orWhere('cpf', 'like', "%$search%")
                    ->orWhere('email', 'ilike', "%$search%");
            });
        }

        $students = $searchStudents->get();

        return $this->response('Estudantes listados com sucesso.', Response::HTTP_OK, $students->makehidden(['user_id'])->toArray());
    }

    public function show($id)
    {
        $student = Student::find($id);

        if (!$student) return $this->error('Nenhum aluno encontrado com o ID fornecido.', Response::HTTP_NOT_FOUND);

        $studentArray = collect($student->toArray())
            ->only(['id', 'name', 'email', 'date_birth', 'cpf', 'contact'])
            ->merge(['address' => $student->only(['cep', 'street', 'number', 'neighborhood', 'city', 'state'])])
            ->toArray();

        return $this->response('Estudante listado com sucesso.', Response::HTTP_OK, $studentArray);
    }

    public function destroy($id)
    {
        try {
            $student = Student::find($id);

            if (!$student) {
                return $this->error('Aluno não encontrado!', Response::HTTP_NOT_FOUND);
            }

            $user = auth()->user();

            if ($student->user_id != $user->id) {
                return $this->error('Ação não permitida.', Response::HTTP_FORBIDDEN);
            }

            $student->delete();

            return response('', Response::HTTP_NO_CONTENT);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $data = $request->all();

            $request->validate([
                'name' => 'string|max:255',
                'email' => 'string|email|max:255|unique:students',
                'date_birth' => 'string|date_format:Y-m-d',
                'cpf' => 'string|max:14|unique:students',
                'contact' => 'string|max:20',
                'cep' => 'string|max:20',
                'street' => 'string|max:30',
                'number' => 'string|max:30',
                'neighborhood' => 'string|max:50',
                'city' => 'string|max:50',
                'state' => 'string|max:2',
            ]);

            $student = Student::find($id);

            if (!$student) {
                return $this->error('Aluno não encontrado!', Response::HTTP_NOT_FOUND);
            }

            $user = auth()->user();

            if ($student->user_id != $user->id) {
                return $this->error('Ação não permitida.', Response::HTTP_FORBIDDEN);
            }

            $student->update($data);

            return response('Aluno atualizado com sucesso.', Response::HTTP_OK);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
