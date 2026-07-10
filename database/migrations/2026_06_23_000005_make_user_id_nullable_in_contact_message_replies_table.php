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
        // Safely drop foreign key if it exists
        try {
            Schema::table('contact_message_replies', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        } catch (\Exception $e) {
            // Foreign key may already be dropped – continue
        }

        Schema::table('contact_message_replies', function (Blueprint $table) {
            // Change column to nullable and re-add the foreign key
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safely drop foreign key if it exists
        try {
            Schema::table('contact_message_replies', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        } catch (\Exception $e) {
            // Foreign key may already be dropped – continue
        }

        // Remove rows with NULL user_id before reverting column to NOT NULL
        \DB::table('contact_message_replies')->whereNull('user_id')->delete();

        Schema::table('contact_message_replies', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
