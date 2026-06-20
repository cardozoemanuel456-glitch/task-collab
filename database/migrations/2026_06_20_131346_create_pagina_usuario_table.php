<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagina_usuario', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('pagina_id')->constrained('paginas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('role')->default('lector'); // admin, editor, lector
            
            $table->unique(['pagina_id', 'user_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagina_usuario');
    }
};