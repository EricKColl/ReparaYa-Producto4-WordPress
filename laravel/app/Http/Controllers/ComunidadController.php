<?php

namespace App\Http\Controllers;

use App\Models\Comunidad;
use App\Models\Gestora;
use App\Models\Incidencia;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ComunidadController extends Controller
{
    public function index()
    {
        if (session('usuario_rol') !== 'admin') {
            return redirect()->route('home')
                ->with('error', 'Solo el administrador puede gestionar comunidades.');
        }

        $comunidades = Comunidad::with('gestora')
            ->orderBy('id')
            ->get();

        return view('comunidades.index', compact('comunidades'));
    }

    public function create()
    {
        if (session('usuario_rol') !== 'admin') {
            return redirect()->route('home')
                ->with('error', 'Solo el administrador puede crear comunidades.');
        }

        $gestoras = Gestora::orderBy('nombre')->get();

        return view('comunidades.create', compact('gestoras'));
    }

    public function store(Request $request)
    {
        if (session('usuario_rol') !== 'admin') {
            return redirect()->route('home')
                ->with('error', 'Solo el administrador puede guardar comunidades.');
        }

        $request->validate([
            'gestora_id'        => 'required|exists:gestoras,id',
            'nombre'            => 'required|string|max:255',
            'direccion'         => 'required|string|max:255',
            'telefono_contacto' => 'required|string|max:20',
            'zona'              => 'required|string|max:100',
        ]);

        Comunidad::create([
            'gestora_id'        => $request->gestora_id,
            'nombre'            => $request->nombre,
            'direccion'         => $request->direccion,
            'telefono_contacto' => $request->telefono_contacto,
            'zona'              => $request->zona,
        ]);

        return redirect()
            ->route('comunidades.index')
            ->with('success', 'Comunidad creada correctamente.');
    }

    public function edit($id)
    {
        if (session('usuario_rol') !== 'admin') {
            return redirect()->route('home')
                ->with('error', 'Solo el administrador puede editar comunidades.');
        }

        $comunidad = Comunidad::findOrFail($id);

        $gestoras = Gestora::orderBy('nombre')->get();

        return view('comunidades.edit', compact('comunidad', 'gestoras'));
    }

    public function update(Request $request, $id)
    {
        if (session('usuario_rol') !== 'admin') {
            return redirect()->route('home')
                ->with('error', 'Solo el administrador puede actualizar comunidades.');
        }

        $comunidad = Comunidad::findOrFail($id);

        $request->validate([
            'gestora_id'        => 'required|exists:gestoras,id',
            'nombre'            => 'required|string|max:255',
            'direccion'         => 'required|string|max:255',
            'telefono_contacto' => 'required|string|max:20',
            'zona'              => 'required|string|max:100',
        ]);

        $comunidad->update([
            'gestora_id'        => $request->gestora_id,
            'nombre'            => $request->nombre,
            'direccion'         => $request->direccion,
            'telefono_contacto' => $request->telefono_contacto,
            'zona'              => $request->zona,
        ]);

        return redirect()
            ->route('comunidades.index')
            ->with('success', 'Comunidad actualizada correctamente.');
    }

    public function destroy($id)
    {
        if (session('usuario_rol') !== 'admin') {
            return redirect()->route('home')
                ->with('error', 'Solo el administrador puede eliminar comunidades.');
        }

        $comunidad = Comunidad::findOrFail($id);

        $incidenciasAsociadas = Incidencia::where('comunidad_id', $comunidad->id)->count();

        if ($incidenciasAsociadas > 0) {
            return redirect()
                ->route('comunidades.index')
                ->with('error', 'No se puede eliminar esta comunidad porque tiene avisos o incidencias asociadas. Para mantener la trazabilidad, primero habría que eliminar o reasignar esos avisos.');
        }

        try {
            $comunidad->delete();

            return redirect()
                ->route('comunidades.index')
                ->with('success', 'Comunidad eliminada correctamente.');
        } catch (QueryException $e) {
            return redirect()
                ->route('comunidades.index')
                ->with('error', 'No se puede eliminar esta comunidad porque está relacionada con otros datos del sistema.');
        }
    }
}