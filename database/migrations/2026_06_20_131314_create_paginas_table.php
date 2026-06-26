<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paginas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('titulo')->default('Sin título');
            $table->string('icono')->nullable(); // Para guardar el emoji de la página
            $table->string('portada_url')->nullable(); // Para el banner de la cabecera

            // Relación con el creador/dueño
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // LA MAGIA: Auto-relación para subpáginas infinitas
            $table->unsignedBigInteger('padre_id')->nullable();
            $table->foreign('padre_id')->references('id')->on('paginas')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paginas');
    }
};
