<?php

namespace App\Http\Controllers;

use App\Models\Gestora;
use App\Models\Comunidad;
use App\Models\Incidencia;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class B2BController extends Controller
{
    public function showLogin()
    {
        return view('b2b.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $gestora = Gestora::where('email', $request->email)->first();

        if (!$gestora || !Hash::check($request->password, $gestora->password)) {
            return back()
                ->withErrors(['email' => 'Credenciales incorrectas.'])
                ->withInput();
        }

        session()->forget([
            'usuario_id',
            'usuario_nombre',
            'usuario_rol',
        ]);

        session([
            'gestora_id'     => $gestora->id,
            'gestora_nombre' => $gestora->nombre,
        ]);

        return redirect()->route('b2b.panel');
    }

    public function logout()
    {
        session()->forget([
            'gestora_id',
            'gestora_nombre',
        ]);

        return redirect()->route('login');
    }

    public function panel(Request $request)
    {
        if (!session('gestora_id')) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión como gestora para acceder al panel.');
        }

        $gestora = Gestora::with('comunidades')->findOrFail(session('gestora_id'));

        $mes  = (int) $request->get('mes', now()->month);
        $anyo = (int) $request->get('anyo', now()->year);

        $servicios = Incidencia::with(['comunidad', 'especialidad'])
            ->where('gestora_id', $gestora->id)
            ->whereMonth('fecha_servicio', $mes)
            ->whereYear('fecha_servicio', $anyo)
            ->orderBy('fecha_servicio', 'desc')
            ->get();

        $serviciosConComision = $servicios->map(function ($incidencia) use ($gestora) {
            $precioBase = (float) ($incidencia->precio_base ?? 0);
            $porcentaje = (float) ($gestora->comision ?? 0);

            $incidencia->comision_calculada = $incidencia->estado === 'Finalizada'
                ? round($precioBase * ($porcentaje / 100), 2)
                : 0;

            return $incidencia;
        });

        $totalServicios = $serviciosConComision->count();
        $pendientes = $serviciosConComision->where('estado', 'Pendiente')->count();
        $asignados = $serviciosConComision->where('estado', 'Asignada')->count();
        $finalizados = $serviciosConComision->where('estado', 'Finalizada')->count();
        $cancelados = $serviciosConComision->where('estado', 'Cancelada')->count();

        $totalImporte = $serviciosConComision
            ->where('estado', 'Finalizada')
            ->sum('precio_base');

        $totalComisiones = $serviciosConComision->sum('comision_calculada');

        $actividadAbierta = $pendientes + $asignados;

        $proximoServicio = Incidencia::with(['comunidad', 'especialidad'])
            ->where('gestora_id', $gestora->id)
            ->whereIn('estado', ['Pendiente', 'Asignada'])
            ->where('fecha_servicio', '>=', now())
            ->orderBy('fecha_servicio')
            ->first();

        $resumen = [
    'total_servicios' => $totalServicios,
    'pendientes' => $pendientes,
    'asignados' => $asignados,
    'finalizados' => $finalizados,
    'cancelados' => $cancelados,
    'comunidades' => $gestora->comunidades->count(),
    'total_importe' => $totalImporte,
    'total_comisiones' => $totalComisiones,
    'porcentaje_finalizados' => $this->porcentaje($finalizados, $totalServicios),
    'porcentaje_pendientes' => $this->porcentaje($actividadAbierta, $totalServicios),
    'proximo_servicio' => $proximoServicio,
];

$calendarEvents = Incidencia::with(['comunidad', 'especialidad'])
    ->where('gestora_id', $gestora->id)
    ->orderBy('fecha_servicio')
    ->get()
    ->map(function ($incidencia) {
        return [
            'fecha' => $incidencia->fecha_servicio,
            'codigo' => $incidencia->localizador ?? 'B2B-' . $incidencia->id,
            'titulo' => ($incidencia->comunidad->nombre ?? 'Comunidad sin asignar')
                . ' · '
                . ($incidencia->especialidad->nombre_especialidad ?? 'Servicio'),
            'estado' => $incidencia->estado,
            'urgencia' => $incidencia->tipo_urgencia,
        ];
    })
    ->toArray();

$calendarData = [
    'calendarTitle' => 'Calendario de avisos gestionados',
    'calendarSubtitle' => null,
    'calendarEvents' => $calendarEvents,
];

$anyos = range(2024, max((int) now()->year, $anyo));

        return view('b2b.panel', compact(
    'gestora',
    'serviciosConComision',
    'totalComisiones',
    'mes',
    'anyo',
    'anyos',
    'resumen',
    'calendarData'
));
    }

    public function createAviso()
    {
        if (!session('gestora_id')) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión como gestora para crear avisos.');
        }

        $gestora = Gestora::findOrFail(session('gestora_id'));

        $comunidades = Comunidad::where('gestora_id', $gestora->id)
            ->orderBy('nombre')
            ->get();

        $especialidades = \App\Models\Especialidad::orderBy('nombre_especialidad')->get();

        return view('b2b.create_aviso', compact(
            'gestora',
            'comunidades',
            'especialidades'
        ));
    }

    public function storeAviso(Request $request)
    {
        if (!session('gestora_id')) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión como gestora para crear avisos.');
        }

        $gestora = Gestora::findOrFail(session('gestora_id'));

        $request->validate([
            'comunidad_id'      => 'required|exists:comunidades,id',
            'especialidad_id'   => 'required|exists:especialidades,id',
            'descripcion'       => 'required|string',
            'telefono_contacto' => 'required|string|max:20',
            'fecha_servicio'    => 'required|date',
            'tipo_urgencia'     => 'required|in:Estandar,Urgente',
            'precio_base'       => 'required|numeric|min:0',
            'estado'            => 'required|in:Pendiente,Finalizada',
        ]);

        $comunidad = Comunidad::where('id', $request->comunidad_id)
            ->where('gestora_id', $gestora->id)
            ->firstOrFail();

        $cliente = Usuario::where('rol', 'particular')->orderBy('id')->first();

        if (!$cliente) {
            return back()
                ->withErrors(['cliente_id' => 'No existe ningún usuario particular en el sistema para registrar el aviso B2B.'])
                ->withInput();
        }

        Incidencia::create([
            'localizador'       => 'B2B-' . random_int(100000, 999999),
            'cliente_id'        => $cliente->id,
            'tecnico_id'        => null,
            'especialidad_id'   => $request->especialidad_id,
            'descripcion'       => $request->descripcion,
            'direccion'         => $comunidad->direccion,
            'telefono_contacto' => $request->telefono_contacto,
            'fecha_servicio'    => $request->fecha_servicio,
            'tipo_urgencia'     => $request->tipo_urgencia,
            'estado'            => $request->estado,
            'gestora_id'        => $gestora->id,
            'comunidad_id'      => $comunidad->id,
            'precio_base'       => $request->precio_base,
            'created_at'        => now(),
        ]);

        return redirect()
            ->route('b2b.panel')
            ->with('success', 'Aviso creado correctamente.');
    }

    public function editAviso($id)
    {
        if (!session('gestora_id')) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión como gestora para editar avisos.');
        }

        $gestora = Gestora::findOrFail(session('gestora_id'));

        $aviso = Incidencia::where('id', $id)
            ->where('gestora_id', $gestora->id)
            ->firstOrFail();

        $comunidades = Comunidad::where('gestora_id', $gestora->id)
            ->orderBy('nombre')
            ->get();

        $especialidades = \App\Models\Especialidad::orderBy('nombre_especialidad')->get();

        return view('b2b.edit_aviso', compact(
            'gestora',
            'aviso',
            'comunidades',
            'especialidades'
        ));
    }

    public function updateAviso(Request $request, $id)
    {
        if (!session('gestora_id')) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión como gestora para actualizar avisos.');
        }

        $gestora = Gestora::findOrFail(session('gestora_id'));

        $aviso = Incidencia::where('id', $id)
            ->where('gestora_id', $gestora->id)
            ->firstOrFail();

        $request->validate([
            'comunidad_id'      => 'required|exists:comunidades,id',
            'especialidad_id'   => 'required|exists:especialidades,id',
            'descripcion'       => 'required|string',
            'telefono_contacto' => 'required|string|max:20',
            'fecha_servicio'    => 'required|date',
            'tipo_urgencia'     => 'required|in:Estandar,Urgente',
            'precio_base'       => 'required|numeric|min:0',
            'estado'            => 'required|in:Pendiente,Finalizada,Cancelada',
        ]);

        $comunidad = Comunidad::where('id', $request->comunidad_id)
            ->where('gestora_id', $gestora->id)
            ->firstOrFail();

        $aviso->update([
            'comunidad_id'      => $comunidad->id,
            'especialidad_id'   => $request->especialidad_id,
            'descripcion'       => $request->descripcion,
            'direccion'         => $comunidad->direccion,
            'telefono_contacto' => $request->telefono_contacto,
            'fecha_servicio'    => $request->fecha_servicio,
            'tipo_urgencia'     => $request->tipo_urgencia,
            'precio_base'       => $request->precio_base,
            'estado'            => $request->estado,
        ]);

        return redirect()
            ->route('b2b.panel')
            ->with('success', 'Aviso actualizado correctamente.');
    }

    public function destroyAviso($id)
    {
        if (!session('gestora_id')) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión como gestora para eliminar avisos.');
        }

        $gestora = Gestora::findOrFail(session('gestora_id'));

        $aviso = Incidencia::where('id', $id)
            ->where('gestora_id', $gestora->id)
            ->firstOrFail();

        $aviso->delete();

        return redirect()
            ->route('b2b.panel')
            ->with('success', 'Aviso eliminado correctamente.');
    }

    public function liquidaciones(Request $request)
    {
        if (session('usuario_rol') !== 'admin') {
            return redirect()->route('home')
                ->with('error', 'Solo el administrador puede consultar liquidaciones.');
        }

        $mes  = (int) $request->get('mes', now()->month);
        $anyo = (int) $request->get('anyo', now()->year);

        $gestoras = Gestora::with(['incidencias' => function ($query) use ($mes, $anyo) {
            $query->where('estado', 'Finalizada')
                ->whereMonth('fecha_servicio', $mes)
                ->whereYear('fecha_servicio', $anyo);
        }])
            ->orderBy('nombre')
            ->get();

        $liquidaciones = $gestoras->map(function ($gestora) {
            $totalServicios = $gestora->incidencias->count();
            $totalImporte = $gestora->incidencias->sum('precio_base');
            $totalComision = round($totalImporte * ((float) $gestora->comision / 100), 2);

            return [
                'gestora' => $gestora,
                'total_servicios' => $totalServicios,
                'total_importe' => $totalImporte,
                'total_comision' => $totalComision,
            ];
        });

        $anyos = range(2024, max((int) now()->year, $anyo));

        return view('liquidaciones.index', compact(
            'liquidaciones',
            'mes',
            'anyo',
            'anyos'
        ));
    }

    private function porcentaje(int $valor, int $total): int
    {
        if ($total <= 0) {
            return 0;
        }

        return (int) round(($valor / $total) * 100);
    }
}