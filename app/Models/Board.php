<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color_theme', 'workspace_id'];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // Relación Muchos a Muchos con la tabla intermedia 'board_user'
    public function members()
    {
        return $this->belongsToMany(User::class, 'board_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }
}
