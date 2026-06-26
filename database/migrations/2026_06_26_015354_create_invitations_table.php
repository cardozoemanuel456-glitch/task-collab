<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();

            // Relación con tu tabla 'paginas'
            $table->foreignId('pagina_id')->constrained('paginas')->onDelete('cascade');

            // Usuario que envía la invitación
            $table->foreignId('inviter_id')->constrained('users')->onDelete('cascade');

            // Email del invitado (puede ser null si se usa solo código)
            $table->string('email')->nullable();

            // Código corto para "Copiar y Pegar" (ej: A7X9)
            $table->string('code', 10)->index();

            // Token seguro y largo para el enlace (hash)
            $table->string('token_hash', 64)->index();

            // Estado de la invitación
            $table->enum('status', ['pending', 'accepted', 'expired'])->default('pending');

            // Fechas
            $table->timestamp('expires_at');
            $table->timestamp('accepted_at')->nullable();

            // Usuario que aceptó (para saber quién usó el código)
            $table->foreignId('accepted_by_user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();

            // Índices para búsqueda rápida
            $table->unique(['pagina_id', 'email', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
