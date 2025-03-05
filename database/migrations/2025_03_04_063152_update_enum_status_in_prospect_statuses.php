<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Add the new column first
        Schema::table('prospect_statuses', function (Blueprint $table) {
            $table->enum('new_status', ['Prospect', 'Invitation', 'Demo', 'Followup', 'Machine Purchased'])
                ->after('status')
                ->nullable(); // Temporary nullable to avoid errors during update
        });

        // Step 2: Copy existing data from old `status` column to `new_status`
        DB::statement('UPDATE prospect_statuses SET new_status = status');

        // Step 3: Remove the old column
        Schema::table('prospect_statuses', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        // Step 4: Rename `new_status` to `status`
        Schema::table('prospect_statuses', function (Blueprint $table) {
            $table->renameColumn('new_status', 'status');
        });

        // Step 5: Make the column NOT NULL (optional)
        Schema::table('prospect_statuses', function (Blueprint $table) {
            $table->enum('status', ['Prospect', 'Invitation', 'Demo', 'Followup', 'Machine Purchased'])
                ->nullable(false)
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the changes if needed
        Schema::table('prospect_statuses', function (Blueprint $table) {
            $table->enum('old_status', ['Invitation', 'Demo', 'Followup', 'Machine Purchased'])->after('status')->nullable();
        });

        DB::statement('UPDATE prospect_statuses SET old_status = status');

        Schema::table('prospect_statuses', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->renameColumn('old_status', 'status');
        });

        Schema::table('prospect_statuses', function (Blueprint $table) {
            $table->enum('status', ['Invitation', 'Demo', 'Followup', 'Machine Purchased'])->change();
        });
    }
};
