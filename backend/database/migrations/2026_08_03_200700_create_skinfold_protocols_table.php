<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skinfold_protocols', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('sites_count');
            $table->text('description')->nullable();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skinfold_protocols');
    }
};
