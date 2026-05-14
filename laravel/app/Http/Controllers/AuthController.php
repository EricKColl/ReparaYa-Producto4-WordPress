<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()->with('error', 'Credenciales incorrectas');
        }

        session([
            'usuario_id' => $usuario->id,
            'usuario_nombre' => $usuario->nombre,
            'usuario_rol' => $usuario->rol
        ]);

        return redirect()->route('home');
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('login');
    }
}