<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove the old name column
            $table->dropColumn('name');

            // Add new name fields as nullable first
            $table->string('first_name')->nullable()->after('id');
            $table->string('last_name')->nullable()->after('first_name');

            // Add new fields
            $table->text('comments')->nullable()->after('tags');
            $table->text('topics')->nullable()->after('comments');
            $table->string('region')->nullable()->after('topics');
        });

        // Since the existing data can be removed, we'll clear the users table
        // and then make the name fields required
        DB::table('users')->delete();

        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable(false)->change();
            $table->string('last_name')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove new fields
            $table->dropColumn(['first_name', 'last_name', 'comments', 'topics', 'region']);

            // Add back the old name column
            $table->string('name')->after('id');
        });
    }
};
