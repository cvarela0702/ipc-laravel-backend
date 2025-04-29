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
            $table->unsignedInteger('ratings_count')->default(0)->after('calories');
            $table->unsignedInteger('ratings_sum')->default(0)->after('ratings_count');
            $table->float('ratings_avg', 3, 2)->default(0)->after('ratings_sum');
            $table->unsignedInteger('favorites_count')->default(0)->after('ratings_avg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropColumn('ratings_count');
            $table->dropColumn('ratings_sum');
            $table->dropColumn('ratings_avg');
            $table->dropColumn('favorites_count');
        });
    }
};
