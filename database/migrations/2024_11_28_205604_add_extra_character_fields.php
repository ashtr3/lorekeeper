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
        Schema::table('characters', function (Blueprint $table) {
            $table->enum('sex', ['male', 'female', 'intersex', 'other']);
            $table->string('custom_sex')->nullable();
            $table->string('genotype')->nullable();
            $table->integer('mp')->default(0);
            $table->integer('fertility')->default(100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->dropColumn(['sex', 'custom_sex', 'genotype', 'mp', 'fertility']);
        });
    }
};
