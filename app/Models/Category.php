<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    // Relación: Una categoría TIENE MUCHOS eventos
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
