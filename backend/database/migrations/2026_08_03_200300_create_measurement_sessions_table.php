<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('measurement_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('device_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('measured_at');
            $table->string('source')->default('manual');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'measured_at']);
            $table->softDeletes();
        });

        DB::statement("ALTER TABLE measurement_sessions ALTER COLUMN source DROP DEFAULT");
        DB::statement("ALTER TABLE measurement_sessions ALTER COLUMN source TYPE measurement_source_enum USING source::measurement_source_enum");
        DB::statement("ALTER TABLE measurement_sessions ALTER COLUMN source SET DEFAULT 'manual'");
        DB::statement("ALTER TABLE measurement_sessions ALTER COLUMN source SET NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE measurement_sessions ALTER COLUMN source DROP DEFAULT");
        DB::statement("ALTER TABLE measurement_sessions ALTER COLUMN source TYPE varchar USING source::varchar");
        Schema::dropIfExists('measurement_sessions');
    }
};
