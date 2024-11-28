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
        Schema::table('character_features', function (Blueprint $table) {
            $table->unsignedInteger('character_id')->nullable()->after('character_image_id');
            $table->foreign('character_id')->references('id')->on('characters')->cascadeOnDelete();
            $table->dropColumn(['character_image_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('character_features', function (Blueprint $table) {
            $table->unsignedInteger('character_image_id')->nullable()->after('character_id');
            $table->dropForeign(['character_id']);
            $table->dropColumn(['character_id']);
        });
    }
};
