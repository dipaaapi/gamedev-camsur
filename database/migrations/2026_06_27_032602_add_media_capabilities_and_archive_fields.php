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
            $table->boolean('can_post_calendar')->default(false);
            $table->boolean('can_post_announcement')->default(false);
            $table->boolean('can_post_student_board')->default(false);
        });

        Schema::table('calendar_events', function (Blueprint $table) {
            $table->json('media')->nullable();
            $table->boolean('is_archived')->default(false);
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->json('media')->nullable();
            $table->boolean('is_archived')->default(false);
        });

        Schema::table('student_board_posts', function (Blueprint $table) {
            $table->json('media')->nullable();
            $table->boolean('is_archived')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['can_post_calendar', 'can_post_announcement', 'can_post_student_board']);
        });

        Schema::table('calendar_events', function (Blueprint $table) {
            $table->dropColumn(['media', 'is_archived']);
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn(['media', 'is_archived']);
        });

        Schema::table('student_board_posts', function (Blueprint $table) {
            $table->dropColumn(['media', 'is_archived']);
        });
    }
};
