<?php

namespace App\Http\Controllers;

use App\Models\Tecnico;
use App\Models\Usuario;
use App\Models\Especialidad;
use App\Models\Incidencia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class TecnicoController extends Controller
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
                ->with('error', 'Solo el administrador puede acceder a la gestión de técnicos.');
        }

        return null;
    }

    public function index()
    {
        if ($bloqueo = $this->bloquearSiNoEsAdmin()) {
            return $bloqueo;
        }

        $tecnicos = Tecnico::with(['usuario', 'especialidad'])
            ->orderBy('id')
            ->get();

        return view('tecnicos.index', compact('tecnicos'));
    }

    public function create()
    {
        if ($bloqueo = $this->bloquearSiNoEsAdmin()) {
            return $bloqueo;
        }

        $usuarios = Usuario::where('rol', 'tecnico')
            ->whereDoesntHave('tecnico')
            ->orderBy('nombre')
            ->get();

        $especialidades = Especialidad::orderBy('nombre_especialidad')->get();

        return view('tecnicos.create', compact('usuarios', 'especialidades'));
    }

    public function store(Request $request)
    {
        if ($bloqueo = $this->bloquearSiNoEsAdmin()) {
            return $bloqueo;
        }

        $request->validate([
            'usuario_id' => [
                'required',
                'integer',
                Rule::exists('usuarios', 'id')->where(function ($query) {
                    return $query->where('rol', 'tecnico');
                }),
                Rule::unique('tecnicos', 'usuario_id')
            ],
            'nombre_completo' => 'required|string|max:100',
            'especialidad_id' => 'required|integer|exists:especialidades,id',
            'disponible' => 'required|boolean'
        ]);

        Tecnico::create([
            'usuario_id' => $request->usuario_id,
            'nombre_completo' => $request->nombre_completo,
            'especialidad_id' => $request->especialidad_id,
            'disponible' => $request->disponible
        ]);

        return redirect()
            ->route('tecnicos.index')
            ->with('success', 'Técnico creado correctamente.');
    }

    public function edit($id)
    {
        if ($bloqueo = $this->bloquearSiNoEsAdmin()) {
            return $bloqueo;
        }

        $tecnico = Tecnico::findOrFail($id);

        $usuarios = Usuario::where('rol', 'tecnico')
            ->where(function ($query) use ($tecnico) {
                $query->whereDoesntHave('tecnico')
                    ->orWhere('id', $tecnico->usuario_id);
            })
            ->orderBy('nombre')
            ->get();

        $especialidades = Especialidad::orderBy('nombre_especialidad')->get();

        return view('tecnicos.edit', compact('tecnico', 'usuarios', 'especialidades'));
    }

    public function update(Request $request, $id)
    {
        if ($bloqueo = $this->bloquearSiNoEsAdmin()) {
            return $bloqueo;
        }

        $tecnico = Tecnico::findOrFail($id);

        $request->validate([
            'usuario_id' => [
                'required',
                'integer',
                Rule::exists('usuarios', 'id')->where(function ($query) {
                    return $query->where('rol', 'tecnico');
                }),
                Rule::unique('tecnicos', 'usuario_id')->ignore($tecnico->id)
            ],
            'nombre_completo' => 'required|string|max:100',
            'especialidad_id' => 'required|integer|exists:especialidades,id',
            'disponible' => 'required|boolean'
        ]);

        $tecnico->update([
            'usuario_id' => $request->usuario_id,
            'nombre_completo' => $request->nombre_completo,
            'especialidad_id' => $request->especialidad_id,
            'disponible' => $request->disponible
        ]);

        return redirect()
            ->route('tecnicos.index')
            ->with('success', 'Técnico actualizado correctamente.');
    }

    public function destroy($id)
    {
        if ($bloqueo = $this->bloquearSiNoEsAdmin()) {
            return $bloqueo;
        }

        $tecnico = Tecnico::findOrFail($id);

        $incidenciasAsignadas = Incidencia::where('tecnico_id', $tecnico->id)->count();

        if ($incidenciasAsignadas > 0) {
            return redirect()
                ->route('tecnicos.index')
                ->with('error', 'No se puede eliminar este técnico porque tiene incidencias asociadas. Para conservar la trazabilidad, primero habría que reasignar o eliminar esas incidencias.');
        }

        try {
            $tecnico->delete();

            return redirect()
                ->route('tecnicos.index')
                ->with('success', 'Técnico eliminado correctamente.');
        } catch (QueryException $e) {
            return redirect()
                ->route('tecnicos.index')
                ->with('error', 'No se puede eliminar este técnico porque está relacionado con otros datos del sistema.');
        }
    }
}