<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class Invitation extends Model
{
    protected $fillable = [
        'pagina_id',
        'inviter_id',
        'email',
        'code',
        'token_hash',
        'status',
        'expires_at',
        'accepted_at',
        'accepted_by_user_id',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    /**
     * Crea una invitación y devuelve el token plano (necesario para el enlace).
     */
    public static function createInvitation($paginaId, $inviterId, $email)
    {
        // Verificar duplicados pendientes
        $existing = static::where('pagina_id', $paginaId)
            ->where('email', $email)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return ['invitation' => $existing, 'token' => null, 'isNew' => false];
        }

        // Generar datos únicos
        $code = strtoupper(Str::random(4)); // Código corto
        $plainToken = Str::random(64);      // Token largo para enlace
        $tokenHash = hash('sha256', $plainToken); // Hash para guardar en BD

        $invitation = static::create([
            'pagina_id' => $paginaId,
            'inviter_id' => $inviterId,
            'email' => $email,
            'code' => $code,
            'token_hash' => $tokenHash,
            'status' => 'pending',
            'expires_at' => now()->addHours(48),
        ]);

        return ['invitation' => $invitation, 'token' => $plainToken, 'isNew' => true];
    }

    /**
     * Busca una invitación válida por su token (para el enlace).
     */
    public static function findByToken($plainToken)
    {
        $hashed = hash('sha256', $plainToken);
        return static::where('token_hash', $hashed)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->first();
    }

    /**
     * Busca una invitación válida por su código corto.
     */
    public static function findByCode($code)
    {
        return static::where('code', strtoupper($code))
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->first();
    }
}