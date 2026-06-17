<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('priority')->default('Baja'); // Baja, Media, Alta
            $table->string('status')->default('por_hacer'); // por_hacer, en_progreso, completado
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación limpia con usuarios
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
