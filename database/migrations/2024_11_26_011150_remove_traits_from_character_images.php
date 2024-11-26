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
        Schema::table('character_images', function (Blueprint $table) {
            $table->dropColumn(['species_id', 'subtype_id', 'rarity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('character_images', function (Blueprint $table) {
            $table->unsignedInteger('species_id')->nullable()->after('is_valid');
            $table->unsignedInteger('subtype_id')->nullable()->after('species_id');
            $table->unsignedInteger('rarity_id')->nullable()->after('subtype_id');
        });
    }
};
