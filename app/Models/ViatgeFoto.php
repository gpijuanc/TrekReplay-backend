<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViatgeFoto extends Model
{
    use HasFactory;

    protected $table = 'viatge_fotos';

    protected $fillable = [
        'viatge_id',
        'imatge_url',
        'alt_text',
    ];

    public function viatge()
    {
        return $this->belongsTo(Viatge::class);
    }
}