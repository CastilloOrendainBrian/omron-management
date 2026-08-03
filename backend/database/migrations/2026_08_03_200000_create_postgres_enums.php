<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE TYPE sex_enum AS ENUM ('male', 'female')");
        DB::statement("CREATE TYPE measurement_source_enum AS ENUM ('manual', 'device_sync', 'api')");
        DB::statement("CREATE TYPE goal_status_enum AS ENUM ('active', 'achieved', 'abandoned')");
        DB::statement("CREATE TYPE activity_level_enum AS ENUM ('sedentary', 'light', 'moderate', 'active', 'very_active')");
    }

    public function down(): void
    {
        DB::statement("DROP TYPE IF EXISTS activity_level_enum");
        DB::statement("DROP TYPE IF EXISTS goal_status_enum");
        DB::statement("DROP TYPE IF EXISTS measurement_source_enum");
        DB::statement("DROP TYPE IF EXISTS sex_enum");
    }
};
