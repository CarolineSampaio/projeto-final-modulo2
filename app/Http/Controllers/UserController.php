<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = $request->all();

            $request->validate([
                'name' => 'string|required|max:255',
                'email' => 'string|required|email|max:255|unique:users',
                'date_birth' => 'string|required|date_format:Y-m-d',
                'cpf' => 'string|required|max:14|unique:users',
                'password' => 'string|required|min:8|max:32',
                'plan_id' => 'integer|required|exists:plans,id',
            ]);

            $user = User::create($data);

            return $this->response('Usuário cadastrado com sucesso.', Response::HTTP_CREATED, $user);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
