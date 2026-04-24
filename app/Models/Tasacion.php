<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tasacion extends Model
{
    protected $table = 'tasaciones';

    protected $fillable = [
        'remate_id',
        'precio_base',
        'fecha',
        'hora',
    ];

    protected $casts = [
        'fecha' => 'date',
        'precio_base' => 'decimal:2',
    ];

    public function remate(): BelongsTo
    {
        return $this->belongsTo(Remate::class);
    }
}
