<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'dark_mode',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Páginas creadas por el usuario
    public function paginas()
    {
        return $this->hasMany(Pagina::class, 'user_id');
    }

    // Tareas de las cuales es creador
    public function tareasCreadas()
    {
        return $this->hasMany(Task::class, 'user_id');
    }

    // Tareas que tiene asignadas
    public function tareasAsignadas()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    // Espacios colaborativos donde fue invitado
    public function paginasCompartidas()
    {
        return $this->belongsToMany(Pagina::class, 'pagina_usuario', 'user_id', 'pagina_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }
}