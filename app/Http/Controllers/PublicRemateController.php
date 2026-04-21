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
            ->orderByDesc('fecha_expediente')
            ->get()
            ->map(function (Remate $remate): array {
                return [
                    'id' => $remate->id,
                    'foto' => $remate->foto_path,
                    'fecha_expediente' => optional($remate->fecha_expediente)->format('Y-m-d'),
                    'ubicacion_inmueble' => $remate->ubicacion_inmueble,
                    'tasaciones' => $remate->tasaciones->map(fn ($tasacion): array => [
                        'precio_base' => number_format((float) $tasacion->precio_base, 2, '.', ''),
                        'fecha' => optional($tasacion->fecha)->format('Y-m-d'),
                    ])->values()->all(),
                ];
            })->values();

        return response()->json($remates);
    }
}
