<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    public function login(Request $request){

        $credenciais = [
            'email' => $request->email,
            'password' => $request->senha
        ];

        $token = auth('api')->attempt($credenciais);

        if($token) {
            return response()->json(['token' => $token]);
        } else {
            return response()->json(['erro' => 'Usuario ou senha invalido'], 401);
        }

        return $token;
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['mensagem' => 'Logout realizado com sucesso']);
    }

    public function refresh()
    {
        $token = auth('api')->refresh();

        return response()->json(['token' => $token]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
