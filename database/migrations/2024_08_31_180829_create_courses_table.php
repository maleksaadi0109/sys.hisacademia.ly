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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('section');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('level');
            $table->time('start_time');
            $table->time('end_time');
            $table->double('total_days');
            $table->double('average_hours');
            $table->double('total_hours');
            $table->double('n_d_per_week');
            $table->json('days');
            $table->double('price');
            $table->string('currency');
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('diploma_id')->nullable();
            $table->foreign('diploma_id')->references('id')->on('diploma');
            $table->foreign('teacher_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
