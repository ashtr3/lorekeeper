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
            $table->dropColumn('rarity_id');
        });

        Schema::table('character_images', function (Blueprint $table) {
            $table->dropIndex('character_images_rarity_id_foreign');
            $table->dropColumn('rarity_id');
        });

        Schema::table('design_updates', function (Blueprint $table) {
            $table->dropColumn('rarity_id');
        });

        Schema::table('features', function (Blueprint $table) {
            $table->dropForeign(['rarity_id']);
            $table->dropIndex('features_rarity_id_foreign');
            $table->dropColumn('rarity_id');
        });

        Schema::dropIfExists('rarities');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('rarities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->unsignedInteger('sort')->default(0);
            $table->string('color', 6)->nullable();
            $table->boolean('has_image')->default(0);
            $table->text('description')->nullable();
            $table->text('parsed_description')->nullable();
            $table->primary('id');
        });

        Schema::table('characters', function (Blueprint $table) {
            $table->unsignedInteger('rarity_id', 10)->nullable;
        });

        Schema::table('character_images', function (Blueprint $table) {
            $table->unsignedInteger('rarity_id', 10)->nullable();
            $table->index('rarity_id');
        });

        Schema::table('design_updates', function (Blueprint $table) {
            $table->unsignedInteger('rarity_id', 10)->nullable();
        });

        Schema::table('features', function (Blueprint $table) {
            $table->unsignedInteger('rarity_id', 10);
            $table->index('rarity_id');
        });
    }
};
