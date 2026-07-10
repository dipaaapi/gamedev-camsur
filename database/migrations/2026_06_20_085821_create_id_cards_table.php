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
        Schema::create('id_cards', function (Blueprint $table) {
            $table->id();
            $table->string('id_number')->unique();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('dob');
            $table->text('permanent_address');
            $table->text('current_address')->nullable();
            $table->string('gender');
            $table->integer('age');
            $table->string('id_picture')->nullable();
            $table->date('validity_date');
            $table->string('status'); // new, renew, archived, graduated, dismissed, deceased
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('id_cards');
    }
};
