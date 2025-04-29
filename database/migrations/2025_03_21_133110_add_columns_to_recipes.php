<?php

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
        Schema::table('recipes', function (Blueprint $table) {
            $table->string('slug')->unique()->after('title');
            $table->string('image_url')->nullable()->after('slug');
            $table->string('video_url')->nullable()->nullable()->after('image_url');
            $table->integer('servings')->default(2)->after('video_url');
            $table->integer('prep_time_hours')->default(0)->after('servings');
            $table->integer('prep_time_minutes')->default(0)->after('prep_time_hours');
            $table->integer('cook_time_hours')->default(0)->after('prep_time_minutes');
            $table->integer('cook_time_minutes')->default(0)->after('cook_time_hours');
            $table->integer('calories')->default(0)->after('cook_time_minutes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropColumn('image_url');
            $table->dropColumn('video_url');
            $table->dropColumn('servings');
            $table->dropColumn('prep_time_hours');
            $table->dropColumn('prep_time_minutes');
            $table->dropColumn('cook_time_hours');
            $table->dropColumn('cook_time_minutes');
            $table->dropColumn('calories');
        });
    }
};
