<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color'];

    // Relación: Una etiqueta PERTENECE A MUCHOS eventos
    public function events()
    {
        return $this->belongsToMany(Event::class);
    }
}
