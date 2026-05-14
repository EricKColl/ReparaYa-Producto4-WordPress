<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use App\Models\Tecnico;
use App\Models\Incidencia;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class EspecialidadController extends Controller
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
                ->with('error', 'Solo el administrador puede acceder a la gestión de especialidades.');
        }

        return null;
    }

    public function index()
    {
        if ($bloqueo = $this->bloquearSiNoEsAdmin()) {
            return $bloqueo;
        }

        $especialidades = Especialidad::orderBy('id')->get();

        return view('especialidades.index', compact('especialidades'));
    }

    public function create()
    {
        if ($bloqueo = $this->bloquearSiNoEsAdmin()) {
            return $bloqueo;
        }

        return view('especialidades.create');
    }

    public function store(Request $request)
    {
        if ($bloqueo = $this->bloquearSiNoEsAdmin()) {
            return $bloqueo;
        }

        $request->validate([
            'nombre_especialidad' => 'required|string|max:100'
        ]);

        Especialidad::create([
            'nombre_especialidad' => $request->nombre_especialidad
        ]);

        return redirect()
            ->route('especialidades.index')
            ->with('success', 'Especialidad creada correctamente.');
    }

    public function edit($id)
    {
        if ($bloqueo = $this->bloquearSiNoEsAdmin()) {
            return $bloqueo;
        }

        $especialidad = Especialidad::findOrFail($id);

        return view('especialidades.edit', compact('especialidad'));
    }

    public function update(Request $request, $id)
    {
        if ($bloqueo = $this->bloquearSiNoEsAdmin()) {
            return $bloqueo;
        }

        $especialidad = Especialidad::findOrFail($id);

        $request->validate([
            'nombre_especialidad' => 'required|string|max:100'
        ]);

        $especialidad->update([
            'nombre_especialidad' => $request->nombre_especialidad
        ]);

        return redirect()
            ->route('especialidades.index')
            ->with('success', 'Especialidad actualizada correctamente.');
    }

    public function destroy($id)
    {
        if ($bloqueo = $this->bloquearSiNoEsAdmin()) {
            return $bloqueo;
        }

        $especialidad = Especialidad::findOrFail($id);

        $tecnicosAsociados = Tecnico::where('especialidad_id', $especialidad->id)->count();
        $incidenciasAsociadas = Incidencia::where('especialidad_id', $especialidad->id)->count();

        if ($tecnicosAsociados > 0) {
            return redirect()
                ->route('especialidades.index')
                ->with('error', 'No se puede eliminar esta especialidad porque hay técnicos asociados a ella. Primero habría que reasignar esos técnicos a otra especialidad.');
        }

        if ($incidenciasAsociadas > 0) {
            return redirect()
                ->route('especialidades.index')
                ->with('error', 'No se puede eliminar esta especialidad porque hay incidencias asociadas a ella. Para mantener la trazabilidad, primero habría que reasignar o eliminar esas incidencias.');
        }

        try {
            $especialidad->delete();

            return redirect()
                ->route('especialidades.index')
                ->with('success', 'Especialidad eliminada correctamente.');
        } catch (QueryException $e) {
            return redirect()
                ->route('especialidades.index')
                ->with('error', 'No se puede eliminar esta especialidad porque está relacionada con otros datos del sistema.');
        }
    }
}