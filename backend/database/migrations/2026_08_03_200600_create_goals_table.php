<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('target_weight_kg', 6, 3)->nullable();
            $table->decimal('target_body_fat_percentage', 4, 1)->nullable();
            $table->date('start_date');
            $table->date('target_date')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE goals ALTER COLUMN status DROP DEFAULT");
        DB::statement("ALTER TABLE goals ALTER COLUMN status TYPE goal_status_enum USING status::goal_status_enum");
        DB::statement("ALTER TABLE goals ALTER COLUMN status SET DEFAULT 'active'");
        DB::statement("ALTER TABLE goals ALTER COLUMN status SET NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE goals ALTER COLUMN status DROP DEFAULT");
        DB::statement("ALTER TABLE goals ALTER COLUMN status TYPE varchar USING status::varchar");
        Schema::dropIfExists('goals');
    }
};
