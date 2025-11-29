<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Viatge extends Model
{
    use HasFactory;

    protected $table = 'viatge';

    protected $fillable = [
        'usuari_id',
        'titol',
        'imatge_principal',
        'blog',
        'tipus_viatge',
        'preu',
        'pais',
        'publicat'
    ];

    protected $casts = [
        'pais' => 'array',
        'publicat' => 'boolean',
        'preu' => 'decimal:2'
    ];

    public function venedor()
    {
        return $this->belongsTo(Usuari::class, 'usuari_id');
    }

    public function fotos()
    {
        return $this->hasMany(ViatgeFoto::class);
    }

    public function carretItems()
    {
        return $this->hasMany(CarretVirtual::class);
    }
}