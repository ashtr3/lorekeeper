<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('characters', function (Blueprint $table) {
            $table->unsignedInteger('species_id')->nullable()->after('character_category_id');
            $table->unsignedInteger('subtype_id')->nullable()->after('species_id');
            $table->foreign('species_id')->references('id')->on('specieses')->onDelete('set null');
            $table->foreign('subtype_id')->references('id')->on('subtypes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('characters', function (Blueprint $table) {
            if (Schema::hasColumn('characters', 'species_id')) {
                $table->dropForeign(['species_id']);
            }
            if (Schema::hasColumn('characters', 'subtype_id')) {
                $table->dropForeign(['subtype_id']);
            }
            $table->dropColumn(['species_id', 'subtype_id']);
        });
    }
};
