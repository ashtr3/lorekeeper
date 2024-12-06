<?php

use App\Models\Feature\Feature;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('feature_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Feature::class, 'override_id');
            $table->foreignIdFor(Feature::class, 'hidden_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('feature_overrides');
    }
};
