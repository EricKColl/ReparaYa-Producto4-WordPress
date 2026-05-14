<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    public function serviciosPorZona(): JsonResponse
    {
        $totalGlobal = Incidencia::whereNotNull('comunidad_id')
            ->where('estado', 'Finalizada')
            ->count();

        if ($totalGlobal === 0) {
            return response()->json([
                'total_global' => 0,
                'zonas' => [],
            ]);
        }

        $serviciosPorZona = Incidencia::selectRaw('comunidades.zona AS zona, COUNT(*) AS total_servicios')
            ->join('comunidades', 'incidencias.comunidad_id', '=', 'comunidades.id')
            ->whereNotNull('incidencias.comunidad_id')
            ->where('incidencias.estado', 'Finalizada')
            ->groupBy('comunidades.zona')
            ->orderByDesc('total_servicios')
            ->get();

        $zonas = $serviciosPorZona->map(function ($fila) use ($totalGlobal) {
            return [
                'zona' => $fila->zona,
                'total_servicios' => (int) $fila->total_servicios,
                'porcentaje' => round(($fila->total_servicios / $totalGlobal) * 100, 2),
            ];
        });

        return response()->json([
            'total_global' => $totalGlobal,
            'zonas' => $zonas,
        ]);
    }
}