<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    /**
     * El nom de la taula associada amb el model.
     *
     * @var string
     */
    protected $table = 'rols'; // Important per connectar amb la taula 'rols'

    /**
     * Un Rol pertany a molts Usuaris.
     */
    public function usuaris()
    {
        return $this->hasMany(Usuari::class);
    }
}