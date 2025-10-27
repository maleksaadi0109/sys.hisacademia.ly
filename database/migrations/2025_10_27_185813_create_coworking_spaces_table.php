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
        Schema::create('coworking_spaces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();


            $table->decimal('daily_price', 8, 2)->nullable();
            $table->decimal('weekly_price', 8, 2)->nullable();
            $table->decimal('monthly_price', 8, 2)->nullable();
            $table->decimal('three_month_price', 8, 2)->nullable();


            $table->decimal('student_daily_price', 8, 2)->nullable();
            $table->decimal('student_weekly_price', 8, 2)->nullable();
            $table->decimal('student_monthly_price', 8, 2)->nullable();
            $table->decimal('student_three_month_price', 8, 2)->nullable();


            $table->boolean('is_open_weekdays')->default(true);
            $table->time('open_time')->default('09:00:00');
            $table->time('close_time')->default('23:00:00');
            $table->string('internet_speed')->default('30Mbps');
            $table->text('address_line1')->nullable();

            $table->integer('capacity')->unsigned()->default(1);




            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coworking_spaces');
    }
};
