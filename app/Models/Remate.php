<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Remate extends Model
{
    protected $fillable = [
        'foto_path',
        'fecha_expediente',
        'ubicacion_inmueble',
    ];

    protected $casts = [
        'fecha_expediente' => 'date',
    ];

    public function tasaciones(): HasMany
    {
        return $this->hasMany(Tasacion::class)->orderByDesc('fecha');
    }
}
