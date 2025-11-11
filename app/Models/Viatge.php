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
        'publicat',
    ];

    /**
     * Un Viatge pertany a un Usuari (Venedor).
     */
    public function venedor()
    {
        return $this->belongsTo(Usuari::class, 'usuari_id');
    }

    /**
     * Un Viatge pot tenir moltes ViatgeFotos.
     */
    public function fotos()
    {
        return $this->hasMany(ViatgeFoto::class);
    }

    /**
     * Un Viatge pot estar en molts CarretsVirtuals.
     */
    public function carretItems()
    {
        return $this->hasMany(CarretVirtual::class);
    }
}