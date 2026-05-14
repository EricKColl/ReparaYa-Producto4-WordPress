<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Tecnico;
use App\Models\Incidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class UsuarioController extends Controller
{
    private function bloquearSiNoEsAdmin()
    {
        if (!session()->has('usuario_id')) {
            return redirect()
                ->route('login')
                ->with('error', 'Debes iniciar sesión para acceder a esta sección.');
        }

        if (session('usuario_rol') !== 'admin') {
            return redirect()
                ->route('home')
                ->with('error', 'Solo el administrador puede acceder a la gestión de usuarios.');
        }

        return null;
    }

    public function index()
    {
        if ($bloqueo = $this->bloquearSiNoEsAdmin()) {
            return $bloqueo;
        }

        $usuarios = Usuario::orderBy('id')->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        if ($bloqueo = $this->bloquearSiNoEsAdmin()) {
            return $bloqueo;
        }

        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        if ($bloqueo = $this->bloquearSiNoEsAdmin()) {
            return $bloqueo;
        }

        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:usuarios,email',
            'password' => 'required|string|min:6',
            'rol' => 'required|in:admin,tecnico,particular',
            'telefono' => 'nullable|string|max:20'
        ]);

        Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'telefono' => $request->telefono,
            'created_at' => now()
        ]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit($id)
    {
        if ($bloqueo = $this->bloquearSiNoEsAdmin()) {
            return $bloqueo;
        }

        $usuario = Usuario::findOrFail($id);

        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        if ($bloqueo = $this->bloquearSiNoEsAdmin()) {
            return $bloqueo;
        }

        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:usuarios,email,' . $usuario->id,
            'password' => 'nullable|string|min:6',
            'rol' => 'required|in:admin,tecnico,particular',
            'telefono' => 'nullable|string|max:20'
        ]);

        $datos = [
            'nombre' => $request->nombre,
            'email' => $request->email,
            'rol' => $request->rol,
            'telefono' => $request->telefono
        ];

        if ($request->filled('password')) {
            $datos['password'] = Hash::make($request->password);
        }

        $usuario->update($datos);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        if ($bloqueo = $this->bloquearSiNoEsAdmin()) {
            return $bloqueo;
        }

        $usuario = Usuario::findOrFail($id);

        if ((int) session('usuario_id') === (int) $usuario->id) {
            return redirect()
                ->route('usuarios.index')
                ->with('error', 'No puedes eliminar el usuario con el que tienes la sesión iniciada.');
        }

        $incidenciasComoCliente = Incidencia::where('cliente_id', $usuario->id)->count();
        $fichaTecnica = Tecnico::where('usuario_id', $usuario->id)->first();

        if ($incidenciasComoCliente > 0) {
            return redirect()
                ->route('usuarios.index')
                ->with('error', 'No se puede eliminar este usuario porque tiene incidencias asociadas como cliente. Para mantener la trazabilidad del sistema, primero habría que reasignar o eliminar esas incidencias.');
        }

        if ($fichaTecnica) {
            return redirect()
                ->route('usuarios.index')
                ->with('error', 'No se puede eliminar este usuario porque está vinculado a una ficha de técnico. Primero elimina o reasigna la ficha técnica correspondiente.');
        }

        try {
            $usuario->delete();

            return redirect()
                ->route('usuarios.index')
                ->with('success', 'Usuario eliminado correctamente.');
        } catch (QueryException $e) {
            return redirect()
                ->route('usuarios.index')
                ->with('error', 'No se puede eliminar este usuario porque está relacionado con otros datos del sistema.');
        }
    }
}