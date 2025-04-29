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
        Schema::table('comments', function (Blueprint $table) {
            $table->foreignId('parent_id')->after('content')->nullable()->constrained('comments')->onDelete('cascade');
            $table->unsignedInteger('replies_count')->default(0)->after('parent_id');
            $table->index(['parent_id']);
            $table->index(['recipe_id']);
            $table->index(['user_id']);
            $table->index(['created_at']);
            $table->index(['updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('replies_count');
            $table->dropIndex(['parent_id']);
            $table->dropIndex(['recipe_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['updated_at']);
        });
    }
};
