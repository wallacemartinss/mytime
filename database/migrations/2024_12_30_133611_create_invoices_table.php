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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete(); // Relacionamento com o usuário
            $table->foreignId('project_id')->nullable()->constrained('projects')->cascadeOnDelete(); // Relaciona com user_settings
            $table->foreignId('client_id')->nullable()->constrained('clients')->cascadeOnDelete(); // Relaciona com user_settings
            $table->date('start_date')->nullable(); 
            $table->date('end_date')->nullable(); 
            $table->decimal('total_hours', 8, 2)->nullable();
            $table->decimal('total_value', 15, 2)->nullable(); // Valor total da nota fiscal
            $table->string('status')->nullable(); // Status da nota fiscal
            $table->string('payment_method')->nullable(); // Método de pagamento
            $table->string('payment_status')->nullable(); // Status do pagamento
            $table->string('payment_date')->nullable(); // Data do pagamento
            $table->string('atachment')->nullable(); // Status do pagamento
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
