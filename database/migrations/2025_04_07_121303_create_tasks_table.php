<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Ejecuta las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['pendiente', 'en progreso', 'completada'])->default('pendiente');
            $table->date('due_date')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('low');
            $table->enum('category', [
                'trabajo',
                'estudio',
                'casa',
                'personal',
                'finanzas',
                'salud',
                'viaje',
                'social',
                'tecnología'
            ])->default('personal');
            $table->timestamps();
        });
    }


    /**
     * Revertir las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('priority');
        });
    }
}
