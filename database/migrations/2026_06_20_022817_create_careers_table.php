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
        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category');
            $table->text('description');
            $table->string('color_class'); // text color of tag (e.g. text-indigo-400)
            $table->string('border_color'); // border color of skewed box (e.g. border-indigo-500)
            $table->string('meta_key_1');
            $table->string('meta_value_1');
            $table->string('meta_key_2');
            $table->string('meta_value_2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('careers');
    }
};
