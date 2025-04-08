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
            $table->index(['user_id']);
            $table->index(['created_at']);
            $table->index(['updated_at']);
            $table->index(['title']);
            $table->index(['slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['updated_at']);
            $table->dropIndex(['title']);
            $table->dropIndex(['slug']);
        });
    }
};
