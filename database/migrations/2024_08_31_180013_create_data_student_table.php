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
        Schema::create('data_student', function (Blueprint $table) {
            $table->id();
            $table->string('section')->nullable();
            $table->string('level')->nullable();
            $table->enum('attend',['online', 'physicist'])->nullable();
            $table->enum('course_type',['regular', 'translation', 'methodological', 'assignment', 'diploma', 'childrens', 'conversation'])->nullable();
            $table->json('course_days')->nullable();
            $table->time('course_start_time')->nullable();
            $table->time('course_end_time')->nullable();
            $table->string('classroom_name')->nullable();
            $table->enum('cultural_activity',['yes','no'])->nullable();
            $table->string('payment_method')->nullable();
            $table->string('signature')->nullable();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_student');
    }
};
