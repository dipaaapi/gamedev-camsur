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
        Schema::create('student_board_posts', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // achievement, happening
            $table->string('date'); // e.g. "June 19, 2026"
            $table->string('title');
            $table->text('content');
            $table->string('author');
            $table->string('author_initials');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_board_posts');
    }
};
