<?php

use App\Models\Character\Character;
use App\Models\Feature\FeatureAllele;
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
        Schema::create('character_genetics', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Character::class, 'character_id');
            $table->foreignIdFor(FeatureLocus::class, 'locus_id');
            $table->foreignIdFor(FeatureAllele::class, 'primary_allele_id')->nullable();
            $table->foreignIdFor(FeatureAllele::class, 'secondary_allele_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_genetics');
    }
};
