<?php

use App\Models\Character\Character;
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
        Schema::create('character_ancestors', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignIdFor(Character::class, 'character_id');
            $table->foreignIdFor(Character::class, 'ancestor_id');
            $table->enum('type', ['sire','dam','ss','sd','ds','dd','sss','ssd','sds','sdd','dss','dsd','dds','ddd']);
            $table->timestamps();
            $table->unique(['character_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_ancestors');
    }
};
