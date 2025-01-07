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
        Schema::create('distributors', function (Blueprint $table) {
            $table->id();
            $table->string('enagic_id')->unique('enagic_id_unique')->index('enagic_id_index', 191);
            $table->string('mobile_no');
            $table->string('email')->unique();
            $table->string('name');
            $table->text('address');
            $table->string('area');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->enum('type', ['User', 'Distributor']);
            $table->enum('distributor_status', ['Active', 'Inactive']);
            $table->enum('goal_for', ['User', '3A', '6A', '6A2', '6A2-3']);
            $table->unsignedBigInteger('upline_id');
            $table->unsignedBigInteger('leader_id');
            $table->enum('account_status', ['Active', 'Inactive'])->default('Inactive');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('upline_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('leader_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributors');
    }
};
