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
        Schema::table('id_cards', function (Blueprint $table) {
            $table->string('class')->nullable();
            $table->string('batch_number')->nullable();
            $table->string('student_phone')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('id_cards', function (Blueprint $table) {
            $table->dropColumn([
                'class',
                'batch_number',
                'student_phone',
                'emergency_contact_name',
                'emergency_contact_phone'
            ]);
        });
    }
};
