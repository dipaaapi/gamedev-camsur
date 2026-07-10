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
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->unsignedTinyInteger('rating')->nullable()->after('status');
            $table->text('resolution_comment')->nullable()->after('rating');
            $table->boolean('is_flagged')->default(false)->after('resolution_comment');
        });

        Schema::table('contact_message_replies', function (Blueprint $table) {
            $table->boolean('is_flagged')->default(false)->after('message');
            $table->string('attachment_path')->nullable()->after('is_flagged');
            $table->string('attachment_name')->nullable()->after('attachment_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropColumn(['rating', 'resolution_comment', 'is_flagged']);
        });

        Schema::table('contact_message_replies', function (Blueprint $table) {
            $table->dropColumn(['is_flagged', 'attachment_path', 'attachment_name']);
        });
    }
};
