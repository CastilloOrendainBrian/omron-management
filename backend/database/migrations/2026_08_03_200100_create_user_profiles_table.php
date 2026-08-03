<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('sex');
            $table->date('birth_date');
            $table->decimal('height_reference_cm', 5, 2)->nullable();
            $table->string('activity_level')->nullable();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE user_profiles ALTER COLUMN sex TYPE sex_enum USING sex::sex_enum");
        DB::statement("ALTER TABLE user_profiles ALTER COLUMN sex SET NOT NULL");
        DB::statement("ALTER TABLE user_profiles ALTER COLUMN activity_level TYPE activity_level_enum USING activity_level::activity_level_enum");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE user_profiles ALTER COLUMN sex TYPE varchar USING sex::varchar");
        DB::statement("ALTER TABLE user_profiles ALTER COLUMN activity_level TYPE varchar USING activity_level::varchar");
        Schema::dropIfExists('user_profiles');
    }
};
