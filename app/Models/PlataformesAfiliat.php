<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlataformesAfiliat extends Model
{
    use HasFactory;

    protected $table = 'plataformes_afiliats';

    protected $fillable = [
        'empresa',
        'url_base',
        'valor_afiliat',
        'id_afiliat',
    ];
}