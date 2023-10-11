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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('chave');
            $table->text('telefone')->nullable()->default(null);
        });

        Schema::table('pessoas_confirmadas', function (Blueprint $table) {
            $table->integer('user_id')->nullable()->change();
            $table->boolean('confirmacao_1')->nullable()->change();
            $table->boolean('confirmacao_2')->nullable()->change();
            $table->boolean('confirmacao_3')->nullable()->change();
            $table->dateTime('updated_at')->nullable()->change();
            $table->dateTime('btn_close_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('telefone');
            $table->text('chave');
        });

        Schema::table('pessoas_confirmadas', function (Blueprint $table) {
            $table->integer('user_id')->change();
            $table->boolean('confirmacao_1')->change();
            $table->boolean('confirmacao_2')->change();
            $table->boolean('confirmacao_3')->change();
            $table->dateTime('updated_at')->change();
            $table->dateTime('btn_close_at')->change();
        });
    }
};
