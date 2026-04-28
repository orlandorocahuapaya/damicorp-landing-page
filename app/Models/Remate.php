<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Remate extends Model
{
    protected $fillable = [
        'foto_path',
        'numero_expediente',
        'ubicacion_inmueble',
        'tasacion',
        'tasacion_moneda',
    ];

    public function tasaciones(): HasMany
    {
        return $this->hasMany(Tasacion::class)->orderBy('id');
    }
}
