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
            $table->string('sms')->nullable();
            $table->enum('provider', ['Verizon', 'AT&T', 'T-Mobile', 'Sprint', 'Other'])->nullable();
            $table->boolean('sms_opt_in')->default(false);
            $table->boolean('email_opt_in')->default(false);
            $table->string('how_did_you_hear')->nullable();
            $table->string('paid_via')->nullable();
            $table->json('tags')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'sms',
                'provider',
                'sms_opt_in',
                'email_opt_in',
                'how_did_you_hear',
                'paid_via',
                'tags'
            ]);
        });
    }
};
