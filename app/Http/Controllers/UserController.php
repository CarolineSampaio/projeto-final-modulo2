<?php

namespace App\Http\Controllers;

use App\Mail\SendWelcomeToNewUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();

        $request->validate([
            'name' => 'string|required|max:255',
            'email' => 'string|required|email|max:255|unique:users',
            'date_birth' => 'string|required|date_format:Y-m-d',
            'cpf' => 'string|required|size:11|regex:/^\d{11}$/|unique:users',
            'password' => 'string|required|min:8|max:32',
            'plan_id' => 'integer|required|exists:plans,id',
        ]);

        $user = User::with('plan')->create($data);

        Mail::to($user->email, $user->name)
            ->send(new SendWelcomeToNewUser($user));

        return $this->response('Usuário cadastrado com sucesso.', Response::HTTP_CREATED, $user);
    }
}
