<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('lecciones', function (Blueprint $table) {
        $table->id();
        $table->foreignId('curso_id')->constrained()->onDelete('cascade');
        $table->string('titulo');
        $table->text('contenido')->nullable(); // texto o descripción
        $table->string('video_url')->nullable(); // si luego agregas videos
        $table->integer('orden')->default(1); // posición dentro del curso
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leccions');
    }
};
