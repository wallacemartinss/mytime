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
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete(); // Relacionamento com o usuário
            $table->string('company_name'); // Nome da empresa
            $table->decimal('gross_salary', 10, 2); // Salário bruto
            $table->integer('monthly_hours'); // Horas contratadas no mês
            $table->decimal('extra_hour_rate', 8, 2); // Valor da hora extra calculada
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
