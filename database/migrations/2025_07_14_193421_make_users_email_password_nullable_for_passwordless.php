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
        Schema::table('users', function (Blueprint $table) {
            // Make email nullable and remove unique constraint
            $table->dropUnique(['email']);
            $table->string('email')->nullable()->change();

            // Make password nullable for passwordless users
            $table->string('password')->nullable()->change();

            // Add unique constraint that allows multiple null emails
            $table->unique(['email'], 'users_email_unique_not_null')
                  ->whereNotNull('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove the conditional unique constraint
            $table->dropUnique('users_email_unique_not_null');

            // Make email required and unique again
            $table->string('email')->unique()->change();

            // Make password required again
            $table->string('password')->change();
        });
    }
};
