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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable(); // Make nullable for students who might not have email

            // Foreign key to the data entry user who created the booking
            $table->foreignId('user_id')
                ->nullable() // Make nullable
                ->constrained('users')
                ->onDelete('set null');

            // Foreign key for students (optional)
            $table->foreignId('student_id')
                ->nullable()
                ->constrained('students')
                ->onDelete('set null');

            // Foreign key to the coworking space
            $table->foreignId('coworking_space_id')
                ->constrained('coworking_spaces')
                ->onDelete('cascade');

            // Booking details
            $table->enum('booking_type', ['daily', 'weekly', 'monthly', 'three_month']);
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
