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

    protected $fillable = [
        'nom',
        'correu',
        'contrasenya',
        'role_id',
        'OTA', 
    ];

    protected $hidden = [
        'contrasenya',
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'role_id');
    }

    public function viatges()
    {
        return $this->hasMany(Viatge::class);
    }

    public function carretVirtualItems()
    {
        return $this->hasMany(CarretVirtual::class);
    }
}