<?php

namespace App\Http\Controllers;

use App\Models\Remate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminRemateController extends Controller
{
    public function index(): View
    {
        $remates = Remate::query()
            ->with('tasaciones')
            ->orderByDesc('id')
            ->paginate(12);

        return view('admin.remates.index', compact('remates'));
    }

    public function create(): View
    {
        return view('admin.remates.form', [
            'remate' => null,
            'tasaciones' => [['precio_base' => '', 'moneda' => 'PEN', 'fecha' => '', 'hora' => '16:00']],
            'action' => route('admin.remates.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRemate($request, true);

        DB::transaction(function () use ($data, $request): void {
            $remate = Remate::create([
                'foto_path' => $this->storePhoto($request),
                'numero_expediente' => $data['numero_expediente'],
                'ubicacion_inmueble' => $data['ubicacion_inmueble'],
            ]);

            foreach ($data['tasaciones'] as $tasacion) {
                $remate->tasaciones()->create($tasacion);
            }
        });

        return redirect()->route('admin.remates.index')->with('status', 'Remate creado correctamente.');
    }

    public function edit(Remate $remate): View
    {
        $remate->load('tasaciones');

        return view('admin.remates.form', [
            'remate' => $remate,
            'tasaciones' => $remate->tasaciones->map(fn ($t) => [
                'precio_base' => number_format((float) $t->precio_base, 2, '.', ''),
                'moneda' => $t->moneda ?: 'PEN',
                'fecha' => optional($t->fecha)->format('Y-m-d'),
                'hora' => $t->hora ? substr((string) $t->hora, 0, 5) : '16:00',
            ])->values()->all(),
            'action' => route('admin.remates.update', $remate),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, Remate $remate): RedirectResponse
    {
        $data = $this->validateRemate($request, false);

        DB::transaction(function () use ($data, $request, $remate): void {
            $payload = [
                'numero_expediente' => $data['numero_expediente'],
                'ubicacion_inmueble' => $data['ubicacion_inmueble'],
            ];

            if ($request->hasFile('foto')) {
                $payload['foto_path'] = $this->storePhoto($request, $remate->foto_path);
            }

            $remate->update($payload);
            $remate->tasaciones()->delete();

            foreach ($data['tasaciones'] as $tasacion) {
                $remate->tasaciones()->create($tasacion);
            }
        });

        return redirect()->route('admin.remates.index')->with('status', 'Remate actualizado correctamente.');
    }

    public function destroy(Remate $remate): RedirectResponse
    {
        $photoPath = public_path(ltrim($remate->foto_path, '/'));
        $remate->delete();

        if (is_file($photoPath)) {
            @unlink($photoPath);
        }

        return redirect()->route('admin.remates.index')->with('status', 'Remate eliminado correctamente.');
    }

    private function validateRemate(Request $request, bool $requirePhoto): array
    {
        $photoRules = ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'];
        if ($requirePhoto) {
            array_unshift($photoRules, 'required');
        } else {
            array_unshift($photoRules, 'nullable');
        }

        return $request->validate([
            'foto' => $photoRules,
            'numero_expediente' => ['required', 'string', 'max:30'],
            'ubicacion_inmueble' => ['required', 'string', 'max:255'],
            'tasaciones' => ['required', 'array', 'min:1'],
            'tasaciones.*.precio_base' => ['required', 'numeric', 'min:0'],
            'tasaciones.*.moneda' => ['required', 'in:PEN,USD'],
            'tasaciones.*.fecha' => ['required', 'date'],
            'tasaciones.*.hora' => ['required', 'date_format:H:i'],
        ]);
    }

    private function storePhoto(Request $request, ?string $oldPath = null): string
    {
        $file = $request->file('foto');
        $dir = public_path('uploads/remates');
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $filename = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
        $file->move($dir, $filename);

        if ($oldPath !== null) {
            $oldAbsolutePath = public_path(ltrim($oldPath, '/'));
            if (is_file($oldAbsolutePath)) {
                @unlink($oldAbsolutePath);
            }
        }

        return '/uploads/remates/'.$filename;
    }
}
