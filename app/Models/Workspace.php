<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Workspace extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'user_id', 'invite_code'];

    // Evento automático: Genera el código aleatorio único antes de guardar en la BD
    protected static function booted()
    {
        static::creating(function ($workspace) {
            $workspace->invite_code = 'TC-' . strtoupper(Str::random(4));
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function boards()
    {
        return $this->hasMany(Board::class);
    }
}