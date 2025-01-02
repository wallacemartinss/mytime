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
        Schema::create('work_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete(); // Relacionamento com o usuário
            $table->foreignId('user_setting_id')->nullable()->constrained('user_settings')->cascadeOnDelete(); // Relaciona com user_settings
            $table->date('date'); // Data do registro
            $table->time('start_time'); // Hora de entrada
            $table->time('lunch_start')->nullable(); // Hora de saída para almoço
            $table->time('lunch_end')->nullable(); // Hora de retorno do almoço
            $table->time('end_time'); // Hora de saída
            $table->decimal('hourly_rate', 8, 2)->nullable(); // Horas extras
            $table->decimal('extra_hours', 8, 2)->nullable(); // Horas extras
            $table->decimal('extra_value', 8, 2)->nullable(); // Valor das horas extras
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_hours');
    }
};
