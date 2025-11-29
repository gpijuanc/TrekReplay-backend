<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarretVirtual extends Model
{
    use HasFactory;

    protected $table = 'carret_virtual';

    protected $fillable = [
        'usuari_id',
        'viatge_id',
        'temps_afegit',
    ];

    public function usuari()
    {
        return $this->belongsTo(Usuari::class);
    }

    public function viatge()
    {
        return $this->belongsTo(Viatge::class);
    }
}