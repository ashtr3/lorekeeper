<?php

use App\Models\Feature\Feature;
use App\Models\Feature\FeatureAllele;
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
        Schema::create('feature_genetics', function (Blueprint $table) {
            $table->foreignIdFor(Feature::class, 'feature_id');
            $table->foreignIdFor(FeatureAllele::class, 'feature_allele_id');
            $table->boolean('allow_homozygous')->default(false);
            $table->boolean('allow_heterozygous')->default(false);
            $table->boolean('allow_absent')->default(false);
            $table->timestamps();
        });

        Schema::table('feature_genetics', function (Blueprint $table) {
            $table->primary(['feature_id', 'feature_allele_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feature_genetics', function (Blueprint $table) {
            $table->dropPrimary(['feature_id', 'feature_allele_id']);
        });
        Schema::dropIfExists('feature_genetics');
    }
};
