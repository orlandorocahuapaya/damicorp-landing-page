<?php

namespace App\Http\Controllers;

use App\Models\Remate;
use Illuminate\Http\JsonResponse;

class PublicRemateController extends Controller
{
    public function index(): JsonResponse
    {
        $remates = Remate::query()
            ->with('tasaciones')
            ->orderByDesc('id')
            ->get()
            ->map(function (Remate $remate): array {
                return [
                    'id' => $remate->id,
                    'foto' => $remate->foto_path,
                    'numero_expediente' => $remate->numero_expediente,
                    'ubicacion_inmueble' => $remate->ubicacion_inmueble,
                    'tasacion' => number_format((float) $remate->tasacion, 2, '.', ''),
                    'tasacion_moneda' => $remate->tasacion_moneda ?: 'PEN',
                    'tasaciones' => $remate->tasaciones->map(fn ($tasacion): array => [
                        'precio_base' => number_format((float) $tasacion->precio_base, 2, '.', ''),
                        'moneda' => $tasacion->moneda ?: 'PEN',
                        'fecha' => optional($tasacion->fecha)->format('Y-m-d'),
                        'hora' => substr((string) $tasacion->hora, 0, 5),
                    ])->values()->all(),
                ];
            })->values();

        return response()->json($remates);
    }
}
