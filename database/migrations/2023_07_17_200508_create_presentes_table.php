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
        Schema::create('presentes', function (Blueprint $table) {
            $table->id();
            $table->string('nome', []);
            $table->decimal('valor', 10, 2);
            $table->decimal('valor_parcela', 10, 2)->nullable();
            $table->integer('qts_parcelas')->nullable();
            $table->string('img_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presentes');
    }
};
