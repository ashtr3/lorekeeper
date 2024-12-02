<?php

use App\Models\Feature\FeatureLocus;
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
        Schema::create('feature_alleles', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(FeatureLocus::class, 'feature_locus_id');
            $table->string('allele', 5);
            $table->integer('sort')->default(0);
            $table->text('description')->nullable();
            $table->text('parsed_description')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_alleles');
    }
};
