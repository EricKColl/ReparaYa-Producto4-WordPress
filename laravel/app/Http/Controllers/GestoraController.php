<?php

namespace App\Http\Controllers;

use App\Models\Gestora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * GestoraController
 * Permite al administrador de ReparaYa dar de alta, ver, editar y eliminar gestoras.
 */
class GestoraController extends Controller
{
    // Listado de todas las gestoras
    public function index()
    {
        $gestoras = Gestora::withCount('incidencias')->get();

        return view('gestoras.index', compact('gestoras'));
    }

    // Formulario para crear una gestora nueva
    public function create()
    {
        return view('gestoras.create');
    }

    // Guardar la gestora nueva en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'email'    => 'required|email|unique:gestoras,email',
            'password' => 'required|string|min:6',
            'telefono' => 'nullable|string|max:20',
            'comision' => 'required|numeric|min:0|max:100',
        ]);

        Gestora::create([
            'nombre'   => $request->nombre,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // guardamos la contraseña encriptada
            'telefono' => $request->telefono,
            'comision' => $request->comision,
        ]);

        return redirect()->route('gestoras.index')->with('success', 'Gestora creada correctamente.');
    }

    // Formulario para editar una gestora
    public function edit($id)
    {
        $gestora = Gestora::findOrFail($id);

        return view('gestoras.edit', compact('gestora'));
    }

    // Actualizar datos de la gestora
    public function update(Request $request, $id)
    {
        $gestora = Gestora::findOrFail($id);

        $request->validate([
            'nombre'   => 'required|string|max:255',
            'email'    => 'required|email|unique:gestoras,email,' . $id, // permite mismo email al editar
            'telefono' => 'nullable|string|max:20',
            'comision' => 'required|numeric|min:0|max:100',
        ]);

        $datos = [
            'nombre'   => $request->nombre,
            'email'    => $request->email,
            'telefono' => $request->telefono,
            'comision' => $request->comision,
        ];

        // Solo actualizamos la contraseña si el admin escribió una nueva
        if ($request->filled('password')) {
            $datos['password'] = Hash::make($request->password);
        }

        $gestora->update($datos);

        return redirect()->route('gestoras.index')->with('success', 'Gestora actualizada.');
    }

    // Eliminar una gestora
    public function destroy($id)
    {
        $gestora = Gestora::findOrFail($id);
        $gestora->delete();

        return redirect()->route('gestoras.index')->with('success', 'Gestora eliminada.');
    }
}
