<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presentes', function (Blueprint $table) {
            $table->boolean('flg_disponivel')->default(1);
            $table->string('name_selected')->nullable();
            $table->dateTime('selected_at')->nullable();
            $table->dropColumn('valor_parcela');
            $table->dropColumn('qts_parcelas');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->decimal('pontos')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('presentes', function (Blueprint $table) {
            $table->dropColumn('flg_disponivel');
            $table->dropColumn('name_selected');
            $table->dropColumn('pontos');
            $table->dropColumn('selected_at');
        });
    }
};
