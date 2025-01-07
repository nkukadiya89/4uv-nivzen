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
        Schema::create('support_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_user_id'); // User who raises the request
            $table->unsignedBigInteger('to_user_id'); // User receiving the request
            $table->string('support_name');
            $table->text('description');
            $table->string('request_number')->unique(); // Unique request number
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_requests');
    }
};
