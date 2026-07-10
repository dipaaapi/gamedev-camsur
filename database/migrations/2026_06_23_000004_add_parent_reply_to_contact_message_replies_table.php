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
        Schema::table('contact_message_replies', function (Blueprint $table) {
            $table->foreignId('parent_reply_id')->nullable()->after('contact_message_id')->constrained('contact_message_replies')->onDelete('cascade');
            $table->boolean('reply_to_original')->default(false)->after('parent_reply_id');
            $table->string('reply_mode')->default('both')->after('reply_to_original');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_message_replies', function (Blueprint $table) {
            $table->dropForeign(['parent_reply_id']);
            $table->dropColumn(['parent_reply_id', 'reply_to_original', 'reply_mode']);
        });
    }
};
