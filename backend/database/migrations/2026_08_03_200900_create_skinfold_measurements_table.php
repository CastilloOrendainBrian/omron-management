<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skinfold_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('measurement_session_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('skinfold_protocol_id')->constrained('skinfold_protocols')->restrictOnDelete();
            $table->decimal('estimated_body_fat_percentage', 4, 1)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skinfold_measurements');
    }
};
