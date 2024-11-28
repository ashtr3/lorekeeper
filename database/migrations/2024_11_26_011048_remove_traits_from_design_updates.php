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
        Schema::table('design_updates', function (Blueprint $table) {
            $table->dropColumn(['has_features', 'species_id', 'subtype_id', 'rarity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('design_updates', function (Blueprint $table) {
            $table->tinyInteger('has_features')->default(0);
            $table->unsignedInteger('species_id')->nullable();
            $table->unsignedInteger('subtype_id')->nullable();
            $table->unsignedInteger('rarity_id')->nullable();
        });
    }
};
