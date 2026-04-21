<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminRemateController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PublicRemateController;
use Illuminate\Support\Facades\Route;

$adminLoginPath = config('remates.login_path');
$adminPanelPrefix = config('remates.panel_prefix');

Route::get('/', function () {
    return redirect('/index.html');
});

Route::get('/index.html/{extra}', function () {
    return redirect('/index.html', 301);
})->where('extra', '.*');

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
