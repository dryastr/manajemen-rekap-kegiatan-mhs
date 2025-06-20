<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parents_mhs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parents_mhs');
    }
};
