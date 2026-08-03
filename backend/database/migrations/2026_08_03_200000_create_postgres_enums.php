<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
            DO $$ BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'sex_enum') THEN
                    CREATE TYPE sex_enum AS ENUM ('male', 'female');
                END IF;
            END $$;
        SQL);

        DB::statement(<<<'SQL'
            DO $$ BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'measurement_source_enum') THEN
                    CREATE TYPE measurement_source_enum AS ENUM ('manual', 'device_sync', 'api');
                END IF;
            END $$;
        SQL);

        DB::statement(<<<'SQL'
            DO $$ BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'goal_status_enum') THEN
                    CREATE TYPE goal_status_enum AS ENUM ('active', 'achieved', 'abandoned');
                END IF;
            END $$;
        SQL);

        DB::statement(<<<'SQL'
            DO $$ BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'activity_level_enum') THEN
                    CREATE TYPE activity_level_enum AS ENUM ('sedentary', 'light', 'moderate', 'active', 'very_active');
                END IF;
            END $$;
        SQL);
    }

    public function down(): void
    {
        DB::statement("DROP TYPE IF EXISTS activity_level_enum CASCADE");
        DB::statement("DROP TYPE IF EXISTS goal_status_enum CASCADE");
        DB::statement("DROP TYPE IF EXISTS measurement_source_enum CASCADE");
        DB::statement("DROP TYPE IF EXISTS sex_enum CASCADE");
    }
};
