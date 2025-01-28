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
        Schema::table('quiz_options', function (Blueprint $table) {
            // Modify the is_correct column to ensure it has a default value of false
            $table->boolean('is_correct')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_options', function (Blueprint $table) {
            // Revert the is_correct column to its previous state
            // You may need to adjust this if you know the original column definition
            $table->boolean('is_correct')->default(null)->change();
        });
    }
};
