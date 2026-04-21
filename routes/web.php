<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminRemateController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PublicRemateController;
use Illuminate\Support\Facades\Route;

$adminLoginPath = config('remates.login_path');
$adminPanelPrefix = config('remates.panel_prefix');

Route::get('/', function () {
    return response()->file(public_path('index.html'));
});

Route::get('/index.html', function () {
    return redirect('/', 301);
});

Route::get('/index.html/{extra}', function () {
    return redirect('/', 301);
})->where('extra', '.*');

Route::get('/nosotros', function () {
    return response()->file(public_path('nosotros.html'));
});

Route::get('/remates', function () {
    return response()->file(public_path('remates.html'));
});

Route::get('/preguntas', function () {
    return response()->file(public_path('preguntas.html'));
});

Route::get('/contacto', function () {
    return response()->file(public_path('contacto.html'));
});

Route::redirect('/nosotros.html', '/nosotros', 301);
Route::redirect('/remates.html', '/remates', 301);
Route::redirect('/preguntas.html', '/preguntas', 301);
Route::redirect('/contacto.html', '/contacto', 301);

Route::post('/contacto/enviar', [ContactController::class, 'send']);
Route::get('/api/remates-publicos', [PublicRemateController::class, 'index'])->name('public.remates.json');

Route::get("/{$adminLoginPath}", [AdminAuthController::class, 'showLogin'])->name('admin.remates.login');
Route::post("/{$adminLoginPath}", [AdminAuthController::class, 'login'])->name('admin.remates.login.submit');
Route::post("/{$adminLoginPath}/salir", [AdminAuthController::class, 'logout'])->name('admin.remates.logout');

Route::middleware('remates.admin')->prefix($adminPanelPrefix)->group(function (): void {
    Route::get('/remates', [AdminRemateController::class, 'index'])->name('admin.remates.index');
    Route::get('/remates/crear', [AdminRemateController::class, 'create'])->name('admin.remates.create');
    Route::post('/remates', [AdminRemateController::class, 'store'])->name('admin.remates.store');
    Route::get('/remates/{remate}/editar', [AdminRemateController::class, 'edit'])->name('admin.remates.edit');
    Route::put('/remates/{remate}', [AdminRemateController::class, 'update'])->name('admin.remates.update');
    Route::delete('/remates/{remate}', [AdminRemateController::class, 'destroy'])->name('admin.remates.destroy');
});
