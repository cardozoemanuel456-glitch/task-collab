<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('priority')->default('media');
            $table->string('status')->default('todo'); // valores: todo, doing, done

            // Reciclamos tus campos originales de fechas
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // NUEVO VÍNCULO: Cada tarea pertenece a una Lista/Página específica de tu menú lateral
            $table->foreignId('pagina_id')->constrained('paginas')->onDelete('cascade');

            // Relaciones con usuarios heredadas de tu código
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Creador
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');

            $table->bigInteger('team_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};
