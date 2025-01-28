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
        Schema::table('video_lessons', function (Blueprint $table) {
            // Add thumbnail URL
            $table->string('thumbnail_url')->nullable()->after('video_url');

            // Add created_by, updated_by, and deleted_by columns
            $table->unsignedBigInteger('created_by')->nullable()->after('thumbnail_url');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            $table->unsignedBigInteger('deleted_by')->nullable()->after('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('video_lessons', function (Blueprint $table) {
            //
            // Remove added columns
            $table->dropColumn(['thumbnail_url', 'created_by', 'updated_by', 'deleted_by']);
        });
    }
};
