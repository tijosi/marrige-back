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
        Schema::create('pessoas_confirmadas', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->boolean('confirmacao_1');
            $table->boolean('confirmacao_2');
            $table->boolean('confirmacao_3');
            $table->dateTime('updated_at');
            $table->dateTime('btn_close_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pessoas_confirmadas');
    }
};
