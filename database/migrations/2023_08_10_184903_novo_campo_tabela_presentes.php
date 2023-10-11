<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presentes', function (Blueprint $table) {
            $table->decimal('valor_max', 10, 2);
            $table->string('name_img');
            $table->string('level');
            $table->renameColumn('valor', 'valor_min');
        });
    }

    public function down(): void
    {
        Schema::table('presentes', function (Blueprint $table) {
            $table->dropColumn('valor_max');
            $table->dropColumn('name_img');
            $table->dropColumn('level');
            $table->renameColumn('valor_min', 'valor');
        });
    }
};
