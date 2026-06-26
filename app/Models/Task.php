<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tareas';

    // Agregamos TODOS los campos para permitir la asignación masiva
    protected $fillable = [
        'pagina_id',
        'title',
        'description',
        'status',
        'priority',
        'user_id',
        'assigned_to',
        'start_date',
        'end_date',
    ];

    public function pagina()
    {
        return $this->belongsTo(Pagina::class, 'pagina_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function asignado()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
