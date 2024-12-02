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
        Schema::table('feature_loci', function (Blueprint $table) {
            $table->boolean('default_allele_leads')->default(true)->after('parsed_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feature_loci', function (Blueprint $table) {
            $table->dropColumn('default_allele_leads');
        });
    }
};
