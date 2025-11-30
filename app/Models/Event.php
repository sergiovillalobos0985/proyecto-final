<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes; // Importante para la rúbrica

class Event extends Model
{
    use HasFactory, SoftDeletes; // Habilitamos SoftDeletes

    protected $fillable = ['title', 'description', 'start_date', 'location', 'category_id',  'user_id'];

    // Relación: Un evento PERTENECE A una categoría
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relación: Un evento PERTENECE A un usuario (creador)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: Un evento TIENE MUCHAS etiquetas
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
