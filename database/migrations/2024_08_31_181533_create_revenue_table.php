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
        Schema::create('revenue', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('diploma_id')->nullable();
            $table->date('date_of_rec');
            $table->string('type');
            $table->double('value');
            $table->string('currency');
            $table->unsignedBigInteger('user_id');
            $table->double('value_rec');
            $table->double('value_rem');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('diploma_id')->references('id')->on('diploma');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenue');
    }
};
