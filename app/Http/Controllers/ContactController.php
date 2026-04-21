<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function send(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'Nombre' => ['required', 'string', 'max:120'],
            'Correo' => ['required', 'email', 'max:120'],
            'Telefono' => ['required', 'string', 'max:40'],
            'Asunto' => ['required', 'string', 'max:140'],
            'Mensaje' => ['required', 'string', 'max:5000'],
        ]);

        if ($validator->fails()) {
            return $this->redirectWithStatus('invalid');
        }

        $validated = $validator->validated();

        $toEmail = (string) env('CONTACT_TO_EMAIL', 'procesos@damicorperu.com');
        $subject = 'Formulario Web DAMICOR - '.$this->cleanText($validated['Asunto']);

        $body = "Nombre: ".$this->cleanText($validated['Nombre'])."\n"
            ."Correo: ".trim($validated['Correo'])."\n"
            ."Telefono: ".$this->cleanText($validated['Telefono'])."\n"
            ."Asunto: ".$this->cleanText($validated['Asunto'])."\n\n"
            ."Mensaje:\n".trim($validated['Mensaje'])."\n";

        try {
            Mail::raw($body, function ($message) use ($toEmail, $subject, $validated): void {
                $message->to($toEmail)
                    ->replyTo(trim($validated['Correo']), $this->cleanText($validated['Nombre']))
                    ->subject($subject);
            });

            return $this->redirectWithStatus('ok');
        } catch (\Throwable $e) {
            report($e);

            return $this->redirectWithStatus('error');
        }
    }

    private function cleanText(string $value): string
    {
        return trim(str_replace(["\r", "\n"], ' ', $value));
    }

    private function redirectWithStatus(string $status): RedirectResponse
    {
        $target = (string) env('CONTACT_REDIRECT_URL', '/contacto.html');
        $separator = str_contains($target, '?') ? '&' : '?';
        $finalUrl = $target.$separator.'status='.$status;

        if (filter_var($target, FILTER_VALIDATE_URL)) {
            return redirect()->away($finalUrl);
        }

        return redirect($finalUrl);
    }
}
