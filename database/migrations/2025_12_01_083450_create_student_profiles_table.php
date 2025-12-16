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
    Schema::create('student_profiles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        $table->string('student_id')->nullable();
        $table->string('first_name')->nullable();
        $table->string('last_name')->nullable();
        $table->string('phone')->nullable();
        $table->string('course')->nullable();
        $table->string('year_level')->nullable();
        $table->text('address')->nullable();
        $table->string('profile_picture')->nullable(); // path to uploaded image

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
