<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagina extends Model
{
    use HasFactory;

    // Indicamos explícitamente el nombre de la tabla
    protected $table = 'paginas';

    protected $fillable = [
        'titulo',
        'icono',
        'portada_url',
        'user_id',
        'padre_id'
    ];

    // Dueño o creador de la página
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con su página "Madre" (si es una subpágina)
    public function padre()
    {
        return $this->belongsTo(Pagina::class, 'padre_id');
    }

    // Relación para traer todas sus subpáginas
    public function subpaginas()
    {
        return $this->hasMany(Pagina::class, 'padre_id');
    }

    // Las tareas asociadas específicamente a esta página/lista
    public function tareas()
    {
        return $this->hasMany(Task::class, 'pagina_id');
    }

    // Colaboradores con los que se compartió la página
    public function miembros()
    {
        return $this->belongsToMany(User::class, 'pagina_usuario', 'pagina_id', 'user_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }
}