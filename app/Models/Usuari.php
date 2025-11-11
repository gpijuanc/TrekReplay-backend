<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuari extends Authenticatable // Canvia 'Model'
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'usuaris';

    /**
     * Els atributs que es poden assignar massivament.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'correu',
        'contrasenya',
        'role_id',
        'OTA', 
    ];

    /**
     * Els atributs que s'han d'amagar.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'contrasenya',
    ];

    /**
     * Un Usuari pertany a un Rol.
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'role_id');
    }

    /**
     * Un Usuari (Venedor) pot tenir molts Viatges.
     */
    public function viatges()
    {
        return $this->hasMany(Viatge::class);
    }

    /**
     * Un Usuari (Comprador) pot tenir molts items al CarretVirtual.
     */
    public function carretVirtualItems()
    {
        return $this->hasMany(CarretVirtual::class);
    }
}