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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->date('dob');
            $table->string('phone');
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->string('country')->nullable();
            $table->string('feature_access');
            $table->dateTime('last_login')->useCurrent();
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('upline_id')->nullable();
            $table->unsignedBigInteger('leader_id')->nullable();
            $table->string('enagic_id')->nullable();
            $table->enum('type', ['User', 'Distributor'])->default('User');
            $table->enum('distributor_status', ['Active', 'Inactive']);
            $table->enum('goal_for', ['User', '3A', '6A', '6A2', '6A2-3'])->default('User');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
