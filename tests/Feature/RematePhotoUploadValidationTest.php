<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class RematePhotoUploadValidationTest extends TestCase
{
    public function test_admin_cannot_upload_a_photo_larger_than_150_kb(): void
    {
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=');
        $oversizedPhoto = str_pad($png, 151 * 1024, "\0");

        $response = $this
            ->withSession(['remates_admin_auth' => true])
            ->from(route('admin.remates.create'))
            ->post(route('admin.remates.store'), [
                'foto' => UploadedFile::fake()->createWithContent('remate.png', $oversizedPhoto),
                'numero_expediente' => 'EXP. 001-2026',
                'ubicacion_inmueble' => 'Lima',
                'tasacion' => 100000,
                'tasacion_moneda' => 'PEN',
                'tasaciones' => [[
                    'precio_base' => 90000,
                    'moneda' => 'PEN',
                    'fecha' => '2026-08-01',
                    'hora' => '16:00',
                ]],
            ]);

        $response
            ->assertRedirect(route('admin.remates.create'))
            ->assertSessionHasErrors([
                'foto' => 'La imagen no debe superar los 150 KB.',
            ]);
    }
}
