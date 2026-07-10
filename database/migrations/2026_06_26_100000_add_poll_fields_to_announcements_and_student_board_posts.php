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
        Schema::table('announcements', function (Blueprint $table) {
            $table->string('poll_question')->nullable();
            $table->json('poll_options')->nullable();
            $table->json('poll_votes')->nullable();
        });

        Schema::table('student_board_posts', function (Blueprint $table) {
            $table->string('poll_question')->nullable();
            $table->json('poll_options')->nullable();
            $table->json('poll_votes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn(['poll_question', 'poll_options', 'poll_votes']);
        });

        Schema::table('student_board_posts', function (Blueprint $table) {
            $table->dropColumn(['poll_question', 'poll_options', 'poll_votes']);
        });
    }
};
